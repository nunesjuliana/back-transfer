run_tests:
	docker exec webserver vendor/bin/phpunit

up_database:
	docker-compose up -d database

up_serve:
	docker-compose up -d webserver

stop:
	docker-compose down
	docker-compose rm

migrates:
	docker exec webserver php artisan migrate

queue:
	docker exec webserver php artisan queue:work

composer_install:
	composer install

run: up_database up_serve queue

start: up_database up_serve composer_install migrates queue




