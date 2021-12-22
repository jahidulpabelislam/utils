<?php

namespace JPI\Utilities;

trait PaginatedCollection {

    protected $limit;
    protected $page;
    protected $totalCount;

    public function __construct(array $items, int $limit, int $page = 1, int $totalCount = null) {
        parent::__construct($items);
        $this->limit = $limit;
        $this->page = $page;
        $this->totalCount = $totalCount;
    }

    public function getTotalCount(): int {
        return $this->totalCount ?? $this->count();
    }

    public function getLimit(): int {
        return $this->limit;
    }

    public function getPage(): int {
        return $this->page;
    }
}
