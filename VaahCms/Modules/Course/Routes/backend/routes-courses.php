<?php

use VaahCms\Modules\Course\Http\Controllers\Backend\CoursesController;

Route::group(
    [
        'prefix' => 'backend/course/courses',
        
        'middleware' => ['web', 'has.backend.access'],
        
],
function () {
    /**
     * Get Assets
     */
    Route::get('/students-autocomplete', [CoursesController::class, 'autocompleteStudents'])
    ->name('vh.backend.course.courses.students.autocomplete');
     /**
     * Auto complete route
     */
    Route::get('/assets', [CoursesController::class, 'getAssets'])
        ->name('vh.backend.course.courses.assets');
    /**
     * Get List
     */
    Route::get('/', [CoursesController::class, 'getList'])
        ->name('vh.backend.course.courses.list');
    /**
     * Update List
     */
    Route::match(['put', 'patch'], '/', [CoursesController::class, 'updateList'])
        ->name('vh.backend.course.courses.list.update');
    /**
     * Delete List
     */
    Route::delete('/', [CoursesController::class, 'deleteList'])
        ->name('vh.backend.course.courses.list.delete');


    /**
     * Fill Form Inputs
     */
    Route::any('/fill', [CoursesController::class, 'fillItem'])
        ->name('vh.backend.course.courses.fill');

    /**
     * Create Item
     */
    Route::post('/', [CoursesController::class, 'createItem'])
        ->name('vh.backend.course.courses.create');
    /**
     * Get Item
     */
    Route::get('/{id}', [CoursesController::class, 'getItem'])
        ->name('vh.backend.course.courses.read');
    /**
     * Update Item
     */
    Route::match(['put', 'patch'], '/{id}', [CoursesController::class, 'updateItem'])
        ->name('vh.backend.course.courses.update');
    /**
     * Delete Item
     */
    Route::delete('/{id}', [CoursesController::class, 'deleteItem'])
        ->name('vh.backend.course.courses.delete');

    /**
     * List Actions
     */
    Route::any('/action/{action}', [CoursesController::class, 'listAction'])
        ->name('vh.backend.course.courses.list.actions');

    /**
     * Item actions
     */
    Route::any('/{id}/action/{action}', [CoursesController::class, 'itemAction'])
        ->name('vh.backend.course.courses.item.action');

    //---------------------------------------------------------
   

});
