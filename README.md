## PHP library for Plesk XML-RPC API

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plesk/api-php-lib/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plesk/api-php-lib/?branch=master)

PHP object-oriented library for Plesk XML-RPC API.

## Install Via Composer

[Composer](https://getcomposer.org/) is a preferable way to install the library:

`composer require plesk/api-php-lib:@dev`

## Examples

To get started you can find examples in the 'examples' folder or open [index.html](index.html)

## How to Run Unit Tests

One the possible ways to become familiar with the library is to check the unit tests.

To run the unit tests use the following command:

`REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password ./vendor/bin/phpunit`

To use custom port one can provide a URL (e.g. for Docker container):

`REMOTE_URL=https://your-plesk-host.dom:port REMOTE_PASSWORD=password ./vendor/bin/phpunit`

## Using Grunt for Continuous Testing

* Install Node.js
* Install dependencies via `npm install` command
* Run `REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password grunt watch:test`

