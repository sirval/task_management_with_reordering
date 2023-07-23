# Task Management Application
This repository contains a Laravel project that you can clone and install on your local machine. Built with Laravel Framework `8.83.5` and PHP `8.1.2`

## Prerequisites

Before getting started, ensure that you have the following softwares installed on your system:

- PHP (>= 8.0)
- Composer
- MySQL
- Git

## Clone the Repository

1. Open your terminal or command prompt.

2. Change to the directory where you want to clone the project.

3. Run the following command to clone the repository: `git clone  https://github.com/sirval/task_management_with_reordering.git`

## Installation

Follow these steps to set up and install the Laravel project:

1. Change into the project directory: `cd project_directory`

2. Install the project dependencies using Composer: `composer install`

3. Create a copy of the `.env.example` file and rename it to `.env`:

4. Generate an application key: `php artisan key:generate`

5. Configure the app main database connection and test database connection by updating the `.env` file with your database credentials.

6. Run database migrations to create the required tables: `php artisan migrate`

.7 Seed the database with initial sample data: `php artisan db:seed` This will generate Projects, and Tasks.
## Serving the project

- To start the server, run `php artisan serve`

- To maintain the project, run `php artisan down`

- To bring back project, run `php artisan up`

## Running Test

To run the PHPUnit test provided in this app, check `phpunit.xml` in the root directory of your project to confirm this code snippet is not commented or you can change to suit your need.

`<server name="DB_CONNECTION" value="test"/>`
`<server name="DB_DATABASE" value=":task_manager_test:"/>`

and make sure all are configured in the `config/database.php` in the connections array,

1. If all are set, run `php artisan test` 
2. if all are set, you will see about 10 passed test.

- For more Laravel commands and options, refer to the Laravel documentation.
- For inquiry, shoot me an email via `ohukaiv@gmail.com`.

