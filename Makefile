build:
	@echo "Building docker container from docker-compose.yml"
	docker-compose up --build -d

clean-build:
	@echo "Building clean docker container from docker-compose.yml"
	docker-compose up --build -d --force-recreate --remove-orphans

shell:
	@echo "Open shell in container"
	docker exec -it whitecat-82 sh

stop:
	@echo "Stopping docker container"
	docker-compose down

test:
	@echo "Running test..."
	docker exec -it whitecat-82 composer test

php-cs-fixer:
	@echo "Launching php-cs-fixer"
	docker exec -it whitecat-82 composer php-cs-fixer

phpstan:
	@echo "Launching php-cs-fixer"
	docker exec -it whitecat-82 composer phpstan

phpstan-test:
	@echo "Launching php-cs-fixer"
	docker exec -it whitecat-82 composer phpstan-test
