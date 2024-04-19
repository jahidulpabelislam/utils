# Utilities

[![CodeFactor](https://www.codefactor.io/repository/github/jahidulpabelislam/utils/badge)](https://www.codefactor.io/repository/github/jahidulpabelislam/utils)
[![Latest Stable Version](https://poser.pugx.org/jpi/utils/v/stable)](https://packagist.org/packages/jpi/utils)
[![Total Downloads](https://poser.pugx.org/jpi/utils/downloads)](https://packagist.org/packages/jpi/utils)
[![Latest Unstable Version](https://poser.pugx.org/jpi/utils/v/unstable)](https://packagist.org/packages/jpi/utils)
[![License](https://poser.pugx.org/jpi/utils/license)](https://packagist.org/packages/jpi/utils)
![GitHub last commit (branch)](https://img.shields.io/github/last-commit/jahidulpabelislam/utils/0.x.svg?label=last%20activity)

A tiny library that provides very simple utility classes. This is currently in development, so there will small `beta` releases as classes get added then the full major release will be published when officially ready / stable.

This provides a simple singleton trait to go on classes, URL builder class and a few different classes / traits around collections.

## Dependencies

- PHP 8.0+
- Composer

## Installation

Use [Composer](https://getcomposer.org/)

```bash
$ composer require jpi/utils 
```

## Usage

### Singleton

Simply add `\JPI\Utils\Singleton` to any class, this will make the constructor protected so the class can't be instantiated outside the singleton getter, also provide a `get` method which handles the class being singleton.

### URL

`\JPI\Utils\URL` provides 5 static helper methods (which should be self-explanatory):

- `removeLeadingSlash(string): string`
- `removeTrailingSlash(string): string`
- `removeSlashes(string): string`
- `addLeadingSlash(string): string`
- `addTrailingSlash(string): string`

`\JPI\Utils\URL` as a class instance provides building a URL, the class has a single optional string argument, which you can pass if you know what the starting URL should be. You then have the following methods to build on top of this starting URL:

- `setScheme(string|null)`
- `setHost(string|null)`
- `setPath(string|null)`
- `addPath(string)`
- `setQueryParams(array)`
- `setQueryParam(string, string|array)`
- `removeQueryParam(string)`
- `setFragment(string|null)`

They all come with equivalent getter methods:

- `getScheme: string|null`
- `getHost: string|null`
- `getPath: string|null`
- `getQueryParams: array`
- `getFragment: string|null`

Also, a `getQuery: string|null` method which transform the query params to an encoded query string to be used in a URL (minus the `?`).

Lastly the class implements `\Stringable` so you can cast the instance to a string or can manually call `build` method to get the URL as a string. 

### Collections

Here we have `\JPI\Utils\Collection` and `\JPI\Utils\Collection\Paginated`. 

A collection works like a normal array as it implements `\ArrayAccess`, `\Countable` & `\IteratorAggregate` just with some extra methods (all should be self-explanatory), both classes have the below:

- `isset(string|int $key)`
- `get(string|int $key, $default = null)`
- `getCount()`
- `each(callable)`
- `pluck(string $toPluck, string $keyedBy = null): CollectionInterface`
- `groupBy(string $groupByKey): CollectionInterface`

The `Collection` class also has the following methods (`Paginated` doesn't have as its immutable

- `add(mixed $item)`
- `set(string|int $key, mixed $item)`
- `unset(string|int $key)`
- `clear`

A `Paginated` collection extends the `Collection` and adds the following methods:

- `getTotalCount()`
- `getLimit()` get what limit was applied when getting result
- `getPage()` get what page number was applied when getting result

We also have interfaces in case you want to create your own versions, `\JPI\Utils\CollectionInterface`, `\JPI\Utils\Collection\ImmutableInterface` & `\JPI\Utils\Collection\PaginatedInterface` (which extends `ImmutableInterface`).
If you do create your own collection classes, and want to make an immutable or paginated version see `\JPI\Utils\Collection\ImmutableTrait` and `\JPI\Utils\Collection\PaginatedTrait`.

## Support

If you found this library interesting or useful please do spread the word of this library: share on your social's, star on GitHub, etc.

If you find any issues or have any feature requests, you can open an [issue](https://github.com/jahidulpabelislam/utils/issues) or can email [me @ jahidulpabelislam.com](mailto:me@jahidulpabelislam.com) :smirk:.

## Authors

- [Jahidul Pabel Islam](https://jahidulpabelislam.com/) [<me@jahidulpabelislam.com>](mailto:me@jahidulpabelislam.com)

## Licence

This module is licenced under the General Public Licence - see the [Licence](LICENSE.md) file for details
