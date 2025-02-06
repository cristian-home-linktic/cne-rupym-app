# CNE Application Project

## Table of Contents
- [Prerequisites](#prerequisites)
- [Installation Methods](#installation-methods)
  - [VSCode DevContainer (Recommended)](#vscode-devcontainer-recommended)
  - [Laravel Sail](#laravel-sail)
  - [Docker](#docker)
  - [Local Installation](#local-installation)

## Prerequisites

Before you begin, ensure you have the following installed based on your preferred installation method:
- Git
- Docker Desktop (for container-based installations)
- VSCode (for DevContainer method)
- PHP 8.x and Composer (for local installation)
- Node.js and NPM (for local installation)

## Installation Methods

### VSCode DevContainer (Recommended)

1. Clone the repository and open it in VSCode
2. Install the [Dev Containers extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)
3. Configure environment:
   ```bash
   # Copy the environment file template
   cp .env.example .env
   # Configure your environment variables in .env
   ```
4. Press `Ctrl+Shift+P` and select `Dev Containers: Rebuild and Reopen in Container`
5. Once the container is ready, open the integrated terminal and run:
   ```bash
   # Install PHP dependencies via Composer
   composer install
   # Install Node.js dependencies
   npm install
   # Compile and minify frontend assets
   npm run build
   # Run database migrations and seed initial data
   php artisan migrate:fresh --seed
   # Restart all supervisor-managed processes
   supervisorctl restart all
   ```

### Laravel Sail

> **Note**: Windows users must have WSL2 installed

1. Configure environment:
   ```bash
   # Copy the environment file template
   cp .env.example .env
   # Configure your environment variables in .env
   ```

2. Build and start containers:
   ```bash
   # Build containers without using cache
   ./sail build --no-cache
   # Start containers in detached mode
   ./sail up -d
   ```

3. Install dependencies and setup application:
   ```bash
   # Install PHP dependencies
   ./sail composer install
   # Install Node.js dependencies
   ./sail npm install
   # Compile and minify frontend assets
   ./sail npm run build
   # Generate application encryption key
   ./sail artisan key:generate
   # Run database migrations and seed initial data
   ./sail artisan migrate:fresh --seed
   # Restart the containers
   ./sail restart
   ```

### Docker

1. Configure environment:
   ```bash
   # Copy the environment file template
   cp .env.example .env
   # Configure your environment variables in .env
   ```

2. Build and start containers:
   ```bash
   # Build containers without using cache
   docker compose build --no-cache
   # Start containers in detached mode
   docker compose up -d
   ```

3. Access container and setup application:
   ```bash
   # Access the Laravel container as the 'sail' user
   docker exec -it -u sail [project-name]-laravel.test-1 /bin/bash
   ```

4. Inside the container, run:
   ```bash
   # Install PHP dependencies
   composer install
   # Install Node.js dependencies
   npm install
   # Compile and minify frontend assets
   npm run build
   # Generate application encryption key
   php artisan key:generate
   # Run database migrations and seed initial data
   php artisan migrate:fresh --seed
   # Restart all supervisor-managed processes
   supervisorctl restart all
   ```

### Local Installation

1. Configure environment:
   ```bash
   # Copy the environment file template
   cp .env.example .env
   # Configure your environment variables in .env
   ```

2. Install dependencies and setup application:
   ```bash
   # Install PHP dependencies
   composer install
   # Install Node.js dependencies
   npm install
   # Compile and minify frontend assets
   npm run build
   # Run database migrations and seed initial data
   php artisan migrate:fresh --seed
   ```

3. Start the development server:
   ```bash
   # Start the Laravel development server
   php artisan serve
   ```

## Additional Notes

- Make sure all required ports are available on your system
- For database connections, ensure your database service is running and configured correctly in `.env`
- Check the application logs if you encounter any issues during installation

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
