<?php

declare(strict_types=1);

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

        // If the last path segment includes a full stop, assume it's a file... so don't add trailing slash
        $path = parse_url($url, PHP_URL_PATH);
        if ($path) {
            $splitPaths = explode("/", $path);
            $count = count($splitPaths);
            if (strpos($splitPaths[$count - 1], ".")) {
                return $url;
            }
        }

        return "$url/";
    }
}
