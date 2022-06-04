## Installation

Clone application using

> git clone https://github.com/faridsh69/cms2.git

create .env file, fix APP_URL and DB_DATABASE

> cp .env.example .env

Install dependencies

> composer install

Produce a key for laravel

> php artisan key:generate

Create a link to your storage file from public folder

> php artisan storage:link

Run migrations, also it will fully seed fake data

> php artisan migrate:fresh --seed

When you changed your models do not create migration file, or factory, just run: 

> php artisan cms:migration

### Admin panel:

http://cms.test/auth/login
username: farid.sh69@gmail.com
password: 123456

### Using file thumbnails:

```php
$generalSettings->avatar('logo')
$user->avatar('profile_picture')
$user->avatar('cover_photo')
```
### Clean the code

> vendor/bin/ecs check app --fix
> vendor/bin/ecs check config --fix
> vendor/bin/ecs check database --fix
> vendor/bin/ecs check routes --fix
> vendor/bin/ecs check tests --fix

### Run tests

> vendor/bin/phpunit

### For using passport

> php artisan passport:install

To get Client ID, and Client Secret use this:

> php artisan passport:client --password
