docker-build:
	@echo "Building docker container from docker-compose.yml"
	docker compose up --build -d

docker-shell:
	docker exec -it whitecat-81 sh

test:
	docker exec -it whitecat-81 composer test

php-cs-fixer:
	docker exec -it whitecat-81 composer php-cs-fixer