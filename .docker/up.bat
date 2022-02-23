docker-compose -p eshop up -d --build --force-recreate
call d wait-for-it eshop_db:3306 -t 60
call d php bin/console.php migrations:migrate
call d php bin/console.php eshop:create-user MichalHaltuf haltuf@imagineo.cz administrator test
call d php bin/console.php doctrine:fixtures:load -n --append