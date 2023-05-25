Тестовое задание: "Скандинавский аукцион"

Для установки требуется docker и bash terminal. Для автозапуска страницы command python в терминале (необязательно, сайт будет доступен по адресу "http://localhost")

Важно! Перед установкой: следует остановить прочие контейнеры docker.

1. Установка проекта (в папку scandinavian_auction):
``` bash
git clone https://github.com/Mikhail-Litvintsev/scandinavian_auction.git 
cd scandinavian_auction/
cp .env.example .env 
docker compose build 
docker compose up -d 
docker compose exec backend /bin/bash -lc 'composer install && php artisan migrate:fresh && php artisan optimize &&  php artisan cache:clear' && python -m webbrowser -t "http://localhost"
``` 
