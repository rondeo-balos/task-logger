<?php

use App\Http\Controllers\NotesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\TasksController;
use App\Models\Tasks;
use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $data = Tasks::whereBetween('start', [strtotime(date('Y-m-01')), strtotime(date('Y-m-t'))])->orderByDesc( 'start' )->get();

    if($data) {
        $data = $data->groupBy( function($task) {
            $date = Carbon::parse($task->start);
            return $date->format('W');
        });
        $data = $data->map( function($group) {
            return $group->groupBy( function($task) {
                $date = Carbon::parse($task->start);
                return $date->format('d');
            });
        });
    }
    return Inertia::render('Welcome', [
        'tasks' => $data
    ]);
})->name( 'home' );

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

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