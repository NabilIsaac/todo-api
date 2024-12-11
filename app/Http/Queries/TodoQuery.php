<?php

namespace App\Http\Queries;

use Illuminate\Http\Request;

class TodoQuery
{
    private array $filters;

    public function __construct(Request $request)
    {
        $this->filters = $this->extractFilters($request);
    }

    public function filters(): array
    {
        return $this->filters;
    }

    private function extractFilters(Request $request): array
    {
        return [
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];
    }
}
