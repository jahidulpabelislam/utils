<?php

namespace JPI\Utilities;

class File {

    private $path;

    private $exists = null;
    private $contents = null;
    private $contentsAsArray = null;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function exists(): bool {
        if ($this->exists === null) {
            $this->exists = file_exists($this->path);
        }

        return $this->exists;
    }

    public function include(): void {
        if ($this->exists()) {
            include_once($this->path);
        }
    }

    public function get($default = null): ?string {
        if ($this->contents === null && $this->exists()) {
            $this->contents = file_get_contents($this->path);
        }

        return $this->contents ?? $default;
    }

    public function getArray(array $default = null): ?array {
        if ($this->contentsAsArray === null) {
            $jsonString = $this->get();

            if ($jsonString) {
                $this->contentsAsArray = json_decode($jsonString, true);
            }
        }

        return $this->contentsAsArray ?? $default;
    }

    public function render(string $default = ""): void {
        echo $this->get($default);
    }
}
