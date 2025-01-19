<?php

use App\Http\Controllers\FormFieldController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormManagerController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\FormTestDataController;
use App\Http\Controllers\FormImportExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Dictionary;

// Маршруты аутентификации
Auth::routes();

Route::get('/', function () {
    return redirect()->route('forms.index');
});

Route::middleware(['auth'])->group(function () {
    // Маршруты для управления формами
    Route::get('forms', [FormManagerController::class, 'index'])->name('forms.index');
    Route::get('forms/create', [FormManagerController::class, 'create'])->name('forms.create');
    Route::post('forms', [FormManagerController::class, 'store'])->name('forms.store');
    Route::get('forms/{form}/edit', [FormFieldController::class, 'index'])->name('forms.edit');
    Route::put('forms/{form}', [FormManagerController::class, 'update'])->name('forms.update');
    Route::delete('forms/{form}', [FormManagerController::class, 'destroy'])->name('forms.destroy');

    // Маршруты для полей формы
    Route::get('forms/{form}/fields', [FormFieldController::class, 'index'])->name('forms.fields.index');
    Route::get('forms/{form}/fields/create', [FormFieldController::class, 'create'])->name('forms.fields.create');
    Route::post('forms/{form}/fields', [FormFieldController::class, 'store'])->name('forms.fields.store');
    Route::get('forms/{form}/fields/{field}/edit', [FormFieldController::class, 'edit'])->name('forms.fields.edit');
    Route::put('forms/{form}/fields/{field}', [FormFieldController::class, 'update'])->name('forms.fields.update');
    Route::delete('forms/{form}/fields/{field}', [FormFieldController::class, 'destroy'])->name('forms.fields.destroy');

    // Маршруты для просмотра и отправки форм
    Route::get('/forms/{form}', [FormController::class, 'show'])->name('forms.show');
    Route::post('/forms/{form}/submit', [FormController::class, 'submit'])->name('forms.submit');
    Route::get('forms/{form}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');

    // Маршруты для справочников
    Route::resource('dictionaries', DictionaryController::class);

    // Остальные маршруты...

    // Маршрут для получения элементов справочника
    Route::get('/api/dictionaries/{dictionary}/items', function (Dictionary $dictionary) {
        if (!$dictionary || ($dictionary->user_id !== auth()->id() && !$dictionary->is_public)) {
            return response()->json([]);
        }
        
        try {
            $items = $dictionary->items()
                ->select('value')
                ->orderBy('value')
                ->get()
                ->pluck('value')
                ->toArray();
                
            return response()->json($items);
        } catch (\Exception $e) {
            \Log::error('Error getting dictionary items: ' . $e->getMessage());
            return response()->json([]);
        }
    })->name('api.dictionary.items');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Добавим маршрут для проверки уникальности
Route::get('/api/forms/{form}/fields/check-unique', function (Request $request, Form $form) {
    $field = $request->query('field');
    $value = $request->query('value');
    
    $exists = $form->fields()
        ->where($field, $value)
        ->exists();
    
    return response()->json([
        'unique' => !$exists
    ]);
})->name('forms.fields.check-unique');

// Маршруты для скачивания результатов
Route::post('forms/{form}/download/csv', [FormController::class, 'downloadCsv'])->name('forms.download.csv');
Route::post('forms/{form}/download/xlsx', [FormController::class, 'downloadXlsx'])->name('forms.download.xlsx');
Route::post('forms/{form}/download/template', [FormController::class, 'downloadTemplate'])->name('forms.download.template');

// Маршруты для генерации тестовых данных
Route::get('forms/{form}/test-data', [FormTestDataController::class, 'show'])->name('forms.test-data');
Route::post('forms/{form}/test-data/generate', [FormTestDataController::class, 'generate'])->name('forms.test-data.generate');

// Добавим новый маршрут для удаления результатов
Route::delete('forms/{form}/submissions', [FormController::class, 'deleteSubmissions'])
    ->name('forms.submissions.delete');

// Маршрут для копирования формы
Route::post('forms/{form}/copy', [FormController::class, 'copy'])->name('forms.copy');

// Маршруты для импорта/экспорта формы
Route::get('forms/{form}/export', [FormImportExportController::class, 'export'])->name('forms.export');
Route::post('forms/import', [FormImportExportController::class, 'import'])->name('forms.import');

// Маршруты для экспорта/импорта справочников
Route::get('dictionaries/{dictionary}/export', [DictionaryController::class, 'export'])
    ->name('dictionaries.export');
Route::post('dictionaries/import', [DictionaryController::class, 'import'])
    ->name('dictionaries.import');

// Временный маршрут для отладки
Route::get('forms/{form}/debug', [FormController::class, 'debug']);
