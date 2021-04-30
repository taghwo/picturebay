
## About project

A photography e-commerce where clients can request photographers to come and take pictures of their products.

### break down
1. Photographers can get assigned to a request

2. Photographer uploads low quality images for request owner to preview

3. Request owner can then download approved pictures anytime

## Authentication

Authentication is handled with Laravel Sanctum, use Token received during login as Bearer to for subsequent requests

## How to set up

```sh
## Minimum Server Requiremnt "php": 7.3,
1. Clone repository
2. CD into project and run `composer install` to install dependencies
3. Create .env file in project root and paste .env.example content into .env
4. Add Database credentials to .env
5, Run the following commands
    `php artisan key:generate`
    `php artisan migrate`
    `php artisan db:seed`
    `php artisan storage:link`
    'php artisan serve`
```

```sh
## Available endpoints
     check exported postman collection in the root folder of project
```
6. Test API with postman (URL should be structured like `localhost:port/api/v1/{endpoint}`)

7. Execute test suite with `php artisan test`