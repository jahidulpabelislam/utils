<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

use JPI\Utils\Collection;

/**
 * Collection that is from a paginated result.
 */
class Paginated extends Collection {

    use PaginatedTrait;
}
