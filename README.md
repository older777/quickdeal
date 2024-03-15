## Квик Дил, тестовое задание (Артур М.)

Скачать проект

    git clone https://github.com/older777/quickdeal.git

Создать новый *.env* файл (из *.env.example*).

    cp .env.example .env

Выполнить следующие команды:

    cd quickdeal
    composer install
    php artisan vendor:publish 
    # ресурсы всех вендров: All providers and tags
    php artisan sail:publish
    ./sail build
    ./sail up

    (в новом окне терминала)
    ./sail artisan migrate
    ./sail artisan db:seed
    ./sail artisan storage:link
    ./sail npm install
    ./sail npm run dev

Проверка синтаксиса PHP и JS

    ./sail pint --test
    ./sail npm run eslint

Перейти на страницу [http://0.0.0.0/](http://0.0.0.0/). Авторизоваться пользователем manager (*test@test*, *123*).
В ЛК создать новую задачу, обновить список задач (появятся новые записи). Редактировать статусы задач, удалить задачи. 
Создать записи в хранилище VueX. Выйти из профиля.

Авторизоваться пользователем moderator (*moder@moder*, *123*), повторить те же действия.

По вопросам прошу писать на [Телеграм](https://t.me/artip7)
