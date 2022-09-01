# Glade CRM Backend

## Introduction

A minimum employee management system api with authenticatiom, authorization and notifications system

Below is a notion link where I planned development

[Development Plan On Notion](https://sprinkle-princess-220.notion.site/Glade-Backend-CRM-25ddce81414e40438cf31814cc4c1e91)

## Table of Contents
1. <a href="#how-it-works">How it works</a>
2. <a href="#technology-stack">Technology Stack</a>
3. <a href="#application-features">Application Features</a>
4. <a href="#api-endpoints">API Endpoints</a>
5. <a href="#setup">Setup</a>
6. <a href="#author">Author</a>
7. <a href="#license">License</a>


## Technology Stack
  - [PHP](https://www.php.net)
  - [Laravel](https://laravel.com)
  - [MySQL](https://www.mysql.com)
  ### Testing tools
  - [PHPUnit](https://phpunit.de) 

## Application Features
* A user can login into the system by providing a valid email and password.
* An authenticated user can logout of the system.
* An admin can create and view a company
* An admin can create an employee and view company emoloyees
* A company owner can view their employees
* An employee can view his company
* A super admin can perform all of the above functionalties
* A super admin can register new user into the system by providing required fields.
* A super admin can delete a user.
* A super admin can delete a company
* A super admin can delete an employee
* Email is sent to a company owner once its company account has been created.

## API Endpoints
### Base URL = http://localhost:8080/api
[Postman Documentation](https://documenter.getpostman.com/view/11352884/VUxPvTDF)

## Setup
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

  #### Dependencies
  - [Laravel Requiremnets] 
 
  #### Getting Started
  - Install composer and all laravel dependencies [Laravel Requirements](https://laravel.com/docs/7.x/installation)
  
  - Open terminal and run the following commands
    ```
    $ git clone https://github.com/harmlessprince/glade-crm.git
    $ cd glade-crm
    $ cp .env.example .env
    $ composer install
    $ php artisan key:generate
    ```
  - Create database and update database details in your .env file
  
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=yourdatabasename
    DB_USERNAME=yourdatabaseusern
    DB_PASSWORD= yourdatabasepassword
    ```
  - Seed Data
  
    You need to seed super admin user into the database, you can do that buy running: 
    
    ```
    php artisan app:setup
    ```
    
    if you will like to seed super admin user, admin user, company, employees data, the run else skip
    
    ```
    php artisan app:setup --seed=y
    ```
  - Setup Mail with either mailtrap or mailhog
  
    ```
    MAIL_MAILER=smtp
    MAIL_HOST=mailhog
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=null
    MAIL_FROM_NAME="${APP_NAME}"
    ```
    
  if Seeding goes well
  ### Super Admin Logins
        email: superadmin@admin.com
        password: password
        
  ### Testing
  
  ```
  $ php artisan test
  ```
  If correctly setup, all tests should pass
  
  
## Author
 Name: Adewuyi Taofeeq <br>
 Email: realolamilekan@gmail.com <br>
 LinkenIn:  <a href="#license">Adewuyi Taofeeq Olamikean</a> <br>

## License
ISC
