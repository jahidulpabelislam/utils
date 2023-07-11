<?php

namespace JPI\Utils\Collection;

use ArrayIterator;
use JPI\Utils\Arrayable;
use JPI\Utils\Collection;

/**
 * A trait which can go on any type of collection, one that immutable, paginated or not.
 */
trait BaseTrait {

    protected ?int $count = null;

    public function __construct(protected array $items = []) {
    }

    public function isset(string|int $key): bool {
        return array_key_exists($key, $this->items);
    }

    public function get(string|int $key, $default = null) {
        return $this->items[$key] ?? $default;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function offsetExists($key): bool {
        return $this->isset($key);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($key) {
        return $this->get($key);
    }

    public function getIterator(): ArrayIterator {
        return new ArrayIterator($this->getItems());
    }

    public function count(): int {
        if ($this->count === null) {
            $this->count = count($this->getItems());
        }

        return $this->count;
    }

    public function getCount(): int {
        return $this->count();
    }

    protected static function getFromItem($item, string|int $key) {
        if (is_array($item)) {
            return $item[$key] ?? null;
        }

        if (is_object($item)) {
            if ($item instanceof ArrayAccess && isset($item[$key])) {
                return $item[$key];
            }

            if ($item instanceof Arrayable) {
                $array = $item->toArray();
                if (isset($array[$key])) {
                    return $array[$key];
                }
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

    public function each(callable $callback): void {
        foreach ($this as $key => $item) {
            $callback($key, $item);
        }
    }

    public function pluck(string $toPluck, string $keyedBy = null): Collection {
        $plucked = new Collection();

        $this->each(function (string|int $key, $item) use ($plucked, $toPluck, $keyedBy) {
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

    public function groupBy(string $groupByKey): Collection {
        $collection = new Collection();

        $this->each(function (string|int $key, $item) use ($collection, $groupByKey) {
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

    /**
     * Try to get an array of items - also convert children to array if arrayable.
     */
    public function toArray(): array {
        $array = [];

        foreach ($this->items as $key => $item) {
            if ($item instanceof Arrayable) {
                $item = $item->toArray();
            }

            $array[$key] = $item;
        }

        return $array;
    }

    public function __clone() {
        foreach ($this->items as $key => $value) {
            if (is_object($value)) {
                $this->items[$key] = clone $value;
            }
        }
    }
}
