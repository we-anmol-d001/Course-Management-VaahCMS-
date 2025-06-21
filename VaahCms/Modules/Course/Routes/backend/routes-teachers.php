<?php

use VaahCms\Modules\Course\Http\Controllers\Backend\TeachersController;

Route::group(
    [
        'prefix' => 'backend/course/teachers',
        
        'middleware' => ['web', 'has.backend.access'],
        
],
function () {
    /**
     * Get Assets
     */
    Route::get('/assets', [TeachersController::class, 'getAssets'])
        ->name('vh.backend.course.teachers.assets');
    /**
     * Get List
     */
    Route::get('/', [TeachersController::class, 'getList'])
        ->name('vh.backend.course.teachers.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [TeachersController::class, 'updateList'])
        ->name('vh.backend.course.teachers.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [TeachersController::class, 'deleteList'])
        ->name('vh.backend.course.teachers.list.delete');


    /**
     * Fill Form Inputs
     */
    Route::any('/fill', [TeachersController::class, 'fillItem'])
        ->name('vh.backend.course.teachers.fill');

    /**
     * Create Item
     */
    Route::post('/', [TeachersController::class, 'createItem'])
        ->name('vh.backend.course.teachers.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [TeachersController::class, 'getItem'])
        ->name('vh.backend.course.teachers.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [TeachersController::class, 'updateItem'])
        ->name('vh.backend.course.teachers.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [TeachersController::class, 'deleteItem'])
        ->name('vh.backend.course.teachers.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [TeachersController::class, 'listAction'])
        ->name('vh.backend.course.teachers.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [TeachersController::class, 'itemAction'])
        ->name('vh.backend.course.teachers.item.action');

    //---------------------------------------------------------

});
