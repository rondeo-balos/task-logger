<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TasksController;
use App\Models\Tags;
use App\Models\Tasks;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $tags = TagsController::list();
    $tasks = TasksController::list();
    $notes = NotesController::list();

    return Inertia::render('Worksheet', [
        'notes' => $notes,
        'tags' => $tags,
        'tasks' => $tasks['data'],
        'total' => $tasks['total']
    ]);
})->name( 'home' );

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/api/tasks', [TasksController::class, 'index'])->name('tasks');
    Route::post('/api/tasks', [TasksController::class, 'create'])->name('tasks.create');
    Route::delete('/api/tasks/{id}', [TasksController::class, 'delete'])->name('tasks.delete');
    Route::post('/api/tasks/{id}', [TasksController::class, 'update'])->name('tasks.update');

    Route::get('/api/notes', [NotesController::class, 'index'])->name('notes');
    Route::post('/api/notes', [NotesController::class, 'create'])->name('notes.create');
    Route::delete('/api/notes/{id}', [NotesController::class, 'delete'])->name('notes.delete');
    Route::post('/api/notes/{id}', [NotesController::class, 'update'])->name('notes.update');

    Route::get('/api/tags', [TagsController::class, 'index'])->name('tags');
    Route::post('/api/tags', [TagsController::class, 'create'])->name('tags.create');
    Route::delete('/api/tags/{id}', [TagsController::class, 'delete'])->name('tags.delete');
    Route::post('/api/tags/{id}', [TagsController::class, 'update'])->name('tags.update');
});

require __DIR__.'/auth.php';

Route::get( '/export', function() {
    $tags = TagsController::list();
    $tasks = TasksController::list();

    return Inertia::render('Export', [
        'tags' => $tags,
        'tasks' => $tasks['data'],
        'total' => $tasks['total']
    ]);
})->name( 'export' );