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

    /**
     * @var string|null
     */
    protected $scheme;

    /**
     * @var string|null
     */
    protected $host;

    /**
     * @var string|null
     */
    protected $path;

    protected $params = [];

    /**
     * @var string|null
     */
    protected $fragment;

    /**
     * Whether to add a trailing slash at the end of the path.
     */
    protected $addTrailingSlash = true;

    /**
     * Parse the components out from passed URL string if passed.
     */
    public function __construct(string $url = null) {
        if (!$url) {
            return;
        }

        $isProtocolRelative = strpos($url, "//") === 0;

        if ($isProtocolRelative) {
            $url = "https:$url";
        }

        $parsed = parse_url($url);

        $this->setScheme($isProtocolRelative ? "//" : ($parsed["scheme"] ?? null));

        $this->setHost($parsed["host"] ?? null);
        $this->setPath($parsed["path"] ?? null);

        if (isset($parsed["query"])) {
            parse_str($parsed["query"], $params);
            $this->setParams($params);
        }

        $this->setFragment($parsed["fragment"] ?? null);
    }

    public function setScheme(string $scheme = null): URL {
        $this->scheme = $scheme;
        return $this;
    }

    public function getScheme(): ?string {
        return $this->scheme;
    }

    public function setHost(string $host = null): URL {
        $this->host = $host;
        return $this;
    }

    public function getHost(): ?string {
        return $this->host;
    }

    public function setPath(string $path = null): URL {
        $this->path = $path;
        return $this;
    }

    /**
     * Add part(s) to the current path.
     */
    public function addPath(string $path): URL {
        if (!$this->path) {
            return $this->setPath($path);
        }
        return $this->setPath(static::addTrailingSlash($this->path) . static::removeLeadingSlash($path));
    }

    public function getPath(): ?string {
        return $this->path;
    }

    /**
     * Set query parameters.
     */
    public function setParams(array $params): URL {
        $this->params = $params;
        return $this;
    }

    /**
     * Add/set query parameter.
     */
    public function setParam(string $key, $value): URL {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Remove a query parameter.
     */
    public function removeParam(string $key): URL {
        unset($this->params[$key]);
        return $this;
    }

    /**
     * Get query parameters.
     */
    public function getParams(): array {
        return $this->params;
    }

    public function getQuery(): ?string {
        if (!$this->params) {
            return null;
        }

        return http_build_query($this->params);
    }

    public function setFragment(string $fragment = null): URL {
        $this->fragment = $fragment;
        return $this;
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

        if ($this->addTrailingSlash && ($this->path || $this->params || $this->fragment)) {
            $string = static::addTrailingSlash($string);
        }

        if ($this->params) {
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
