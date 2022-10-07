# Simple E-commerce application
This is simple E-commerce, built on PHP, Laravel, and node.js.

## Installation
You need PHP, composer, Laravel, and node.js to be installed on your OS.
Project is already configured for SQLite. 
Download, or clone with git.
```bash
git clone https://github.com/aldis-sarja/e-commerceV1.git
cd e-commerceV1
npm install
composer install
npm run dev
```

If you plan to use , say, MySQL, configure your SQL server in `.env` file:
```bash
DB_CONNECTION=<your-db-server>
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<shortened-URLs-db-name>
DB_USERNAME=<your-db-user-name>
DB_PASSWORD=<your-password>
```

Now create database tables of project:
```bash
php artisan migrate
```

Project have some sample records for database.
To instantiate these, run command:
```bash
php artisan db:seed
```

## Usage
In the terminal run:
```bash
php artisan serve
```
