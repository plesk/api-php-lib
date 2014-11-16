## Intro

PHP object-oriented library for Plesk API-RPC.

## How to Run Unit Tests

One the possible ways to become familiar with the library is to check the unit tests.

To run the unit tests use the following command:

`cd tests ; REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password phpunit`

## Using Grunt for Continuous Testing

* Install Node.js
* Install dependencies via `npm install` command
* Run `cd tests ; REMOTE_HOST=your-plesk-host.dom REMOTE_PASSWORD=password grunt watch:test`

