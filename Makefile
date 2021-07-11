.SILENT:

DOCKER_COMPOSE = docker-compose
DOCKER_LANDINGPAGE_CONTAINER_EXEC = $(DOCKER_COMPOSE) exec landingpage
DOCKER_BOXBILLING_CONTAINER_EXEC = $(DOCKER_COMPOSE) exec landingpage
DOCKER_DB_CONTAINER_EXEC = $(DOCKER_COMPOSE) exec mysql
DOCKER_LANDINGPAGE_EXECUTABLE_CMD = php -dmemory_limit=1G
DOCKER_BOXBILLING_EXECUTABLE_CMD = php -dmemory_limit=1G


all: start-recreate reinstall
start:          ## Start app
	$(DOCKER_COMPOSE) up -d
ifeq (,$(wildcard ./landingpage/composer.lock))
	cp ./landingpage/config/env.example.php ./landingpage/config/env.php
	$(DOCKER_LANDINGPAGE_CONTAINER_EXEC) composer install
	$(DOCKER_LANDINGPAGE_CONTAINER_EXEC) vendor/bin/phoenix migrate
# $(DOCKER_LANDINGPAGE_CONTAINER_EXEC) vendor/bin/phinx migrate -t 20160111202556
endif
ifeq (,$(wildcard ./boxbilling/src/bb-config.php))
	cp ./boxbilling/src/bb-config-sample.php ./boxbilling/src/bb-config.php
# $(DOCKER_BOXBILLING_CONTAINER_EXEC) $(DOCKER_BOXBILLING_EXECUTABLE_CMD) ./boxbilling/bin/prepare.php
endif

start-recreate: ## Start app with full rebuild
	$(DOCKER_COMPOSE) up -d  --build --force-recreate --remove-orphans
stop:           ## Stop app
	$(DOCKER_COMPOSE) stop
remove: stop    ## Stop and remove app
	$(DOCKER_COMPOSE) rm -f
logs:           ## Show app logs
	$(DOCKER_COMPOSE) logs -ft --tail=50
exec-landingpage:       ## Enter landingpage container shell
	$(DOCKER_LANDINGPAGE_CONTAINER_EXEC) bash
exec-boxbilling:       ## Enter boxbilling container shell
	$(DOCKER_BOXBILLING_CONTAINER_EXEC) bash
exec-db:        ## Enter DB container shell
	$(DOCKER_DB_CONTAINER_EXEC) bash
install: start  ## Install app after start
# $(DOCKER_BOXBILLING_CONTAINER_EXEC) composer install --working-dir=src --no-progress --no-suggest --prefer-dist --no-dev
reinstall:      ## Reinstall app
# rm -rf ./src/bb-config.php
	make install