<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

/**
 * Collection trait you use on collection classes that will be used for paginated results.
 */
trait PaginatedTrait {

    use ImmutableTrait;

    public function __construct(
        protected array $items,
        protected int $totalCount,
        protected int $limit,
        protected int $page
    ) {
        $this->count = count($this->items);
    }

    public function getTotalCount(): int {
        return $this->totalCount;
    }

    public function getLimit(): int {
        return $this->limit;
    }

    public function getPage(): int {
        return $this->page;
    }
}
