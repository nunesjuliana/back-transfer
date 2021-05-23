tests:
	docker exec webserver vendor/bin/phpunit

start:
	docker-compose up -d --build

stop:
	docker-compose down
	docker-compose rm

migrates:
	docker exec webserver php artisan migrate

queue:
	docker exec webserver php artisan queue:work

composer_install:
	composer install

run: migrates tests queue



