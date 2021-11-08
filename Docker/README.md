# How to configure with docker

>Make sure you have `docker` and `docker-compose` installed in your machine

Set the following local dns entries in your /etc/hosts file

``` 
127.0.0.1 www.test-app.com
```

>The default password for MySQL is `password` and the username is `root`

## Update your .env

>`host.docker.internal` will be the host address for the database

## Run application
Set executable permission to the `start` file, which is located at the root directory of the project

Run below command to run application
```
./start
```
## List running docker container
```
docker ps
```

## Execute artisan command
```
docker exec -it [CONTAINER_NAME] php artisan [COMMAND]

e.g.
docker exec -it test-app php artisan migrate
```

## Execute composer command
```
docker exec -it [CONTAINER_NAME] composer install

e.g.
docker exec -it test-app composer install
```

> For this app the container name `test-app`

## Run and Detach

>`docker compose up -d` won't be running on the console.

## To Stop all running containers

>`docker stop $(docker ps -q)`

## To Stop remove all containers

>`docker-compose down`

## To Import the database file in database container

>`docker exec -i 3s-mysql mysql -uroot -ppassword DATABASE_NAME < FILE_NAME.sql`
e.g. `docker exec -i 3s-mysql mysql -uroot -ppassword 3s_local_db < /home/shawon/Downloads/old_database_backup.sql`

## To remove/delete an image or multiple

>`docker rmi -f image_name image_name_2`

## To remove/delete a container or multiple

>`docker rm -f container_name container_name_2`
