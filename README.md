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

$borrower = new stdClass;
$borrower->firstName = "John";
$borrower->middleInitial = "G";
$borrower->lastName = "Doe";
$borrower->suffix = "Jr.";
$borrower->ssn = "123456789";
$borrower->homePhone = "5555551234";
$borrower->dob = "07/21/1997";

$location = new stdClass;
$location->streetAddress = "123 Atlantic Avenue";
$location->city = "Atlantic City";
$location->state = 'NJ');
$location->zip = 12345;

$borrower->residences = array($location);

$co_borrower = new stdClass;
$co_borrower->firstName = "John";
$co_borrower->middleInitial = "G";
$co_borrower->lastName = "Doe";
$co_borrower->suffix = "Jr.";
$co_borrower->ssn = "123456789";
$co_borrower->homePhone = "5555551234";
$co_borrower->dob = "07/21/1997";

$co_location = new stdClass;
$co_location->streetAddress = "123 Atlantic Avenue";
$co_location->city = "Atlantic City";
$co_location->state = 'NJ';
$co_location->zip = 12345;

$co_borrower->residences = array($borrower,$coborrower);

$borrowers = array($applicant1);

$requester = new stdClass;
$requester->name = "Some Mortgage Co";
$requester->streetAddress = "123 Some Lane, Suite 100";
$requester->city = "Daytona Beach";
$requester->state = "FL";
$requester->zip = "32124";
$submitter = new stdClass;
$requester->name = "Some Dev Co";
$requester->streetAddress = "123 Some Lane, Suite 200";
$requester->city = "Daytona Beach";
$requester->state = "FL";
$requester->zip = "32124";
$report = $client->getCreditReport($loanNumber,$loanOfficer,$borrowers,$requester,$submitter);

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
