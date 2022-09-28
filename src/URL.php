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

    protected $scheme;
    protected $host;
    protected $path;
    protected $params = [];
    protected $fragment;

    protected $addTrailingSlash = true;

    public function __construct(string $url = null) {
        if (!$url) {
            return;
        }

        $isProtocolRelative = strpos($url, '//') === 0;

        if ($isProtocolRelative) {
            $url = "https:$url";
        }

        $parsed = parse_url($url);

        if (!$isProtocolRelative) {
            $this->setScheme($parsed["scheme"] ?? null);
        }

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

    public function setHost(string $host = null): URL {
        $this->host = $host;
        return $this;
    }

    public function setPath(string $path = null): URL {
        if ($path === '/') {
            $path = null;
        }

        $this->path = $path;
        return $this;
    }

    public function addPath(string $path): URL {
        if (!$this->path) {
            return $this->setPath($path);
        }
        return $this->setPath(static::addTrailingSlash($this->path) . $path);
    }

    public function setParams(array $params): URL {
        $this->params = $params;
        return $this;
    }

    public function setFragment(string $fragment = null): URL {
        $this->fragment = $fragment;
        return $this;
    }

    public function build(): string {
        $string = "";

        if ($this->scheme) {
            $string = "$this->scheme:";
        }

        if ($this->host) {
            $string .= "//$this->host";
        }

        if ($this->path) {
            $string .= static::addLeadingSlash($this->path);

            if ($this->addTrailingSlash) {
                $string = static::addTrailingSlash($string);
            }
        }

        if ($this->params) {
            if ($this->path) {
                $string = static::addTrailingSlash($string);
            } else {
                $string .= "/";
            }
            $string .= "?" . http_build_query($this->params);
        }
        else if (!$this->scheme && !$this->host && !$this->path) {
            $string = "/";
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
