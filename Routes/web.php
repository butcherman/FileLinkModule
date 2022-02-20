<?php

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

use Modules\FileLinkModule\Http\Controllers\AdminController;
use Modules\FileLinkModule\Http\Controllers\FileLinkFileController;
use Modules\FileLinkModule\Http\Controllers\FileLinkModuleController;
use Modules\FileLinkModule\Http\Controllers\GuestFileUploadController;

Route::prefix('file-links')->name('FileLinkModule.')->group(function()
{
    Route::get('{hash}', [GuestFileUploadController::class, 'show'])->name('guest');
    Route::put('{hash}', [GuestFileUploadController::class, 'update'])->name('guest.update');
});

Route::prefix('links')->name('FileLinkModule.')->group(function()
{
    /**
     * Authorized User routes for using File Links
     */
    Route::middleware('auth')->group(function() {
        Route::put(   'files/{id}/update',     [FileLinkFileController::class, 'update']) ->name('files.update');
        Route::delete('files/{id}/destroy',    [FileLinkFileController::class, 'destroy'])->name('files.destroy');

        Route::get(    '{id}/edit',   [FileLinkModuleController::class, 'edit'])   ->name('edit');
        Route::delete( '{id}/delete', [FileLinkModuleController::class, 'destroy'])->name('destroy');
        Route::get(    'create',      [FileLinkModuleController::class, 'create']) ->name('create');
        Route::put(    '{id}',        [FileLinkModuleController::class, 'update']) ->name('update');
        Route::get(    '{id}',        [FileLinkModuleController::class, 'show'])   ->name('show');
        Route::post(   '/',           [FileLinkModuleController::class, 'store'])  ->name('store');
        Route::get(    '/',           [FileLinkModuleController::class, 'index'])  ->name('index');

        /**
         * Administration routes for Module
         */
        Route::prefix('administration/file-link-module')->name('admin.')->group(function()
        {
            Route::get('/',           [AdminController::class, 'index'])->name('index');
            Route::get('{username}',  [AdminController::class, 'show'])->name('show');
        });
    });
});



