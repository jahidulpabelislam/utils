<?php

declare(strict_types=1);

namespace JPI\Utils;

/**
 * Interface for classes that can be converted to an array.
 *
 * @author Jahidul Pabel Islam <me@jahidulpabelislam.com>
 * @copyright 2012-2022 JPI
 */
interface Arrayable {

    /**
     * @return array
     */
    public function toArray(): array;
}
