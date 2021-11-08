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

