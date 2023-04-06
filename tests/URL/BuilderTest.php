<?php

namespace JPI\Utils\Tests\URL;

use JPI\Utils\URL;
use PHPUnit\Framework\TestCase;

final class BuilderTest extends TestCase {

    public function testAll(): void {
        $url = new URL("https://jahidulpabelislam.com");

        // Basic
        $this->assertSame("https://jahidulpabelislam.com", (string)$url);

        // Add extra path
        $url->addPath("/projects");
        $this->assertSame("https://jahidulpabelislam.com/projects/", (string)$url);

        // Swapping scheme
        $url->setScheme("http");
        $this->assertSame("http://jahidulpabelislam.com/projects/", (string)$url);

        // Changing scheme
        $url->setHost("staging.jahidulpabelislam.com");
        $this->assertSame("http://staging.jahidulpabelislam.com/projects/", (string)$url);

        // Changing Path
        $url->setPath("/contact/");
        $this->assertSame("http://staging.jahidulpabelislam.com/contact/", (string)$url);

        // Add query param
        $url->setQueryParam("key1", "value");
        $this->assertSame("http://staging.jahidulpabelislam.com/contact/?key1=value", (string)$url);

        // Replacing query params + encoded query param
        $url->setQueryParams(["key2" => (string)(new URL("https://jahidulpabelislam.com/"))]);
        $this->assertSame("http://staging.jahidulpabelislam.com/contact/?key2=https%3A%2F%2Fjahidulpabelislam.com%2F", (string)$url);

        // A filepath
        $url = new URL("https://jahidulpabelislam.com/");
        $url->addPath("/test.html");
        $this->assertSame("https://jahidulpabelislam.com/test.html", (string)$url);

        // A fragment
        $url->setFragment("projects");
        $this->assertSame("https://jahidulpabelislam.com/test.html#projects", (string)$url);

        // Protocol relative url
        $url = new URL("//jahidulpabelislam.com/");
        $this->assertSame("//jahidulpabelislam.com/", (string)$url);

        // Path
        $url = new URL("/test");
        $this->assertSame("/test/", (string)$url);

        // Subpath
        $url->addPath("/test.html");
        $this->assertSame("/test/test.html", (string)$url);

        // Parsing with everything
        $url = new URL("https://jahidulpabelislam.com/test.html?key1=value1#test");
        $this->assertSame("https://jahidulpabelislam.com/test.html?key1=value1#test", (string)$url);

        // Bad / empty
        $url = new URL("https://");
        $this->assertSame("", (string)$url);
    }
}
