## Prerequisites.

To use this project you should have `PHP 8.3` installed on your system.

## Setup in your system.

- Clone the repository into your local system.

- Copy the `.env.example` file and paste it in the same directory and rename it as `.env`.

- And create a database if your site uses database and set the following env variables:

    `DB_ENGINE` - databese engine you are using.

    `DB_HOST` - IP address where the databse hosted.

    `DB_PORT` - Port to connect the database server.

    `DB_NAME` - Name of the database you are using.

    `DB_USER` - Username of the database you are using.

    `DB_PASSWORD` - Password of the databse.

- Then run the following commands:

    ```bash
    composer install
    ```

    This will install the default dependencies of the project.

    And after that you have to set the secret key using the below command

    ```bash
    php artisan key:generate
    ```

After that run the following commands to setup tables

```bash
php artisan migrate
```

And then run the server using

```bash
php artisan serve
```
