VidVerify API client
================

- [![Build Status](https://travis-ci.org/webmaxllc/vidverify-client.svg?branch=master)](https://travis-ci.org/webmaxllc/vidverify-client) master
- [![Build Status](https://travis-ci.org/webmaxllc/vidverify-client.svg?branch=1.0)](https://travis-ci.org/webmaxllc/vidverify-client) 1.0

The VidVerify API client is a PHP library which provides simplified access to
the [VidVerify API](http://vidverify.com) and the data it returns.

> This plugin is currently in development with no stable release. Stay tuned for
> an actual release soon.

Requirements
------------

- PHP 5.6 and above
- See the `require` section of [composer.json](composer.json)

Documentation
-------------

For information on how to utilize the various components provided by this
library please read [the documentation](docs/index.md). Full API documentation
can also be found in [the docs/api](docs/api/index.html) folder.

Installation
------------

### Include the library

Open a command console, enter your project directory and execute the following
command to download the latest stable release:

```bash
$ composer require webmax/vidverify-client "~1.0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Quick Start
-----------

```php
$loader = require_once 'vendor/autoload.php';
$loader->register(true);

$apiKey = '<api-key>';
$endpoint = '<endpoint-subdomain>';

$client = new VidVerifyClient($endpoint, $apiKey, [], null, true);

$from = new \DateTime('2015-01-01 12:00');
$to = new \DateTime('2016-07-12 12:00');
$activities = $client->getAllActivities($from, $to);
```

Contributing
------------

If there is anything you believe to be missing or an error, please send a pull
request with your changes. I'd really appreciate the help! Please make sure to
include working unit tests, and that any changes you make have been tested and
do not break current features.

Testing
-------

We hope to remain at 100% unit test coverage for the entire lifespan of this
project; lofty goal, for sure, but doable. To run the test suite first install
the library then run the following command from the root folder of the project:

```bash
$ ./vendor/bin/phpunit
```

Or, if you already have phpunit installed globally this will do:

```bash
$ phpunit
```

Thanks
------

- [VidVerify](http://vidverify.com) - API creator
- [GuzzleHTTP](http://docs.guzzlephp.org) - Base client library
- [JMS Serializer](http://jmsyst.com/libs/serializer) - JSON deserializer
- [PHPUnit](https://phpunit.de/) - PHP testing framework
