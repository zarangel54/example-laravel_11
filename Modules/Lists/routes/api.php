<?php

use Illuminate\Support\Facades\Route;
use Modules\Lists\Http\Controllers\ListsController;
use Modules\Lists\Http\Controllers\ListContactsItemsController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:api'])->prefix('v1')->group(function () {
    Route::apiResource('lists', ListsController::class)->names('lists');

    // Ruta para agregar contactos a una lista
    Route::post('lists/{list_id}/contacts', [ListContactsItemsController::class, 'store']);

    // Ruta para obtener items de una lista
    Route::get('lists/{list}/items', [ListsController::class, 'getListItems'])->name('lists.items');

    // Rutas para agregar y eliminar múltiples contactos
    Route::post('lists/{list}/contacts', [ListContactsItemsController::class, 'store']);
    Route::delete('lists/{list}/contacts', [ListContactsItemsController::class, 'destroy']);
});