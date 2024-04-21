<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

use JPI\Utils\Collection;

/**
 * A collection class to hold items and is immutable.
 */
class Immutable extends Collection implements ImmutableInterface {

    use ImmutableTrait;
}
