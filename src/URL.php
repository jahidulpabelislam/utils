<?php

declare(strict_types=1);

namespace JPI\Utils;

/**
 * URL builder & helper methods around URLs.
 *
 * @author Jahidul Pabel Islam <me@jahidulpabelislam.com>
 * @copyright 2012-2022 JPI
 */
class URL {

    /**
     * Remove the leading slash from passed path (if there is one).
     *
     * @param string $path
     * @return string
     */
    public static function removeLeadingSlash(string $path): string {
        $path = trim($path, " ");
        return ltrim($path, "/");
    }

    /**
     * Remove the trailing slash from passed URL (if there is one).
     *
     * @param string $url
     * @return string
     */
    public static function removeTrailingSlash(string $url): string {
        $url = trim($url, " ");
        return rtrim($url, "/");
    }

    /**
     * Remove both leading & trailing slashes from passed path (if there is any).
     *
     * @param string $path
     * @return string
     */
    public static function removeSlashes(string $path): string {
        return trim($path, " /");
    }

    /**
     * Add a leading slash to passed path (if there isn't one already).
     *
     * @param string $path
     * @return string
     */
    public static function addLeadingSlash(string $path): string {
        $path = trim($path, " ");
        $path = ltrim($path, "/");
        return "/$path";
    }

    /**
     * Add a trailing slash to passed URL (if there isn't one already).
     *
     * @param string $url
     * @return string
     */
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

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string|null
     */
    protected $fragment;

    /**
     * Whether to add a trailing slash at the end of the path.
     *
     * @var bool
     */
    protected $addTrailingSlash = true;

    /**
     * Parse the components out from passed URL string if passed.
     *
     * @param string|null $url
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

    /**
     * @param string|null $scheme
     * @return URL
     */
    public function setScheme(string $scheme = null): URL {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScheme(): ?string {
        return $this->scheme;
    }

    /**
     * @param string|null $host
     * @return URL
     */
    public function setHost(string $host = null): URL {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string {
        return $this->host;
    }

    /**
     * @param string|null $path
     * @return URL
     */
    public function setPath(string $path = null): URL {
        $this->path = $path;
        return $this;
    }

    /**
     * Add part(s) to the current path.
     *
     * @param string $path
     * @return URL
     */
    public function addPath(string $path): URL {
        if (!$this->path) {
            return $this->setPath($path);
        }
        return $this->setPath(static::addTrailingSlash($this->path) . static::removeLeadingSlash($path));
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string {
        return $this->path;
    }

    /**
     * Set query parameters.
     *
     * @param array $params
     * @return URL
     */
    public function setParams(array $params): URL {
        $this->params = $params;
        return $this;
    }

    /**
     * Add/set query parameter.
     *
     * @param string $key
     * @param string|array $value
     * @return URL
     */
    public function setParam(string $key, $value): URL {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Remove a query parameter.
     *
     * @param string $key
     * @return URL
     */
    public function removeParam(string $key): URL {
        unset($this->params[$key]);
        return $this;
    }

    /**
     * Get query parameters.
     *
     * @return array
     */
    public function getParams(): array {
        return $this->params;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string {
        if (!$this->params) {
            return null;
        }

        return http_build_query($this->params);
    }

    /**
     * @param string|null $fragment
     * @return URL
     */
    public function setFragment(string $fragment = null): URL {
        $this->fragment = $fragment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFragment(): ?string {
        return $this->fragment;
    }

    /**
     * Build the URL from current values of each component.
     *
     * @return string
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

    /**
     * @return string
     * @author Jahidul Islam <jahidul@d3r.com>
     */
    public function __toString(): string {
        return $this->build();
    }
}
