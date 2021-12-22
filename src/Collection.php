<?php

namespace JPI\Utilities;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class Collection implements Arrayable, ArrayAccess, Countable, IteratorAggregate {

    protected $items;
    protected $count = null;

    public function __construct(array $items = []) {
        $this->items = $items;
    }

    protected function resetCount(): void {
        $this->count = null;
    }

    public function set($key, $item): void {
        $this->items[$key] = $item;
        $this->resetCount();
    }

    public function add($item): void {
        $this->items[] = $item;
        $this->resetCount();
    }

    public function removeByKey($key): bool {
        unset($this->items[$key]);
        $this->resetCount();
    }

    protected function doesKeyExist($key): bool {
        return array_key_exists($key, $this->items);
    }

    public function get($key, $default = null) {
        return $this->items[$key] ?? $default;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function toArray(): array {
        return $this->getItems();
    }

    // ArrayAccess - Start //

    public function offsetExists($offset): bool {
        return $this->doesKeyExist($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $item): void {
        if ($offset === null) {
            $this->add($item);
        }
        else {
            $this->set($offset, $item);
        }
    }

    public function offsetUnset($offset): void {
        $this->removeByKey($offset);
    }

    // ArrayAccess - End //

    // IteratorAggregate - Start //

    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->getItems());
    }

    // IteratorAggregate - End //

    // Countable - Start //

    public function count(): int {
        if ($this->count === null) {
            $this->count = count($this->getItems());
        }

        return $this->count;
    }

    // Countable - End //

    protected static function getFromItem($item, $key, $default = null) {
        if (is_object($item)) {
            if (isset($item->{$key})) {
                return $item->{$key};
            }

            if (method_exists($item, $key)) {
                return $item->{$key}();
            }
        }

        if (
            is_array($item)
            || $item instanceof Arrayable
            || $item instanceof ArrayAccess
        ) {
            $array = $item instanceof Arrayable ? $item->toArray() : $item;
            if (isset($array[$key])) {
                return $array[$key];
            }
        }

        return $default;
    }

    public function each(callable $callback): void {
        foreach ($this as $key => $item) {
            $callback($key, $item);
        }
    }

    public function pluck($toPluck, $keyedBy = null): Collection {
        $plucked = new Collection();

        $this->each(function ($key, $item) use ($plucked, $toPluck, $keyedBy) {
            $value = static::getFromItem($item, $toPluck);

            if ($keyedBy) {
                $keyValue = static::getFromItem($item, $keyedBy);
                $plucked->set($keyValue, $value);
            }
            else {
                $plucked->add($value);
            }
        });

        return $plucked;
    }

    public function groupBy($groupByKey): Collection {
        $collection = new Collection();

        $this->each(function ($key, $item) use ($collection, $groupByKey) {
            $value = static::getFromItem($item, $groupByKey);

            if (!isset($collection[$value])) {
                $collection->set($value, new static([$item]));
            }
            else {
                $collection[$value]->add($item);
            }
        });

        return $collection;
    }
}
