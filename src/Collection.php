<?php

declare(strict_types=1);

namespace JPI\Utils;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * A collection class.
 *
 * @author Jahidul Pabel Islam <me@jahidulpabelislam.com>
 * @copyright 2012-2022 JPI
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate {

    protected $items;

    /**
     * @var int|null
     */
    protected $count = null;

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = []) {
        $this->items = $items;
    }

    /**
     * @return void
     */
    protected function resetCount(): void {
        $this->count = null;
    }

    /**
     * @param mixed $item
     * @return void
     */
    public function add($item): void {
        $this->items[] = $item;
        $this->resetCount();
    }

    /**
     * @param string $key
     * @param mixed $item
     * @return void
     */
    public function set(string $key, $item): void {
        $this->items[$key] = $item;
        $this->resetCount();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null) {
        return $this->items[$key] ?? $default;
    }

    /**
     * @param string $key
     * @return void
     */
    public function unset(string $key): void {
        unset($this->items[$key]);
        $this->resetCount();
    }

    /**
     * @return array
     */
    public function getItems(): array {
        return $this->items;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function offsetExists($key): bool {
        return $this->isset($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key) {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @param mixed $item
     * @return void
     */
    public function offsetSet($key, $item): void {
        if ($key === null) {
            $this->add($key);
        }
        else {
            $this->set($key, $item);
        }
    }

    /**
     * @param string $key
     * @return void
     */
    public function offsetUnset($key): void {
        $this->unset($key);
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->getItems());
    }

    /**
     * @return int
     */
    public function count(): int {
        if ($this->count === null) {
            $this->count = count($this->getItems());
        }

        return $this->count;
    }

    /**
     * @param mixed $item
     * @param string $key
     * @return mixed|null
     */
    protected static function getFromItem($item, string $key) {
        if (is_array($item)) {
            return $item[$key] ?? null;
        }

        if (is_object($item)) {
            if ($item instanceof ArrayAccess && isset($item[$key])) {
                return $item[$key];
            }

            if (isset($item->{$key})) {
                return $item->{$key};
            }

            if (method_exists($item, $key)) {
                return $item->{$key}();
            }
        }

        return null;
    }

    /**
     * @param callable $callback
     * @return void
     */
    public function each(callable $callback): void {
        foreach ($this as $key => $item) {
            $callback($key, $item);
        }
    }

    /**
     * @param string $toPluck
     * @param string|null $keyedBy
     * @return Collection
     */
    public function pluck(string $toPluck, string $keyedBy = null): Collection {
        $plucked = new Collection();

        $this->each(function (string $key, $item) use ($plucked, $toPluck, $keyedBy) {
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

    /**
     * @param string $groupByKey
     * @return Collection
     */
    public function groupBy(string $groupByKey): Collection {
        $collection = new Collection();

        $this->each(function (string $key, $item) use ($collection, $groupByKey) {
            $value = static::getFromItem($item, $groupByKey);

            if (!isset($collection[$value])) {
                $collection->set($value, new static([$item]));
            }
            else {
                $collection->get($value)->add($item);
            }
        });

        return $collection;
    }
}
