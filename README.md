# Laravel Project with MySQL, Nginx

This project provides a complete setup for developing a Laravel web application using Docker containers. It includes configurations for MySQL, Nginx. Docker Compose is used to orchestrate these containers.

## Prerequisites

Before you begin, ensure you have the following installed:

- Docker: https://www.docker.com/get-started
- Docker Compose: https://docs.docker.com/compose/install/

## Getting Started

1. Clone this repository to your local machine:

   ```bash
   git clone https://github.com/yourusername/project-name
   ```

2. Navigate to the project directory:
   ```bash
   cd project-name
   ```
3. Copy the .env.example file and rename it to .env:
   ```bash
   cp .env.example .env
   ```
4. Customize the .env file according to your preferences.

## Usage

To start the application, run the following command:

    docker-compose up -d

To stop the application, run:

    docker-compose down

## Folder Structure

    project-name/
    │
    ├── .docker/
    │   ├── mysql/
    │   ├── config/
    │   │   └── app.conf
    │   ├── nginx_log/
    │   │   └──
    │   └── Dockerfile
    │    │
    ├── src/
    │   ├── ... # Laravel application files
    │
    └── docker-compose.yaml

- `docker/`: This directory contains all the containers related configuration such as PHP, Nginx, MySQL and Docerfile
  - `mysql/`: Contains MySQL configuration files.
  - `config/`: Contains Nginx configuration files.
  - `php/`: Contains PHP configuration files.
  - `Dockerfile`: Specifies how to build your custom Docker image
- `src/`: This directory contains your Laravel application files.
- `docker-compose.yaml`: Defines your Docker services and their configurations.

Make sure to organize your project's files and directories according to this structure.

## Environment Configuration

Before running the Laravel application, make sure you configure the `.src/env` file to match the Docker Compose services' settings. Here's how you can set up the important environment variables:

#### MySQL Configuration

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql_db
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

- DB_HOST should match the name of the MySQL service defined in Docker Compose (mysql_db).
- DB_PORT should match the MySQL port configured in Docker Compose (3306).

#### Note:

If you make changes to the .env file, you may need to rebuild the application container using docker-compose up -d --build app to apply the new environment settings.

## Service

This project includes the following services:

- Laravel Application (php)
- MySQL Database (mysql_db)
- Nginx Web Server (nginx)

## Accessing Services

- Laravel Application: http://localhost:80
- MySQL Database:
  - Host: localhost
  - Port: 3306
  - Username:
  - Password:
  - Database:
- Nginx Web Server: http://localhost:80

## Managing Databases

You can use phpMyAdmin to manage the MySQL database:

- phpMyAdmin: http://localhost:8080
  - Server: 'mysql_db'
  - Username: 'root'
  - Password: 'root'

## Customization

- Adjust the configurations in the .src/.env file to suit your project needs.
- Modify the Dockerfiles, Nginx configurations, and Laravel application as needed.
