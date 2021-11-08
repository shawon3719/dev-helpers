## Have to add this to `composer.json`

```
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/",
            "Modules\\": "Modules/"
        }
    },
```

## Documentation 
https://github.com/nWidart/laravel-modules

## Basic Commands 
`php artisan module:make-migration`
`php artisan module:seed`
`php artisan module:make-model UserModel -m`
`php artisan module:make-controller UserController`