# phlib/xss-sanitizer

https://github.com/phlib/xss-sanitizer

[![Build Status](https://img.shields.io/travis/phlib/xss-sanitizer/master.svg?style=flat-square)](https://travis-ci.org/phlib/xss-sanitizer)
[![Codecov](https://img.shields.io/codecov/c/github/phlib/xss-sanitizer.svg)](https://codecov.io/gh/phlib/xss-sanitizer)
[![Latest Stable Version](https://img.shields.io/packagist/v/phlib/xss-sanitizer.svg?style=flat-square)](https://packagist.org/packages/phlib/xss-sanitizer)
[![Total Downloads](https://img.shields.io/packagist/dt/phlib/xss-sanitizer.svg?style=flat-square)](https://packagist.org/packages/phlib/xss-sanitizer)
![Licence](https://img.shields.io/github/license/phlib/xss-sanitizer.svg)

PHP XSS sanitizer tool for HTML

## Disclaimer

Use [HTML Purifier](http://htmlpurifier.org/).

This library was created to try to solve the problem of XSS sanitization without using a whitelist, since the HTML which is being sanitized may contain non-standard or unusual syntax (e.g. HTML for emails).

This library is also intended for a limited use case whereby it is assumed that the sanitized HTML is only going to be displayed in a limited set of supported browsers (e.g. no need to strip 'vbscript:' code).

## Install

Via Composer

``` bash
$ composer require phlib/xss-sanitizer
```

## Usage

Create a sanitizer and sanitize some input

``` php
$sanitizer = new \Phlib\XssSanitizer\Sanitizer();
$sanitized = $sanitizer->sanitize($htmlInput);

```

## Supported Browsers

This library is intended to prevent XSS vulnerabilities when the resulting HTML is rendered by any of the following browsers:

* Chrome (40+)
* Firefox (40+)
* Safari (8+)
* IE (10, 11)
* Edge

## License

This package is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
