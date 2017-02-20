VidVerify API client documentation
==============================

Autoloading
-----------

Before using this library, you must ensure that you have registered the composer
autoloader. If you're using a framework like Symfony or Laravel, this has likely
already been done for you. If not, here's how to do that:

```php
// ROOT_DIRECTORY/myscript.php

$loader = require __DIR__.'/vendor/autoload.php';
$loader->register();
```

Debugging
---------

A built in debugger has been coded into this library. This is useful during
development for a few reasons.

1. It forces JMS Serializer to automatically refresh the serialization metadata
   every time it is altered. This saves you from having to do it manually for
   every single code change.

2. It forces Guzzle HTTP into debug mode which will echo the request for you.
   This can help you track down where the API request are headed.

To enable debugging, simply pass `boolean true` as the fifth argument of the
API client constructor:

```php
$client = new VidVerifyClient();
```

Changing the serializer metadata cache directory
------------------------------------------------

For performance reasons, the JMS serializer library caches compiled metadata
about the serialized objects. This makes each request a bit faster and saves you
some CPU cycles and memory.

By default, the serializer will store all metadata in the directory returned by
`sys_get_temp_dir()`. You can change this by passing a valid, writable path as
the fourth argument to the API client constructor.

```php
$client = new VidVerifyClient();
```

Extending the Guzzle HTTP library
---------------------------------

The API client is built on the back of the awesome Guzzle HTTP library. This
allows us developers to stop worrying about Curl, and streams and just access
the API directly. Guzzle, itself, is extremely extensible; but it's too much to
cover here.

Please visit [the Guzzle docs](http://docs.guzzlephp.org/en/latest/index.html)
for more information on possible configuration tweaking.

As far as this library is concerned, you may pass any configuration values for
Guzzle as the third API client constructor parameter:

```php
$client = new VidVerifyClient();
```

You may also access the client directly (forgoing the simple VidVerify wrapper)
and perform more complex queries using Guzzle directly:

```php
$client = new VidVerifyClient();

$client->getGuzzleClient()->requestAsync(...); // Example
```

Endpoints
---------

Write me
