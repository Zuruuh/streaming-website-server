DOCKER_COMPOSE = docker compose -f ./docker-compose.dev.yaml

install:
	composer install
	$(DOCKER_COMPOSE) pull

start:
	$(DOCKER_COMPOSE) up -d
	sleep 4
	echo "yes" | bin/console doctrine:migrations:migrate latest
	# bin/console doctrine:fixtures:load -y
	symfony serve -d

stop:
	$(DOCKER_COMPOSE) down -v
	symfony server:stop

restart: stop start
