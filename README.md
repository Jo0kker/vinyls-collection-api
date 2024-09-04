<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
<a href="https://forge.laravel.com"><img src="https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F7aba6bb0-58c9-4176-9edd-2ef9b36946a5%3Fdate%3D1%26commit%3D1&style=plastic" alt="License"></a>
</p>

## Install php dependencies
You can use sail image to install php dependencies. 
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

## Installation
Use docker with sail to run the project. 
```bash
./vendor/bin/sail up
```

## Setup host
Add the following line to your hosts file.
```bash
127.0.0.1   minio
```

## Setup minio
Access minio at http://minio:9000 and create a bucket named `local`.
(username: `sail`, password: `password`)

Setup access to public

