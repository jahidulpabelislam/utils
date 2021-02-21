<?php

namespace JPI\Utilities;

class Text {

    public static function removeLeadingSlash(string $url): string {
        if ($url[0] === "/") {
            $url = substr($url, 1);
        }

        return $url;
    }

    public static function removeTrailingSlash(string $url): string {
        if (substr($url, -1) === "/") {
            $url = substr($url, 0, -1);
        }

        return $url;
    }

    public static function removeSlashes(string $url): string {
        $url = static::removeLeadingSlash($url);
        $url = static::removeTrailingSlash($url);

        return $url;
    }

    public static function addTrailingSlash(string $url): string {
        $url = static::removeTrailingSlash($url);

        return "{$url}/";
    }

    public static function addLeadingSlash(string $url): string {
        $url = static::removeLeadingSlash($url);

        return "/{$url}";
    }

    public static function addSlashes(string $url): string {
        $url = static::removeSlashes($url);

        return "/{$url}/";
    }

    public static function stringToBoolean(?string $string, ?bool $default = false): ?bool {
        if (in_array($string, ["", null], true)) {
            return $default;
        }

        return filter_var($string, FILTER_VALIDATE_BOOLEAN);
    }

}
