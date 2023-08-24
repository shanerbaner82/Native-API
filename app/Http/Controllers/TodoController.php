<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['todos' => request()->user()->todos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        $validated = $request->validated();
        request()->user()->todos()->create($validated);

        return response()->json(['todos' => request()->user()->todos], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Todo $todo)
    {
        $validated = request()->validate([
            'title' => ['required', 'string'],
            'completed_at' => ['sometimes'],
            'currently_working_on' => ['sometimes'],
        ]);

        $todo->update($validated);

        return response()->json(['todos' => request()->user()->todos], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $todo->forceDelete();
    }
}
