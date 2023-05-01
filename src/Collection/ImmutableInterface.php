<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JPI\Utils\Arrayable;
use JPI\Utils\CollectionInterface;

interface ImmutableInterface extends
    Arrayable,
    ArrayAccess,
    Countable,
    IteratorAggregate {

    public function isset(string|int $key): bool;

    public function get(string|int $key, $default = null);

    public function getCount(): int;

    public function each(callable $callback): void;

    public function pluck(string $toPluck, string $keyedBy = null): CollectionInterface;

    public function groupBy(string $groupByKey): CollectionInterface;
}
