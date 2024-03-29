<?php

declare(strict_types=1);

namespace JPI\Utils;

use Stringable;

/**
 * URL builder & helper methods around URLs.
 */
class URL implements Stringable {

    public static function removeLeadingSlash(string $path): string {
        $path = trim($path, " ");
        return ltrim($path, "/");
    }

    public static function removeTrailingSlash(string $url): string {
        $url = trim($url, " ");
        return rtrim($url, "/");
    }

    /**
     * Remove both leading & trailing slashes from passed path (if there is any).
     */
    public static function removeSlashes(string $path): string {
        return trim($path, " /");
    }

    public static function addLeadingSlash(string $path): string {
        $path = trim($path, " ");
        $path = ltrim($path, "/");
        return "/$path";
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

    protected ?string $scheme = null;

    protected ?string $host = null;

    protected ?string $path = null;

    protected array $queryParams = [];

    protected ?string $fragment = null;

    /**
     * Whether to add a trailing slash at the end of the path.
     */
    protected bool $addTrailingSlash = true;

    /**
     * Parse the components out from passed URL string if passed.
     */
    public function __construct(string $url = null) {
        if (!$url) {
            return;
        }

        $isProtocolRelative = str_starts_with($url, "//");

        if ($isProtocolRelative) {
            $url = "https:$url";
        }

        $parsed = parse_url($url);

        $this->setScheme($isProtocolRelative ? "//" : ($parsed["scheme"] ?? null));

        $this->setHost($parsed["host"] ?? null);
        $this->setPath($parsed["path"] ?? null);

        if (isset($parsed["query"])) {
            parse_str($parsed["query"], $queryParams);
            $this->setQueryParams($queryParams);
        }

        $this->setFragment($parsed["fragment"] ?? null);
    }

    public function setAddTrailingSlash(bool $addTrailingSlash): void {
        $this->addTrailingSlash = $addTrailingSlash;
    }

    public function setScheme(string $scheme = null): void {
        $this->scheme = $scheme;
    }

    public function getScheme(): ?string {
        return $this->scheme;
    }

    public function setHost(string $host = null): void {
        $this->host = $host;
    }

    public function getHost(): ?string {
        return $this->host;
    }

    public function setPath(string $path = null): void {
        $this->path = $path;
    }

    /**
     * Add part(s) to the current path.
     */
    public function addPath(string $path): void {
        if (!$this->path) {
            $this->setPath($path);
            return;
        }
        $this->setPath(static::addTrailingSlash($this->path) . static::removeLeadingSlash($path));
    }

    public function getPath(): ?string {
        return $this->path;
    }

    /**
     * Set query parameters.
     */
    public function setQueryParams(array $params): void {
        $this->queryParams = $params;
    }

    /**
     * Add/set query parameter.
     */
    public function setQueryParam(string $param, array|string|int $value): void {
        $this->queryParams[$param] = $value;
    }

    /**
     * Remove a query parameter.
     */
    public function removeQueryParam(string $param): void {
        unset($this->queryParams[$param]);
    }

    /**
     * Get query parameters.
     */
    public function getQueryParams(): array {
        return $this->queryParams;
    }

    public function getQuery(): ?string {
        if (!$this->queryParams) {
            return null;
        }

        return http_build_query($this->queryParams);
    }

    public function setFragment(string $fragment = null): void {
        $this->fragment = $fragment;
    }

    public function getFragment(): ?string {
        return $this->fragment;
    }

    /**
     * Build the URL from current values of each component.
     */
    public function build(): string {
        $string = "";

        if ($this->scheme) {
            if ($this->scheme === "//") {
                $string = "//";
            } else {
                $string = "$this->scheme://";
            }
        }

        if ($this->host) {
            $string .= $this->host;
        }

        if ($this->path) {
            $string .= static::addLeadingSlash($this->path);
        }

        if ($this->addTrailingSlash && ($this->path || $this->queryParams || $this->fragment)) {
            $string = static::addTrailingSlash($string);
        }

        if ($this->queryParams) {
            $string .= "?" . $this->getQuery();
        }

        if ($this->fragment) {
            $string .= "#$this->fragment";
        }

        return $string;
    }

    public function __toString(): string {
        return $this->build();
    }
}
