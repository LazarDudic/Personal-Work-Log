<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## About Personal Work Log

Personal Work Log is web app created in Laravel to easily keep track of working hours and earnings.

My goal with this app is challenging myself to improve knowledge of Laravel and to have some helpful
app which can count my hours of work or study.

Installation: 

- Clone project from Git.
```bash
git clone https://github.com/Lazar90/Personal-Work-Log
```

- Move terminal to project location.
```bash
cd Personal-Work-Log
```
- Create and Set up your .env file by copying .env.example file.
    * Fill in a database information.
    
- Install dependencies.
```bash
composer install
```

-  Generate the application key.
```bash
php artisan key:generate
``````

- Run migration.
```bash
php artisan migrate
``````

- Run local development server.
```bash
php artisan serve
``````
