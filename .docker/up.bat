docker-compose -p eshop up -d --build --force-recreate
docker exec eshop wait-for-it eshop_db:3306 -t 60
