### Serverless-PHP
[![serverless][badge-serverless]](http://www.serverless.com)
[![language][badge-language]](http://php.net)
![license][badge-license]


This is an example how to run PHP on AWS Lambda via Serverless Framework. Inspired by the [Andy Raines](araines-serverless-php) repository.

[AWS Lambda][aws-lambda-home] lets you run code without thinking about servers. Unfortunately, it does not support PHP yet.


## Installation

### Prerequisites
* [Serverless](https://serverless.com/)
* [Node](https://nodejs.org)
* [Composer](https://getcomposer.org/)
* [Git LFS](https://git-lfs.github.com/)

### Setup
1. Clone this repository
2. Run `composer install -o --no-dev`
3. Adjust name in serverless.yml

## Usage
1. Add a new route around line 10 handler.php
2. Create an invokable class in src (App namespace)
3. Create a service that matches the route defined in 1. and creates the class from point 2.
4. Execute!

Example invokable class: 
```php
<?php

namespace App;

class SayHello {
    public function __invoke(){
        printf(json_encode([
            'statusCode' => 200,
            'body' => json_encode(['status' => 'Hello']),
        ]));
    }
}

```

## Running
### Locally
```bash
sls invoke local -f hello --log -d '{"httpMethod":"GET", "path": "/hello"}'
```
### AWS
```bash
sls invoke -f hello --log -d '{"httpMethod":"GET", "path": "/hello"}'
```
Or just copy the exposed URL and use it.

[badge-serverless]:   http://public.serverless.com/badges/v3.svg
[badge-language]:     https://img.shields.io/badge/language-php-blue.svg
[badge-license]:      https://img.shields.io/badge/license-MIT-orange.svg
[araines-serverless-php]: https://github.com/araines/serverless-php
[aws-lambda-home]:  https://aws.amazon.com/lambda/
[aws-lambda-langs]: http://docs.aws.amazon.com/lambda/latest/dg/lambda-app.html#lambda-app-author