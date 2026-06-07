# Task Manager

A personal task manager built with pure PHP following MVC pattern.

## Features

- User registration and authentication
- Create, edit, delete tasks
- Task statuses: To Do, In Progress, Done
- Quick status update from task list
- File attachments for tasks
- Deadline tracking
- Access control — users see only their own tasks

## Tech Stack

- PHP 8.2
- MySQL 8.0
- Nginx
- Docker
- Composer
- PHPUnit

## Architecture

- MVC pattern
- PSR-4 autoloading
- Dependency injection container
- Traits for reusable logic
- Unit tests

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository
```bash
git clone https://github.com/pinashi/taskmng.git
cd taskmng
```

2. Copy environment file
```bash
cp src/.env.example src/.env
```

3. Start Docker containers
```bash
docker compose up -d
```

4. Install dependencies
```bash
docker compose exec php-fpm composer install
```

5. Create database tables — open Adminer at `http://localhost:8081` and run SQL from `database.sql`

6. Open `http://localhost:8080`

## Running Tests

```bash
docker compose exec php-fpm sh -c "cd /var/www/html && vendor/bin/phpunit tests/"
```

## Project Structure

```
src/
  Controllers/    — request handlers
  Models/         — database interaction
  Validators/     — input validation
  Traits/         — reusable logic
  Views/          — HTML templates
  config/         — database configuration
  public/         — entry point
  tests/          — unit tests
```