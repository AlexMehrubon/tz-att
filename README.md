# tz-att

Реализация представляет собой SPA где в качестве API выступает Laravel 12
В качестве клиента выступает Vue 3 

Для запуска проекта необходим:
1. Docker
2. Docker compose
3. Composer
4. Node.js 20.19+ / npm

Порядок запуска проекта

1. На основе .env.example создать файл .env (Ничего менять не надо, берем все значения .env.example)
2. cd ./backend && composer install
3. docker compose up --build
4. docker compose exec php bash в новом терминале
5. В контейнере выполнить: 
    3.1. php artisan key:generate
    3.2. php artisan migrate
    3.3. php artisan db:seed
6. cd ./frontend && npm install && npm run dev
7. Перейти на localhost:5173