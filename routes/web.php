<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/lmgtfy', function() {

    if (request('token') != env('SLASH_COMMAND_TOKEN')) {
        return json_encode(['text' => 'An error occurred.']);
    }

    $query_text = request('text');

    preg_match('/\@[\w\d\-\_]+/', $query_text, $matches);
    if (!empty($matches)) {
        $target_user = $matches[0];
        $query_text = str_replace($target_user, '', $query_text);
    } else {
        $target_user = '@' . request('user_name');
    }

    $response = [
        'response_type' => 'in_channel',
        'text' => '<' . $target_user . '> http://lmgtfy.com/?q=' . urlencode($query_text),
    ];

    return response()->json($response);
});