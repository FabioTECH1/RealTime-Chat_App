<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# RealTime Chat App
## Getting started

Assuming you've already installed on your machine: PHP (>= 7.0.0), [Laravel](https://laravel.com) and [Composer](https://getcomposer.org)

Clone the repository
``` bash
git clone https://github.com/FabioTECH1/RealTime-Chat_App.git
```

Switch to the repo folder
``` bash
cd RealTime-Chat_App
```

Install all the dependencies using composer
``` bash
composer install
```

Create .env file and make the required configuration changes in it, run the database migrations (**Set the database connection in .env before migrating**)
``` bash
php artisan migrate
```

Then launch the server:
``` bash
php artisan serve
```

The Laravel sample project is now up and running! Access it at http://127.0.0.1:8000.
