install:
	docker-compose up -d
	docker exec www composer install
	docker exec www php bin/console --no-interaction doctrine:migrations:migrate
up:
	docker-compose up -d
down:
	docker-compose down
bash:
	docker exec -it www bash
