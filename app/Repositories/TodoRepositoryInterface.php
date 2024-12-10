<?php

namespace App\Repositories;

use App\Models\Todo;
use Illuminate\Pagination\LengthAwarePaginator;

interface TodoRepositoryInterface
{
    public function getAllPaginated(array $filters, string $sortField, string $sortDirection): LengthAwarePaginator;
    public function create(array $data): Todo;
    public function update(Todo $todo, array $data): Todo;
    public function delete(Todo $todo): bool;
}
