CBC API client
================

- [![Build Status](https://travis-ci.org/webmaxllc/cbc-client.svg?branch=master)](https://travis-ci.org/webmaxllc/vidverify-client) master
- [![Build Status](https://travis-ci.org/webmaxllc/cbc-client.svg?branch=1.0)](https://travis-ci.org/webmaxllc/vidverify-client) 1.0

The CBC API client is a PHP library which provides simplified access to
the [CBC INNOVIS API](http://cbcinnovis.com) and the data it returns.

> This plugin is currently in development with no stable release. Stay tuned for
> an actual release soon.

Requirements
------------

- PHP 5.5 and above
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
$ composer require webmax/cbc-client "~1.0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Quick Start
-----------

```php
$loader = require_once 'vendor/autoload.php';
$loader->register(true);

$loginId = '<loginId>';
$password = '<password>';
$env = '<endpoint-subdomain>';

$client = new CBCClient($env, $loginId, $password, [], null, true);

$loanNumber = 12345678;
$loanOfficer = "James Johnson";
$applicant = new stdClass;
$applicant->firstName = "John";
$applicant->middleInitial = "G";
$applicant->lastName = "Doe";
$applicant->suffix = "Jr.";
$applicant->homePhone = 5555551234;
$applicant->dob = "7/21/1997";

$house = new stdClass;
$house->streetAddress = "123 Atlantic Avenue";
$house->city = "Atlantic City";
$house->state = 'NJ');
$house->zip = 12345;

$applicant->residences = array($house);

$requestor = new stdClass;
$requestor->name = "Some Mortgage Co";
$requestor->streetAddress = "123 Some Lane, Suite 100";
$requestor->city = "Daytona Beach";
$requestor->state = "FL";
$requestor->zip = "32124";
$submittor = new stdClass;
$requestor->name = "Some Dev Co";
$requestor->streetAddress = "123 Some Lane, Suite 200";
$requestor->city = "Daytona Beach";
$requestor->state = "FL";
$requestor->zip = "32124";
$report = $client->getCreditReport($loanNumber,$loanOfficer,$applicant,$requestor,$submittor);

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

- [CBCInnovis](http://cbcinnovis.com) - API creator
- [GuzzleHTTP](http://docs.guzzlephp.org) - Base client library
- [PHPUnit](https://phpunit.de/) - PHP testing framework
