<?php
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/access', [PublicController::class, 'accessCode'])->name('access.code');

// A temporary placeholder route so you can see a successful redirect
Route::get('/test/{code}', function ($code) {
    return "Success! You joined the test with code: " . $code;
})->name('test.placeholder');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('questions', QuestionController::class);

    Route::get('questions/{question}/preview', [QuestionController::class, 'preview'])
        ->name('questions.preview');
});


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
