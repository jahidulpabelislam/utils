<?php

/**
 * Very simple trait to add to classes that are singleton.
 *
 * Assumes that nothing is required for the constructor.
 *
 * @author Jahidul Pabel Islam <me@jahidulpabelislam.com>
 * @copyright 2012-2022 JPI
 */

declare(strict_types=1);

namespace JPI\Utils;

trait Singleton {

    protected static $instance = null;

    /**
     * @return static
     */
    public static function get() {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Singleton constructor.
     *
     * By default don't allow creating instances of the class outside the getter
     */
    protected function __construct() {}
}
