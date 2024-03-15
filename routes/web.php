<?php

use App\Http\Controllers\ProfileController;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TokenService;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function (Request $request) {
    return Inertia::render('Dashboard', [
        'tasks' => Inertia::lazy(function (Request $request) {
            if (! $request->sort) {
                $collection = Task::all()->sortBy('id');
            } else {
                $collection = Task::all()->sortBy($request->sort);
            }

            return TaskResource::collection($collection);
        }),
        'token' => Inertia::lazy(fn () => TokenService::getUserToken()),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
