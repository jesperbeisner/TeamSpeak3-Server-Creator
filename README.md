# TeamSpeak3-Server-Creator

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