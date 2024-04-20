<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

/**
 * A collection class to hold items and is immutable.
 */
class Immutable implements ImmutableInterface {

    use BaseTrait;

    public function offsetSet($key, $item): void {
        throw new Exception("Collection is immutable, adding/updating is not allowed");
    }

    public function offsetUnset($key): void {
        throw new Exception("Collection is immutable, removing is not allowed");
    }
}
