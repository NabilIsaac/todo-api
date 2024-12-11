<?php

namespace App\Services;

use App\Models\Todo;
use App\Http\Queries\TodoQuery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\TodoStatus;

class TodoService
{
    public function getFilteredTodos(TodoQuery $todoQuery): LengthAwarePaginator
    {
        return $this->buildTodoQuery($todoQuery->filters())
            ->paginate(10);
    }

    private function buildTodoQuery(array $filters): Builder
    {
        $query = Todo::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        
        return $query->orderBy($sortField, $sortDirection);
    }

    public function create(array $data): Todo
    {
        if (!isset($data['status'])) {
            $data['status'] = TodoStatus::NOT_STARTED->value;
        }

        return Todo::create($data);
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);
        return $todo->fresh();
    }

    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }
}
