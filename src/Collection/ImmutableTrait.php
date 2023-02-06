<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

use Exception;

/**
 * Trait to go on collection classes that are immutable.
 */
trait ImmutableTrait {

    protected function resetCount(): void {
        // NOP
    }

    public function add($item): void {
        throw new Exception("Collection is immutable, adding is not allowed");
    }

    public function set(string $key, $item): void {
        throw new Exception("Collection is immutable, adding/updating is not allowed");
    }

    public function unset(string $key): void {
        throw new Exception("Collection is immutable, removing is not allowed");
    }
}
