<?php

declare(strict_types=1);

namespace JPI\Utils\Collection;

interface PaginatedInterface extends ImmutableInterface {

    public function getTotalCount(): int;

    public function getLimit(): int;

    public function getPage(): int;
}
