#!/bin/bash

DOCKER_CONTAINER = nurschool-common-php
OS := $(shell uname)

ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

help: # Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Start the containers
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

build: ## Rebuilds all the containers
	U_ID=${UID} docker-compose build

prepare: ## Runs commands
	$(MAKE) composer-install

# Commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_CONTAINER} composer install --no-scripts --no-interaction --optimize-autoloader

ssh: ## ssh's into the container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_CONTAINER} bash

code-style: ## Runs php-cs to fix code styling follwing Symfony rules
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_CONTAINER} php-cs-fixer fix src --rules=@Symfony