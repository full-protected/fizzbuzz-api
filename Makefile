.PHONY: build up down restart install shell logs migrate migration test stan cs-check cs-fix cache-clear setup prod-check

build:
	docker compose build

up:
	docker compose up -d

down:
	docker compose down

restart: down up

install:
	docker compose exec php composer install

shell:
	docker compose exec php bash

logs:
	docker compose logs -f

migrate:
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

migration:
	docker compose exec php php bin/console make:migration

test:
	docker compose exec -e APP_ENV=test php vendor/bin/phpunit

stan:
	docker compose exec php vendor/bin/phpstan analyse

cs-check:
	docker compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff --allow-risky=yes

cs-fix:
	docker compose exec php vendor/bin/php-cs-fixer fix --allow-risky=yes

cache-clear:
	docker compose exec php php bin/console cache:clear

setup: build up install migrate
	docker compose exec php php bin/console --env=test doctrine:database:create --if-not-exists
	docker compose exec php php bin/console --env=test doctrine:migrations:migrate --no-interaction

prod-check:
	docker compose exec -e APP_ENV=prod -e APP_DEBUG=0 php php bin/console cache:clear
