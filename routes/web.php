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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('pertanyaan', 'PertanyaanController@index')->name('pertanyaan.index');
Route::get('pertanyaan/create', 'PertanyaanController@create')->name('pertanyaan.create')->middleware('auth');
Route::post('pertanyaan', 'PertanyaanController@store')->name('pertanyaan.store')->middleware('auth');

Route::get('pertanyaan/{pertanyaan_id}', 'PertanyaanController@show')->name('pertanyaan.show');
Route::get('pertanyaan/{pertanyaan_id}/edit', 'PertanyaanController@edit')->name('pertanyaan.edit')->middleware('auth');
Route::put('pertanyaan/{pertanyaan_id}', 'PertanyaanController@update')->name('pertanyaan.update')->middleware('auth');
Route::delete('pertanyaan/{pertanyaan_id}', 'PertanyaanController@destroy')->name('pertanyaan.destroy')->middleware('auth');

Route::post('pertanyaan/{pertanyaan_id}/upvote', 'PertanyaanController@upvote')
    ->name('pertanyaan.upvote')->middleware('auth');
Route::post('pertanyaan/{pertanyaan_id}/downvote', 'PertanyaanController@downvote')
    ->name('pertanyaan.downvote')->middleware('auth');

Route::post('pertanyaan/{pertanyaan_id}/jawaban', 'JawabanController@store')->name('jawaban.store')->middleware('auth');

Route::post('jawaban/{jawaban_id}/upvote', 'JawabanController@upvote')
    ->name('jawaban.upvote')->middleware('auth');
Route::post('jawaban/{jawaban_id}/downvote', 'JawabanController@downvote')
    ->name('jawaban.downvote')->middleware('auth');

