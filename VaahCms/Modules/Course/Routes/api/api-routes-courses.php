<?php
use VaahCms\Modules\Course\Http\Controllers\Backend\CoursesController;
/*
 * API url will be: <base-url>/public/api/course/courses
 */
Route::group(
    [
        'prefix' => 'course/courses',
        'namespace' => 'Backend',
    ],
function () {

    /**
     * Get Assets
     */
    Route::get('/assets', [CoursesController::class, 'getAssets'])
        ->name('vh.backend.course.api.courses.assets');
    /**
     * Get List
     */
    Route::get('/', [CoursesController::class, 'getList'])
        ->name('vh.backend.course.api.courses.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [CoursesController::class, 'updateList'])
        ->name('vh.backend.course.api.courses.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [CoursesController::class, 'deleteList'])
        ->name('vh.backend.course.api.courses.list.delete');


    /**
     * Create Item
     */
    Route::post('/', [CoursesController::class, 'createItem'])
        ->name('vh.backend.course.api.courses.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [CoursesController::class, 'getItem'])
        ->name('vh.backend.course.api.courses.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [CoursesController::class, 'updateItem'])
        ->name('vh.backend.course.api.courses.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [CoursesController::class, 'deleteItem'])
        ->name('vh.backend.course.api.courses.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [CoursesController::class, 'listAction'])
        ->name('vh.backend.course.api.courses.list.action');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [CoursesController::class, 'itemAction'])
        ->name('vh.backend.course.api.courses.item.action');



});
