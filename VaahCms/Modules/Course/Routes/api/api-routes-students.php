<?php
use VaahCms\Modules\Course\Http\Controllers\Backend\StudentsController;
/*
 * API url will be: <base-url>/public/api/course/students
 */
Route::group(
    [
        'prefix' => 'course/students',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', [StudentsController::class, 'getAssets'])
        ->name('vh.backend.course.api.students.assets');
    /**
     * Get List
     */
    Route::get('/', [StudentsController::class, 'getList'])
        ->name('vh.backend.course.api.students.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [StudentsController::class, 'updateList'])
        ->name('vh.backend.course.api.students.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [StudentsController::class, 'deleteList'])
        ->name('vh.backend.course.api.students.list.delete');


    /**
     * Create Item
     */
    Route::post('/', [StudentsController::class, 'createItem'])
        ->name('vh.backend.course.api.students.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [StudentsController::class, 'getItem'])
        ->name('vh.backend.course.api.students.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [StudentsController::class, 'updateItem'])
        ->name('vh.backend.course.api.students.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [StudentsController::class, 'deleteItem'])
        ->name('vh.backend.course.api.students.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [StudentsController::class, 'listAction'])
        ->name('vh.backend.course.api.students.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [StudentsController::class, 'itemAction'])
        ->name('vh.backend.course.api.students.item.action');



});
