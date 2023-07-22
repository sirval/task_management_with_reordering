<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
Route::get('/tasks/create/task', [TaskController::class, 'create'])->name('task.create');
Route::post('/tasks/create/task',[TaskController::class, 'store'])->name('task.store');
Route::get('/tasks/edit/{id}', [TaskController::class, 'edit'])->name('task.edit');
Route::patch('/tasks/edit/{id}', [TaskController::class, 'update'])->name('task.update');
Route::delete('/tasks/delete/{id}', [TaskController::class, 'destroy'])->name('task.destroy');
Route::post('update-order', [TaskController::class, "updateOrder"])->name("task.reorder");
Route::get('/task/list', [TaskController::class, "getTasksByProjectId"])->name("task.all");
Route::get('/tasks/edit_project/{id}', [ProjectController::class, "edit"])->name("project.edit");
Route::patch('/tasks/edit_project/{id}', [ProjectController::class, "update"])->name("project.update");
Route::delete('/tasks/delete_project', [ProjectController::class, "destroy"])->name("project.destroy");