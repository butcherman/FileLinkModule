<?php

use Modules\FileLinkModule\Http\Controllers\AdminController;
use Modules\FileLinkModule\Http\Controllers\FileLinkFileController;
use Modules\FileLinkModule\Http\Controllers\FileLinkModuleController;
use Modules\FileLinkModule\Http\Controllers\GuestFileUploadController;

Route::name('FileLinkModule.')->group(function()
{
    /**
     * Guest routes
     * Note - authorized users can visit the routes as well without being redirected
     */
    Route::prefix('file-links')->group(function()
    {
        Route::get('show/{hash}', [GuestFileUploadController::class, 'show']);
        Route::get('{hash}',      [GuestFileUploadController::class, 'show'])  ->name('guest');
        Route::put('{hash}',      [GuestFileUploadController::class, 'update'])->name('guest.update');
    });

    /**
     * Authorized User routes
     */
    Route::middleware('auth')->group(function()
    {
        /**
         * Administration routes
         */
        Route::prefix('links/administration')->name('admin.')->group(function()
        {
            Route::get('/',                 [AdminController::class, 'index'])->name('index')->breadcrumb('File Links', 'admin.index');
            Route::get('{username}',        [AdminController::class, 'show'])->name('show')  ->breadcrumb('User Links', '.index');
        });

        /**
         * Standard File Link Routes
         */
        Route::prefix('links')->group(function()
        {
            Route::put(   'files/{id}/update',     [FileLinkFileController::class, 'update']) ->name('files.update');
            Route::delete('files/{id}/destroy',    [FileLinkFileController::class, 'destroy'])->name('files.destroy');

            Route::get(    '{id}/edit',            [FileLinkModuleController::class, 'edit'])   ->name('edit')  ->breadcrumb('Edit File Link', '.show');
            Route::delete( '{id}/delete',          [FileLinkModuleController::class, 'destroy'])->name('destroy');
            Route::get(    'create',               [FileLinkModuleController::class, 'create']) ->name('create')->breadcrumb('New File Link', '.index');
            Route::put(    '{id}',                 [FileLinkModuleController::class, 'update']) ->name('update');
            Route::get(    '{id}',                 [FileLinkModuleController::class, 'show'])   ->name('show')  ->breadcrumb('Link Details', '.index');
            Route::post(   '/',                    [FileLinkModuleController::class, 'store'])  ->name('store');
            Route::get(    '/',                    [FileLinkModuleController::class, 'index'])  ->name('index') ->breadcrumb('File Links');
        });
    });

});
