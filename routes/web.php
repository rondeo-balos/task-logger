<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\WorkplaceController;
use App\Http\Middleware\CheckWorkplace;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/workplace', function(){
    $workplaces = Auth::user()->workplaces;
    return Inertia::render('Workplace/Workplace', [
        'workplaces' => $workplaces
    ]);
})->name('workplace')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function() {
    Route::get('/', function () {
        $workplaces = Auth::user()->workplaces;
        $tags = TagsController::list();
        $tasks = TasksController::list();
        $notes = NotesController::list();
    
        $data = [
            'workplaces' => $workplaces,
            'notes' => $notes,
            'tags' => $tags,
            'tasks' => $tasks['data'],
            'total' => $tasks['total']
        ];
        return Inertia::render('Worksheet', $data);
    })->name( 'home' )->middleware(CheckWorkplace::class.':read');
});

Route::middleware(['auth', CheckWorkplace::class.':write'])->group(function() {
    Route::get('/workplace/edit/{id}', [WorkplaceController::class, 'edit'])->name('workplace.edit');
    Route::get('/workplace/set/{id}', [WorkplaceController::class, 'set'])->name('workplace.set');
    Route::post( '/workplace/give/{id}', [WorkplaceController::class, 'giveWorkplacePermissionTo'])->name('workplace.give');
    Route::post('/workplace/new', [WorkplaceController::class, 'create'])->name('workplace.create');

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