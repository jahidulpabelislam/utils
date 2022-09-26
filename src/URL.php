<?php

declare(strict_types = 1);

namespace JPI\Utils;

class URL {

    public static function removeLeadingSlash(string $url): string {
        $url = trim($url, " ");
        return ltrim($url, "/");
    }

    public static function removeTrailingSlash(string $url): string {
        $url = trim($url, " ");
        return rtrim($url, "/");
    }

    public static function removeSlashes(string $url): string {
        return trim($url, " /");
    }

    public static function addLeadingSlash(string $url): string {
        $url = trim($url, " ");
        $url = ltrim($url, "/");
        return "/$url";
    }

    public static function addTrailingSlash(string $url): string {
        $url = trim($url, " ");
        $url = rtrim($url, "/");
        return "$url/";
    }
}
