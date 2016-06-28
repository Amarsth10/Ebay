<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/time', 'EbayController@Time');
Route::get('/Cat', 'EbayController@GetSel');
Route::post('/AddItem', ['as'=>'AddItem','uses'=>'EbayController@AddItem']);
Route::get('/', function()
{
    $products= Products::all();
    return View::make('products.index',['products'=> $products]);

});
Route::post('register','UsersController@store');
Route::post('add','ProductController@store');
Route::get('users', function()
{
    $users= User::all();
    return View::make('users.index',['users'=> $users]);
});
Route::get('products', function()
{
    return View::make('products.create');
});


Route::get('addItem','EbayController@addItem');