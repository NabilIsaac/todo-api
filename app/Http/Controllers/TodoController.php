<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use App\Services\TodoService;
use App\Http\Queries\TodoQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    public function __construct(
        private readonly TodoService $todoService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $todoQuery = new TodoQuery($request);
        $todos = $this->todoService->getFilteredTodos($todoQuery);
        return TodoResource::collection($todos);
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        $todo = $this->todoService->create($request->validated());
        return (new TodoResource($todo))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Todo $todo): TodoResource
    {
        return new TodoResource($todo);
    }

    public function update(UpdateTodoRequest $request, Todo $todo): TodoResource
    {
        $updatedTodo = $this->todoService->update($todo, $request->validated());
        return new TodoResource($updatedTodo);
    }

    public function destroy(Todo $todo): JsonResponse
    {
        $this->todoService->delete($todo);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}