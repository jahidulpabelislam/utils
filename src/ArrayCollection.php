<?php

namespace JPI\Utilities;

class ArrayCollection extends Collection {

    /**
     * @param $item array
     * @param $key string
     * @param $default mixed
     * @return string|int|float|null
     */
    protected static function getFromItem($item, $key, $default = null) {
        return $item[$key] ?? $default;
    }
}
