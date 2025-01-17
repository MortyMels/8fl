<?php

use App\Http\Controllers\FormFieldController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormManagerController;
use Illuminate\Support\Facades\Route;

// Маршруты аутентификации
Auth::routes();

Route::get('/', function () {
    return redirect()->route('forms.index');
});

// Маршруты для управления формами
Route::resource('forms', FormManagerController::class);

// Маршруты для полей формы
Route::get('forms/{form}/fields', [FormFieldController::class, 'index'])->name('forms.fields.index');
Route::post('forms/{form}/fields', [FormFieldController::class, 'store'])->name('forms.fields.store');
Route::get('forms/{form}/fields/{field}/edit', [FormFieldController::class, 'edit'])->name('forms.fields.edit');
Route::put('forms/{form}/fields/{field}', [FormFieldController::class, 'update'])->name('forms.fields.update');
Route::delete('forms/{form}/fields/{field}', [FormFieldController::class, 'destroy'])->name('forms.fields.destroy');

// Маршруты для просмотра и отправки форм
Route::get('forms/{form}/show', [FormController::class, 'show'])->name('forms.show');
Route::post('forms/{form}/submit', [FormController::class, 'submit'])->name('forms.submit');
Route::get('forms/{form}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');
