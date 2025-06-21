<?php
use VaahCms\Modules\Course\Http\Controllers\Backend\TeachersController;
/*
 * API url will be: <base-url>/public/api/course/teachers
 */
Route::group(
    [
        'prefix' => 'course/teachers',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', [TeachersController::class, 'getAssets'])
        ->name('vh.backend.course.api.teachers.assets');
    /**
     * Get List
     */
    Route::get('/', [TeachersController::class, 'getList'])
        ->name('vh.backend.course.api.teachers.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [TeachersController::class, 'updateList'])
        ->name('vh.backend.course.api.teachers.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [TeachersController::class, 'deleteList'])
        ->name('vh.backend.course.api.teachers.list.delete');


    /**
     * Create Item
     */
    Route::post('/', [TeachersController::class, 'createItem'])
        ->name('vh.backend.course.api.teachers.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [TeachersController::class, 'getItem'])
        ->name('vh.backend.course.api.teachers.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [TeachersController::class, 'updateItem'])
        ->name('vh.backend.course.api.teachers.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [TeachersController::class, 'deleteItem'])
        ->name('vh.backend.course.api.teachers.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [TeachersController::class, 'listAction'])
        ->name('vh.backend.course.api.teachers.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [TeachersController::class, 'itemAction'])
        ->name('vh.backend.course.api.teachers.item.action');



});
