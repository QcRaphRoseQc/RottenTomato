<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Consultation des films (sans critiques et sans acteurs) 
Route::get('films','App\Http\Controllers\FilmController@index');
//• Consultation de tous les acteurs d’un certain film
Route::get('films/actors/{id}','App\Http\Controllers\FilmController@actors');
//o Consultation d’un certain film avec ces critiques
Route::get('films/critics/{id}','App\Http\Controllers\FilmController@show');

//retourner la liste de tous les acteurs
Route::get('films/actors','App\Http\Controllers\FilmController@getActors');

//voir les 5 dernieres critiques pour un film
Route::get('critics/last/{id}','App\Http\Controllers\CriticController@last');
//voir les tout les critiques sauf elle du user
Route::get('critics/{id}','App\Http\Controllers\CriticController@show');
//• Recherche de films
Route::post('films/search','App\Http\Controllers\FilmController@search');
// Afficher les 3 derniers films ajouter
Route::get('films/last','App\Http\Controllers\FilmController@last');
//afficher un film
Route::get('films/{id}','App\Http\Controllers\FilmController@show');


//Les routes protegées sont accessible uniquement aux utilisateurs authentifiés
Route::group(['middleware' => ['auth:sanctum']], function() {
    //Consultation des informations d’un certain user (seulement si on est connecté avec ce user)
    Route::get('users/{id}', 'App\Http\Controllers\UserController@show');
    //Déconnexion d’un user
    Route::post('users/logout', 'App\Http\Controllers\UserController@logout');
    //o Modification d’un user existant (seulement si on est connecté avec ce user)
    Route::put('users', 'App\Http\Controllers\UserController@update');
    //▪ Traiter le mot de passe séparément
    Route::put('users/password', 'App\Http\Controllers\UserController@updatePassword');
    //Ajout d’un film (seulement si admin)
    Route::post('films','App\Http\Controllers\FilmController@store');
    //Suppression d’un film (seulement si admin)
    Route::delete('films/{id}','App\Http\Controllers\FilmController@destroy');
    //• Ajout d’une critique (seulement si membre connecté)
    Route::post('critics', 'App\Http\Controllers\CriticController@store');

    //• Modification d’une critique (seulement si membre connecté)
    Route::put('critics/{id}', 'App\Http\Controllers\CriticController@update');

    //get users info id
    Route::get('users/info/{id}', 'App\Http\Controllers\UserController@showInfo');
    
});


//Ajout d’un nouveau user
Route::post('users/register', 'App\Http\Controllers\UserController@register');
//Connexion d’un user
Route::post('users/login', 'App\Http\Controllers\UserController@login');
