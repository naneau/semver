# A decent SemVer library

[![Build Status](https://travis-ci.org/naneau/semver.png?branch=master)](https://travis-ci.org/naneau/semver)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fa3d9982-f1f9-4637-84f2-c3ac460676c7/mini.png)](https://insight.sensiolabs.com/projects/fa3d9982-f1f9-4637-84f2-c3ac460676c7)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/naneau/semver/badges/quality-score.png?s=0d9f184ab5456eab3c9222fd2f53e1defd43d117)](https://scrutinizer-ci.com/g/naneau/semver/)
[![Code Coverage](https://scrutinizer-ci.com/g/naneau/semver/badges/coverage.png?s=d292f21ffe198272dfc0d96f78750704644d6473)](https://scrutinizer-ci.com/g/naneau/semver/)

This library is a [SemVer](http://semver.org) parser written in PHP. It has a
solid, fully tested OO API that makes it easy to work with SemVer versions.

This library was born out of a lack of decent tools to read and manipulate
SemVer versions in PHP. The libraries currently out there are either not fully
standards compatible, and/or lack a decent API.

## Usage (PHP)

### Parsing and Properties

Parsing strings into SemVer Versions is easy:

```php
<?php

use Naneau\SemVer\Parser;

// Parse a SemVer string
$version = Parser::parse('1.2.3-alpha.1+build.12345.ea4f51');

// Root parts
echo $version->getMajor(); // => 1
echo $version->getMinor(); // => 2
echo $version->getPatch(); // => 3

// Pre-release part ('-alpha.1')
if ($version->hasPreRelease()) {
    echo $version->getPreRelease()->getGreek(); // => alpha
    echo $version->getPreRelease()->getReleaseNumber(); // => 1
    echo $version->getPreRelease() // => alpha.1
}

// Build part ('+build.12345')
if ($version->hasBuild()) {
    echo $version->getBuild()->getNumber(); // => 12345
    var_dump($version->getBuild()->getParts()); // => array(0 => 'ea4f51');
}

// Full version echo
echo $version; // => 1.2.3-alpha.1+build.12345.ea4f51
```

### Comparison

Comparing two versions is easy:

```php
<?php

use Naneau\SemVer\Parser;
use Naneau\SemVer\Compare;

Compare::greaterThan(
    Parser::parse('1.2.1-beta'),
    Parser::parse('1.2.1-alpha.1')
); // ==> true

Compare::equals(
    Parser::parse('1.2.1-beta'),
    Parser::parse('1.2.1-alpha.1')
); // ==> false

Compare::smallerThan(
    Parser::parse('1.2.0'),
    Parser::parse('1.2.1')
); // ==> true
```

### Sorting

There is a built in sorting method, that takes an arbitrary number of arguments
(either strings or Version instances) and returns an array, sorted in
descending order of SemVer:

```php
<?php

use Naneau\SemVer\Sort;

$sorted = Sort::sort('1.2.1-beta', '1.2.0+build.10', '0.9.29');

echo $sorted[0]; // => 0.9.29
echo $sorted[1]; // => 1.2.0+build.10
echo $sorted[2]; // => 1.2.1-beta
```
