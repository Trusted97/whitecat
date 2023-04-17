docker-build:
	@echo "Building docker container from docker-compose.yml"
	docker compose up --build -d

docker-clean-build:
	@echo "Building clean docker container from docker-compose.yml"
	docker compose up --build -d --force-recreate --remove-orphans

docker-shell:
	@echo "Open shell in container"
	docker exec -it whitecat-81 sh

docker-stop:
	@echo "Stopping docker container"
	docker compose down

test:
	@echo "Running test..."
	docker exec -it whitecat-81 composer test

php-cs-fixer:
	@echo "Launching php-cs-fixer"
	docker exec -it whitecat-81 composer php-cs-fixer