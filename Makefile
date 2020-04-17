# очищаем, скачиваем, собираем, поднимаем
init: docker-down-clear docker-pull docker-build docker-up
up: docker-up
down: docker-down

# Запуск всех контейнеров
docker-up:
	docker-compose up -d

# Остановка всех контейнеров
docker-down:
	docker-compose down --remove-orphans

# Остановка и очистка всех контейнеров
docker-down-clear:
	docker-compose down -v --remove-orphans

# Скачать образы
docker-pull:
	docker-compose pull

# Пересобрать контейнеры
docker-build:
	docker-compose build

# Удалить все тома
docker-rm-volume:
	docker volume prune

# Запустить обновление пакетов composer
backend-composer-update:
	docker-compose run --rm backend-php-cli composer update

# Обновить классы для автозагрузчика
backend-composer-dump-autoload:
	docker-compose run --rm backend-php-cli composer dump-autoload

frontend-clear:
	docker run --rm -v ${PWD}/frontend:/node -w /node alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-yarn-install frontend-ready

frontend-yarn-install:
	docker-compose run --rm frontend-node-cli yarn install

frontend-ready:
	docker run --rm -v ${PWD}/frontend:/node -w /node alpine touch .ready