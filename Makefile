UID := $(shell id -u)
DOCKER_BE = symfony-course-skeleton-app

help:
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+): ## (.+)' $(MAKEFILE_LIST) | column -t -c 2 -s ':#'

start: ## Start the containers
	docker network create symfony-course-skeleton-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	U_ID=$(UID) docker compose up -d

stop: ## Stop the containers
	U_ID=$(UID) docker compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuild all containers
	docker network create symfony-course-skeleton-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	U_ID=$(UID) docker compose build

prepare: ## Install composer deps
	$(MAKE) composer-install

run: ## Start Symfony built-in server
	U_ID=$(UID) docker exec -d --user $(UID) $(DOCKER_BE) sh -c "cd /appdata/www && nohup php -S 0.0.0.0:8000 -t public > /dev/null 2>&1 &"

logs: ## Show Symfony logs
	U_ID=$(UID) docker exec -it --user $(UID) $(DOCKER_BE) symfony server:log

composer-install:
	U_ID=$(UID) docker exec --user $(UID) $(DOCKER_BE) composer install --no-interaction

ssh-be:
	U_ID=$(UID) docker exec -it --user $(UID) $(DOCKER_BE) bash
