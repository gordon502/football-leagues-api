# football-leagues-api

This application is part of a master's thesis entitled: 
**Comparative analysis of relational and document databases with
the use of Doctrine libraries for a football service aggregating the results of the lower leagues of the Polish Football
Association**   
carried out at the Faculty of Computer Science, Poznań University of Technology.

I hope the project will continue to be developed after I get my master's degree, because the idea of aggregating the results
of such leagues came about due to the lack of such a portal for 2024, where the social element (comments on the matches, etc.) 
comes into play. 

As time will allow, we can think about aggregating more countries.

## Description

The application is a REST API that allows you to manage football leagues, teams, matches, game events etc.

### Requirements
- **Docker**
- **Docker Compose**

### Technologies used
| **Type**                   | **Technology**      |
|----------------------------|---------------------|
| **Language**               | **PHP 8.3.2**       |
| **Framework**              | **Symfony 7**       |
| **ORM**                    | **Doctrine ORM**    |
| **ODM**                    | **Doctrine ODM**    |
| **Relational Database**    | **MariaDB 10.11.7** |
| **Document Database**      | **MongoDB 7.0.8**   |
| **Web Server (FPM Proxy)** | **Nginx 1.23.0**    |

## Development

### Environment variables
Copy the `.env` file to `.env.local` and fill/replace the values with your own,
especially the `MONGODB_URL`, `MONGODB_DB` and `DATABASE_URL`.

### Run the app
```bash
sh run-dev-env.sh
```

### Interactive shell with PHP and Composer
By default, executing this `sh run-dev-env.sh` will log you into the PHP container. If you 
want to log in again, run:
```bash
docker compose exec php bash
```

### Generate JWT keys
Inside the PHP container, run:
```bash
php bin/console lexik:jwt:generate-keypair
```

### Create/Update DB schemas
Inside the PHP container, run:
```bash
php bin/console doctrine:mongodb:schema:create
php bin/console doctrine:schema:create
```

If you want to update the schema, run:
```bash
php bin/console doctrine:mongodb:schema:update --force
php bin/console doctrine:schema:update --force
```

### Swagger (OpenAPI) documentation
The Swagger documentation is available at [http://localhost:8000/api/doc](http://localhost:8000/api/doc).

## E2E Tests
Because the project may be served by two different types of databases, and many entities/documents depend on one another,
the tests have been structured to handle all possible scenarios in order from the objects highest in the hierarchy to 
the objects lowest in the hierarchy. If any assertion for a given database type fails along the way, the test will
terminate and subsequent operations will not be checked.

We want the whole thing works, so not checking subsequent assertions on failure is a drawback we can accept.

### How E2E Tests work
A separate docker environment is described in the `docker-compose.test.yml` file so as not to interfere with the data 
in the development environment. Tests run in this environment, for each run, remove the previous databases, execute, 
and when the assertion check is completed, the databases are removed.

The main file is `AppTest.php`, which is responsible for taking care of the life cycle of the test environment and 
running a series of tests for specific resources in the right order.

The tests for specific resources should be contained in the Modules folder and inherit from `AbstractControllerTest` class, 
which contains abstract methods indicating what should be checked. It is enough that the method starts with `testShould` 
for it to be run.

### Run E2E Environment and Tests
In terminal, run:
```bash
sh run-test-env.sh
```
You will be logged into the PHP test container. Run the tests with:
```bash
./vendor/bin/phpunit
```