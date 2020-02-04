<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Coding Challenge Installation

Clone repository : 
```git clone https://github.com/bilalatli/coding-challenge-v1.git```

Open project directory : ```cd coding-challenge-v1/```

Create cache folders : 
> ```mkdir -p storage/framework/sessions```

> ```mkdir -p storage/framework/views```

> ```mkdir -p storage/framework/testing```

> ```mkdir -p storage/framework/cache```

Rename environment file : ```mv environment_file .env```

Give permissions on cache folder : ```chmod -R 755 storage```

Check environment variables in ```.env``` file

Install composer dependencies : ```composer install```

Migrate Database : ```php artisan migrate```

Check tests : ```vendor/bin/phpunit```
> Test files, directories defined in {project_path}/phpunit.xml file

*Congratulations ! Installation completed.* 

Add Laravel schedule in to cron : ```* * * * * php artisan schedule:run```

## Application Info

**Scheduled Tasks :** ```Defined in app/Console/Kernel::schedule()```

**Manual Trigger :** ```php artisan category:migrate```

**Queue Connection :** Queue connection defined default ```sync```. Optionally, it can be replaced with any queue driver
