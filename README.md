# Система заявок (Laravel 12)

Короткая инструкция по запуску, настройке и использованию публичного API для заявок пользователей и ответов ответственного лица.

## Быстрый старт

```bash
composer install
cp .env.example .env  # или скопируйте вручную
php artisan key:generate

# Настройте .env (см. ниже), затем
php artisan migrate
php artisan serve
```

- Приложение по умолчанию: http://127.0.0.1:8000/
- Документация API (Swagger UI): http://127.0.0.1:8000/docs
- OpenAPI JSON: http://127.0.0.1:8000/docs/openapi.json

## ENV (ключевые переменные)

- `ADMIN_API_TOKEN` — токен для админ-эндпоинтов (пример: `secret`).
- `MAIL_MAILER` — способ отправки писем. Для локальной разработки удобно `log` (пишет письма в `storage/logs/laravel.log`) или `array` (тесты).
- `QUEUE_CONNECTION` — по умолчанию `sync`. Для ассинхронной отправки писем переключите на очередь и запустите воркер.
- Параметры БД (`DB_CONNECTION`, `DB_*`) — укажите вашу БД (MySQL/Postgres/SQLite).
- `CORS_ALLOWED_ORIGINS` — список разрешённых Origin (через запятую) для `api/*` (по умолчанию `*`).

## Модель данных заявки

- `id` — идентификатор
- `name` — имя пользователя (required)
- `email` — email пользователя (required)
- `status` — enum: `Active` | `Resolved` (по умолчанию `Active`)
- `message` — сообщение пользователя (required)
- `comment` — ответ ответственого лица (required, если `status=Resolved`)
- `created_at`, `updated_at`

## API

Базовый путь: `/api`

- Публичная отправка заявки
  - POST `/requests`
  - Тело: `{ "name": string, "email": string, "message": string }`
  - Пример:
    ```bash
    curl -X POST http://127.0.0.1:8000/api/requests \
      -H "Content-Type: application/json" \
      -d '{"name":"John","email":"john@example.com","message":"Help"}'
    ```

- Получение списка заявок (для ответственного лица)
  - GET `/requests`
  - Заголовок: `X-Admin-Token: <ваш токен>`
  - Фильтры (query): `status=Active|Resolved`, `from=YYYY-MM-DD` (или datetime), `to=YYYY-MM-DD`, `per_page=1..100`
  - Пример:
    ```bash
    curl "http://127.0.0.1:8000/api/requests?status=Resolved&per_page=20" \
      -H "X-Admin-Token: secret"
    ```

- Ответ на заявку / установка статуса
  - PUT `/requests/{id}`
  - Заголовок: `X-Admin-Token: <ваш токен>`
  - Тело: `{ "status": "Active"|"Resolved", "comment"?: string }`
  - При `status=Resolved` — поле `comment` обязательно. При успешном переводе в `Resolved` отправляется письмо пользователю.
  - Пример:
    ```bash
    curl -X PUT http://127.0.0.1:8000/api/requests/1 \
      -H "Content-Type: application/json" -H "X-Admin-Token: secret" \
      -d '{"status":"Resolved","comment":"Готово"}'
    ```

## Авторизация для ответственного лица

- Простейшая схема через заголовок `X-Admin-Token`.
- Значение сравнивается с `ADMIN_API_TOKEN` из `.env`.

## Email и очереди

- При переводе заявки в `Resolved` отправляется письмо (`App\Mail\SupportRequestResolvedMail`).
- Для разработки используйте `MAIL_MAILER=log` — письма доступны в `storage/logs/laravel.log`.
- Для асинхронной отправки через очередь:
  ```bash
  php artisan queue:work
  ```

## Тесты

```bash
php artisan test
```

- Для использования SQLite в тестах включите расширения `pdo_sqlite` и `sqlite3` в php.ini, либо настройте тестовую БД и переменные окружения.

## Примечания по масштабированию

- Индексы по `status`, `created_at`, `updated_at` для ускорения фильтраций.
- Пагинация (`per_page`) ограничена 1..100.
- CORS ограничивается переменной `CORS_ALLOWED_ORIGINS`.
