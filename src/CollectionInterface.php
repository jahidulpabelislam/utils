<?php

declare(strict_types=1);

namespace JPI\Utils;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface CollectionInterface extends
    Arrayable,
    ArrayAccess,
    Countable,
    IteratorAggregate {

    public function isset(string|int $key): bool;

    public function add($item): void;

    public function set(string|int $key, $item): void;

    public function unset(string|int $key): void;

    public function get(string|int $key, $default = null);

    public function getCount(): int;

    public function each(callable $callback): void;

    public function pluck(string $toPluck, string $keyedBy = null): CollectionInterface;

    public function groupBy(string $groupByKey): CollectionInterface;
}
