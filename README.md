# LARAVEL Rest-API-Authentication

Rest api auth using Laravel Passport that support oAuth2

## Installation

Install Rest-API-Authentication

> 1.  First Clone this project
> 2.  open CMD or Bash command and move into directory project
> 3.  install laravel using composer

```bash
  composer install
```

> 4.  create .env file and paste code below

```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:u5XTau6aZmsTCFA8Y0ZbrbZDs0+cIxTj16O+zQw/Uy8=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_restapiauthentication
DB_USERNAME=laravel
DB_PASSWORD=laravel

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

> 5.  Enable extension sodium in your PHP config (php.ini)

```bash
  extension=sodium
```

> 6.  Run command bellow step by step

```bash
  composer require laravel/passport
  php artisan key:generate
  php artisan install:api --passport
  php artisan passport:keys

```

> 7. If step above error, try run this

```bash
  php artisan migrate
  php artisan passport:client --personal
```

> 7.  Run this project

```bash
  php artisan serve
```

## API Reference

#### Register User

```http
  POST /api/user/register
```

| Parameter               | Type     | Description                          |
| :---------------------- | :------- | :----------------------------------- |
| `name`                  | `string` | **Required**, **max:255**            |
| `email`                 | `string` | **Required**, **email**, **max:255** |
| `password`              | `string` | **Required**, **min:6**              |
| `password_confirmation` | `string` | **Required**, **min:6**              |

#### Login User

```http
  POST /api/user/login
```

| Parameter  | Type     | Description               |
| :--------- | :------- | :------------------------ |
| `email`    | `string` | **Required**. **max:255** |
| `password` | `string` | **Required**, **min:6**   |

#### Login User

```http
  POST /api/user/logout
```

| Parameter  | Type     | Description               |
| :--------- | :------- | :------------------------ |
| `email`    | `string` | **Required**. **max:255** |
| `password` | `string` | **Required**, **min:6**   |
