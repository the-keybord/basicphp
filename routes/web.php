<?php
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\AccessCodeController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImageProxyController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::post('/access', [PublicController::class, 'accessCode'])->name('access.code');
Route::get('/code={code}', [PublicController::class, 'directJoin'])->name('direct.join');

Route::get('/test/join/{code}', [PublicController::class, 'joinTest'])->name('test.join');
Route::post('/test/join/{code}', [PublicController::class, 'startTest'])->name('test.start');
Route::get('/test/session/{token}', [PublicController::class, 'showSession'])->name('test.session');
Route::post('/test/session/{token}/submit', [PublicController::class, 'submitSession'])->name('test.submit');
Route::get('/test/session/{token}/results', [PublicController::class, 'showResults'])->name('test.results');
Route::post('/test/session/{token}/auto-save', [PublicController::class, 'autoSave'])->name('test.session.autosave');
Route::get('/images/questions/{filename}', [ImageProxyController::class, 'show'])->name('image.proxy');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('questions', QuestionController::class);
        Route::get('questions/{question}/preview', [QuestionController::class, 'preview'])
            ->name('questions.preview');
        Route::post('questions/upload-image', [QuestionController::class, 'uploadImage'])
            ->name('questions.upload-image');
        Route::get('questions/{question}/set-answer', [QuestionController::class, 'setAnswer'])
            ->name('questions.set-answer');
        Route::post('questions/{question}/set-answer', [QuestionController::class, 'storeAnswer'])
            ->name('questions.store-answer');

        Route::resource('tests', TestController::class);
        Route::get('tests/{test}/preview', [TestController::class, 'preview'])
            ->name('tests.preview');
        Route::post('tests/{test}/toggle', [TestController::class, 'toggle'])
            ->name('tests.toggle');

        Route::resource('codes', AccessCodeController::class);

        Route::get('sessions', [SessionController::class, 'index'])->name('sessions.index');
        Route::get('sessions/{session}/review', [SessionController::class, 'review'])->name('sessions.review');
        Route::post('sessions/{session}/interrupt', [SessionController::class, 'interrupt'])->name('sessions.interrupt');
    });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
