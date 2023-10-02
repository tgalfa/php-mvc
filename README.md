# My PHP Application

This repository contains a PHP application built with Docker Compose, which includes Nginx, MySQL, and various scripts for development and testing.

## Getting Started

Follow these steps to set up and run the application:

1. Clone this repository to your local machine:
```bash
git clone https://github.com/tgalfa/mvc-php.git
```

2. Navigate to the project directory:
```bash
cd mvc-php
```

3. Create a .env file based on the provided .env.example:
```bash
cp .env.example .env
```
Update the .env file with your preferred configuration.
Make sure to set the database configuration, BASE_URL, and APP_NAME as needed:

4. Install Composer dependencies:
```bash
docker exec -it mvc-php composer install
```

5. Run database migrations with seeding:
```bash
docker exec -it mvc-php composer migrate:seed
```

The application should now be accessible at http://localhost.

## Application Features

- The app includes a login form.
- And Users can create news articles after logging in.

## Running Tests

To run tests for the application, follow these steps:

1. Create a test database, for example:
```mysql
CREATE DATABASE IF NOT EXISTS `login_mvc_test`;
```

2. Run PHPUnit tests using the following command:
```bash
docker exec -it mvc-php ./vendor/bin/phpunit
```

## Extra Scripts

Here are some extra scripts available in this project:

```bash
# Code Style Checking (Dry Run): Check for coding style violations without making changes.
composer style:check

# Code Style Fixing: Automatically fix coding style violations.
composer style:fix

# PSR-12 Code Sniffer Check: Check code for PSR-12 compliance.
composer sniff:check

# PSR-12 Code Sniffer Fix: Automatically fix code style violations based on PSR-12.
composer sniff:fix

# Combined Code Fix: Run both code style fixing and PSR-12 compliance fixing.
composer code:fix

# PHP Mess Detector (PHPMD): Analyze your code for potential issues and violations based on the ruleset defined in phpmd-ruleset.xml.
composer phpmd

# Database Migrations: Apply database migrations.
composer migrate

# Rollback Database Migrations: Rollback database migrations
composer migrate:rollback

# Database Migrations with Seeding: Apply database migrations and seed the database.
composer migrate:seed
```