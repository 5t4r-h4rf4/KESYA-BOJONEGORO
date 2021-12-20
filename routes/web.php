<?php

use App\Http\Livewire\MapIndex;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\MapLocation;

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

Route::get('/map', MapLocation::class)->name('map.create');
Route::get('/coba', [MapLocation::class, 'coba'])->name('coba');
Route::get('/', MapIndex::class)->name('map.index');
