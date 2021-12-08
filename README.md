clipr Test - REST API for Blog
==============================

# Requirements

- PHP 7.4 with pdo_sqlite extension enabled
- composer

# Installation

Open a console in project directory and run :

## Dependencies installation

```
composer install
```

## Database creation, schema update and fixtures loading

```
composer prepare-db
```

## Run internal web server

```
composer run-server
```

You can now browse api doc at:
<http://127.0.0.1:8000/api/doc>

## Dev tools

```
composer linter
composer tests
```

# Project feedback

## Time spent

roughly 20-24 hours

### Time consumming tasks

- hateoas
- Results pagination
- Swagger configuration
- Tests

## Design decisions

- docker is not required so we use symfony internal server (version 4.4 because it has been discontinued since then)
- posts can be accessed by slug but users and comments are accessed by id
- slug from post are not editable
- user email and password are not editable
- posts and comments are sorted by descending creation date

## TODO list

Thses task should have been done but i didn't have time:
- secure admin
- configure token creation on swagger UI
- Versionning
- Make comments require a validation
- more tests, with authentication

## Tools added for more comfort

- easyadmin administration
- bookmarlet to import Post from Reddit :
```
javascript:(function(){ window.open('http://127.0.0.1:8000/api/posts/import?uri='+encodeURIComponent(location.href)); })();
```
(A browser able to send POST request with custom headers might be handy)

# Project Feedback

## Feature 1: Users and authentication

### Create an authentication route that returns a token
```
POST http://127.0.0.1:8000/api/login/token
```
It should have been api/login, but my first tought was to add an endpoint for admin authentication and i wanted to have different names

### Create a route that retrieve all users accessible
```
GET http://127.0.0.1:8000/api/users/
```

### Create a route that retrieves the current connected user
```
GET http://127.0.0.1:8000/api/profile/
```
The current user is a special kind of ressource and should have his own endpoint

## Feature 2: Manage posts

### Create a blog post
```
POST http://127.0.0.1:8000/api/posts/
```

### Update the blog post
```
PATCH http://127.0.0.1:8000/api/posts/{slug}
```
Usually the id is used to refer a ressource, but here the slug is unique and immutable, so we can use it and it would be easier for user to identify a post

### Delete a blog post
```
DELETE http://127.0.0.1:8000/api/posts/{slug}
```


## Feature 3: Read posts

### Create a route to list all the posts accessibles for the public
```
GET http://127.0.0.1:8000/api/posts/
```

### Create a route to list only the posts of the connected user
```
GET http://127.0.0.1:8000/api/profile/posts/
```
Here we consider the posts as sub ressources from User, a more "RESTFULL" point of view would be to consider posts as stand alone resources, and filter them by the current user.
```
GET http://127.0.0.1:8000/api/posts/
```
(with the current user id as user parameter)

## Feature 4: Comments

### Create a route to comment on a post for connected users or anonymous
Again, we could consider comments as stand alone resource or as a sub resource from a post :
```
POST http://127.0.0.1:8000/api/posts/{slug}/comments
```

### Create a route to see every comment from a post
```
GET http://127.0.0.1:8000/api/comments/
```
(with the post slug as post parameter)


## Feature 5: Create a post from Reddit
```
POST http://127.0.0.1:8000/api/posts/{slug}/import
```










# Project Creation history

## Command used at project iniialization

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
