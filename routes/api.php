<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Dictionary;

Route::middleware('auth')->group(function () {
    Route::get('/dictionaries/{dictionary}/items', function (Dictionary $dictionary) {
        if ($dictionary->user_id !== auth()->id() && !$dictionary->is_public) {
            abort(403);
        }
        
        return response()->json($dictionary->items()->select('value')->get());
    });
}); 