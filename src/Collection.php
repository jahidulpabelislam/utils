<?php

declare(strict_types=1);

namespace JPI\Utils;

use JPI\Utils\Collection\BaseTrait;

/**
 * A collection class to hold items and isn't immutable.
 */
class Collection implements CollectionInterface {

    use BaseTrait;

    protected function resetCount(): void {
        $this->count = null;
    }

    public function add($item): void {
        $this->items[] = $item;
        $this->resetCount();
    }

    public function set(string|int $key, $item): void {
        $this->items[$key] = $item;
        $this->resetCount();
    }

    public function unset(string|int $key): void {
        unset($this->items[$key]);
        $this->resetCount();
    }

    public function clear(): void {
        $this->items = [];
        $this->resetCount();
    }

    public function offsetSet($key, $item): void {
        if ($key === null) {
            $this->add($item);
        }
        else {
            $this->set($key, $item);
        }
    }

    public function offsetUnset($key): void {
        $this->unset($key);
    }
}
