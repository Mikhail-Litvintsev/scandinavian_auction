Тестовое задание: "Скандинавский аукцион"

Для установки требуется docker и bash terminal.

1. Загрузите локально репозиторий
``` bash
git clone https://github.com/Mikhail-Litvintsev/scandinavian_auction.git
```
2. В папке с проектом выполните команду:
``` bash
cp .env.example .env 
docker compose build 
docker compose up -d 
docker compose exec backend /bin/bash -lc 'composer install && php artisan migrate:fresh && php artisan optimize &&  php artisan cache:clear'
python -m webbrowser -t "http://localhost"
``` 
