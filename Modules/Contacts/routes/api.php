<?php

use Illuminate\Support\Facades\Route;
use Modules\Contacts\Http\Controllers\ContactsController;
use Modules\Contacts\Http\Controllers\DynamicAttributeController;

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
    // Rutas para contactos
    Route::apiResource('contacts', ContactsController::class)->names('contacts');
    Route::delete('contacts/{id?}', [ContactsController::class, 'destroy']);

    // Rutas para atributos dinámicos
    Route::apiResource('dynamic-attributes', DynamicAttributeController::class)->names('dynamic-attributes');

    // Ruta para actualizar el valor de un atributo dinámico de un contacto
    Route::put('contacts/{contactId}/dynamic-attributes/{attributeId}', [ContactsController::class, 'updateDynamicAttributeValue']);
    // Asegúrate de tener solo una de estas rutas:
    // Route::post('contacts/{contactId}/dynamic-attributes/{attributeId}', [ContactsController::class, 'updateDynamicAttribute']);
});








