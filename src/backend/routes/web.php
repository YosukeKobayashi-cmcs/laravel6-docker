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

Route::get('phpinfo', 'InfoController@phpinfo');
Route::get('codeCoverage/report', 'CodeCoverageController@report');
Route::get('codeCoverage/delete', 'CodeCoverageController@delete');
Route::get('fetchTodos', 'TodoController@fetchTodos');
Route::get('outputCsvBySFtp', 'SFtpController@outputCsvBySFtp');
Route::get('renameSFtpFile', 'SFtpController@renameSFtpFile');
