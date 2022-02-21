docker-compose -p eshop up -d --build --force-recreate
docker exec eshop wait-for-it eshop_db:3306 -t 60
docker exec eshop php bin/console.php migrations:migrate
docker exec eshop php bin/console.php eshop:create-user MichalHaltuf haltuf@imagineo.cz administrator test