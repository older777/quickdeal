## Квик Дил, тестовое задание (Артур М.)

Инструкция по установке и запуску. Скачать GIT-репозиторий. Создать новый *.env* файл (из *.env.example*).

Выполнить следующие команды:

    composer install
    php artisan vendor:publish 
    # ресурсы всех вендров: All providers and tags
    php artisan sail:publish
    ./sail build --no-cache
    ./sail up

    (в новом окне терминала)
    ./sail artisan migrate
    ./sail artisan db:seed
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
