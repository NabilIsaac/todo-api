# Todo API

A Laravel 11 RESTful API for managing todos.

## Requirements

- PHP 8.2+
- Composer
- SQLite/MySQL

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run migrations: `php artisan migrate`
5. Start the server: `php artisan serve`

## API Endpoints

### List Todos
```
GET /api/todos
```

Query Parameters:
- `status`: Filter by status (not_started, in_progress, completed)
- `search`: Search in title and details
- `sort_by`: Field to sort by (default: created_at)
- `sort_direction`: Sort direction (asc/desc, default: desc)
- `page`: Page number for pagination
- `per_page`: Items per page (default: 10)

### Create Todo
```
POST /api/todos
```

Request Body:
```json
{
    "title": "string",
    "details": "string",
    "status": "not_started|in_progress|completed"
}
```

### Show Todo
```
GET /api/todos/{id}
```

### Update Todo
```
PUT /api/todos/{id}
```

Request Body:
```json
{
    "title": "string",
    "details": "string",
    "status": "not_started|in_progress|completed"
}
```

### Delete Todo
```
DELETE /api/todos/{id}
```

## Testing

Run the test suite:
```
php artisan test
```
