<?php

use Illuminate\Support\Facades\Route;

Route::get('/projects', App\Http\Controllers\ProjectListController::class);
Route::get('/projects/{id}', App\Http\Controllers\ProjectGetController::class)->where('id', '[1-9]\d*');
Route::get('/projects/{id}/tasks', App\Http\Controllers\TasksListController::class)->where('id', '[1-9]\d*');
Route::post('/projects', App\Http\Controllers\ProjectCreateController::class);

Route::post('/tasks', App\Http\Controllers\TaskCreateController::class);
Route::patch('/tasks/{id}/toggle', App\Http\Controllers\TaskToggleController::class)->where('id', '[1-9]\d*');
Route::delete('/tasks/{id}', App\Http\Controllers\TaskDeleteController::class)->where('id', '[1-9]\d*');

