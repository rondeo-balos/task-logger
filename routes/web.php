<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/tasks', [TasksController::class, 'list']);
Route::post('/api/tasks', [TasksController::class, 'create']);
Route::delete('/api/tasks/{id}', [TasksController::class, 'delete']);
Route::post('/api/tasks/{id}', [TasksController::class, 'update']);

Route::get('/api/notes', [NotesController::class, 'list']);
Route::post('/api/notes', [NotesController::class, 'create']);
Route::delete('/api/notes/{id}', [NotesController::class, 'delete']);
Route::post('/api/notes/{id}', [NotesController::class, 'update']);

Route::get('/api/tags', [TagsController::class, 'list']);
Route::post('/api/tags', [TagsController::class, 'create']);
Route::delete('/api/tags/{id}', [TagsController::class, 'delete']);
Route::post('/api/tags/{id}', [TagsController::class, 'update']);