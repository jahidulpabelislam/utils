<?php

declare(strict_types=1);

namespace JPI\Utils;

use JPI\Utils\Collection\ImmutableInterface;

interface CollectionInterface extends ImmutableInterface {

    public function add($item): void;

    public function set(string|int $key, $item): void;

    public function unset(string|int $key): void;

    public function clear(): void;
}
