<?php

use App\Keyword;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::match(['get', 'post'], '/botman', 'BotManController@handle');

Route::get('expression', function () {
    $expression = " wiche time";
    foreach (explode(" ", $expression) as $wrd) {
        if ($keyword = Keyword::where("value", trim($wrd))->first())
            dd($keyword->expression);
    }
    return new Keyword();
});

// Route::get('seed', function () {
//     $keywrds = ["career", "job", "work", "resume", "intership", "stage", "cv"];
//     foreach ($keywrds as $keywrd) {
//         Keyword::create([
//             "value" => $keywrd, "expression_id" => 5
//         ]);
//     }
// });
