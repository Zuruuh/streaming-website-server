DOCKER_COMPOSE = docker compose -f ./docker-compose.dev.yaml

install:
	composer install
	$(DOCKER_COMPOSE) pull

start:
	$(DOCKER_COMPOSE) -f ./docker-compose.dev.yaml up -d
	echo "yes" | bin/console doctrine:migrations:migrate latest
	# bin/console doctrine:fixtures:load -y
	symfony serve -d
