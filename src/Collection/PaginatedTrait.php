<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

/**
 * Collection trait you use on collection classes that will be used for paginated results.
 */
trait PaginatedTrait {

    use ImmutableTrait;

    protected $totalCount;

    protected $limit;

    protected $page;

    public function __construct(array $items, int $totalCount, int $limit, int $page) {
        $this->items = $items;
        $this->count = count($this->items);
        $this->totalCount = $totalCount;
        $this->limit = $limit;
        $this->page = $page;
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
