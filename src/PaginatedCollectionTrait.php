<?php

declare(strict_types=1);

namespace JPI\Utils;

/**
 * Collection trait you use on collection classes that will be used for paginated results.
 */
trait PaginatedCollectionTrait {

    use ImmutableCollectionTrait;

    /**
     * @var int
     */
    protected $totalCount;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $page;

    /**
     * @param array $items
     * @param int $totalCount
     * @param int $limit
     * @param int $page
     */
    public function __construct(array $items, int $totalCount, int $limit, int $page) {
        $this->items = $items;
        $this->count = count($this->items);
        $this->totalCount = $totalCount;
        $this->limit = $limit;
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getLimit(): int {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage(): int {
        return $this->page;
    }
}
