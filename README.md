# TeamSpeak3-Server-Creator

## About
A small application to run 10 TeamSpeak3 servers in their own Docker containers and start or stop them with one click.

With the help of TeamSpeak's web query api all servers have directly all necessary channels without creating them manually.

Created with Symfony 6 and PHP 8.1. Docker is needed on the server were this application will run.

## Setup/Tear down

### Drop Database
```shell
php bin/console doctrine:database:drop --force
```

### Create Database
```shell
php bin/console doctrine:database:create
```

### Create Migrations From Mapping Infos
```shell
php bin/console doctrine:migrations:diff
```

### Load Migrations
```shell
php bin/console doctrine:migrations:migrate --no-interaction
```