clipr Test - REST API for Blog
==============================

# Requirements
- PHP 7.4 with pdo_sqlite extension enabled
- composer 

# Installation

Open a console in project directory and run :

## Dependencies installation:
```
composer install
```

## Database creation, sschema update and fixtures loading:
```
composer prepare-db
```

## Run internal web server
```
composer run-server
```
You can now browse api doc at:
http://127.0.0.1:8000/api/doc

## Dev tools
```
composer linter
composer tests
```


# Project feedback

## Time spent
roughly 20-24 hours

### Time consumming task:
- hateoas
- Results pagination
- Swagger configuration
- Tests

## Design decisions
- docker is not required so we use symfony internal server (version 4.4 because it has been discontinued since then)
- posts can be accessed by slug but users and comments are accessed by id
- slug from post are not editable

## TODO list :
Thses task should have been done but i didn't have time:
- configure token creation on swagger UI
- Versionning
- Make comments require a validation
- more tests

## Tools added for more comfort:
- easyadmin administration
- bookmarlet to import Post from Reddit : 
javascript:(function(){ window.open('http://127.0.0.1:8000/api/posts/import?uri='+encodeURIComponent(location.href)); })();
(A browser able to send POST request with custom headers might be handy)

# Project Creation history

## Command used at project iniialization : 

### Base project
```
composer create-project symfony/skeleton:"^5.3" ./
composer require jms/serializer-bundle
composer require friendsofsymfony/rest-bundle
composer require willdurand/hateoas
composer require willdurand/hateoas-bundle
composer require nelmio/api-doc-bundle
composer require symfony/security-bundle
composer require lexik/jwt-authentication-bundle
composer require symfony/http-client
composer require stof/doctrine-extensions-bundle
composer require sensio/framework-extra-bundle
composer require symfony/validator
composer require pagerfanta/pagerfanta
```

### Add admin dashboard for easy database administration
```
composer require easycorp/easyadmin-bundle
```

### DevTools
```
composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer
composer require --dev phpstan/phpstan-symfony
composer require --dev phpunit/phpunit symfony/test-pack
composer require --dev symfony/maker-bundle
composer require --dev symfony/debug-bundle
composer require --dev orm-fixtures
composer require symfony/web-server-bundle 4.4
```
