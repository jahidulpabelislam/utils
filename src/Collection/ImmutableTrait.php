<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

/**
 * Trait to go on classes that extend '\JPI\Utils\Collection' to make them immutable.
 */
trait ImmutableTrait {

    protected function resetCount(): void {
        // NOP
    }

    public function add($item): void {
        throw new Exception("Collection is immutable, adding is not allowed");
    }

    public function set(string|int $key, $item): void {
        throw new Exception("Collection is immutable, adding/updating is not allowed");
    }

    public function unset(string|int $key): void {
        throw new Exception("Collection is immutable, removing is not allowed");
    }
}
