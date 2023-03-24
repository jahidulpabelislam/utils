<?php

declare(strict_types=1);

namespace JPI\Utils;

/**
 * Interface for classes that can be converted to an array.
 */
interface Arrayable {

    public function toArray(): array;
}
