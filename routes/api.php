<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('user', fn (Request $request) => $request->user())->name('api.user');
    Route::get('todos', [\App\Http\Controllers\TodoController::class, 'index'])->name('api.todos.index');
    Route::post('todos', [\App\Http\Controllers\TodoController::class, 'store'])->name('api.todos.create');
    Route::post('todos/{todo:uuid}/edit', [\App\Http\Controllers\TodoController::class, 'update'])->name('api.todos.edit');
    Route::delete('todos/{todo:uuid}', [\App\Http\Controllers\TodoController::class, 'destroy'])->name('api.todos.delete');

    Route::get('team', [\App\Http\Controllers\TeamController::class, 'index'])->name('api.team.index');
    Route::get('team/{user}', [\App\Http\Controllers\TeamController::class, 'show'])->name('api.team.show');

});


