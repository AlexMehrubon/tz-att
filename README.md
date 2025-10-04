# tz-att

Для запуска проекта необходим:
1. Docker
2. Docker compose
3. PHP, Composer, npm и тд. не нужны на хост-машине

Порядок запуска проекта

1. На основе .env.example создать файл .env (Ничего менять не надо, берем все значения .env.example)
2. docker compose up
3. docker compose exec php bash
4. В контейнере выполнить: 
    3.1. php artisan key:generate
    3.2. php artisan migrate
    3.3. php artisan db:seed
5. npm run build 
6. Перейти localhost:5173