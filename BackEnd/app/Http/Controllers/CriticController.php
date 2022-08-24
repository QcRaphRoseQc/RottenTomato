<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Actor_film;
use App\Models\Actor;
use App\Models\Critic;
use Illuminate\Support\Facades\Auth;

class CriticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $critics = Critic::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //get the user_id
        $user_id = Auth::user()->id;
        



        //get the First_name and Last_name from table user where user_id = $user_id
        $first_name = Auth::user()->first_name;
        $last_name = Auth::user()->last_name;
        $login = Auth::user()->login;

        

        //check if the film exists if it does not return an error
        $film = Film::where('id', $request->film_id)->first();
        if(!$film){
            return response()->json([
                'status' => 'Error',
                'message' => 'Film does not exist'
            ], 404);
        }

        if($request->score < 1 || $request->score > 5){
            return response()->json([
                'status' => 'Error',
                'message' => 'Score must be between 1 and 5'
            ], 400);
        }
        

        $critic = Critic::where('user_id', $user_id)->where('film_id', $request->film_id)->first();
        //check if admin
        $role_id = Auth::user()->role_id;
        if($critic && $role_id == 2){
            return response()->json([
                'status' => 'Error',
                'message' => 'You have already criticed this film'
            ], 400);
        }

        $critic = Critic::create([ 
            'user_id' => $user_id,
            'film_id' => $request->film_id,
            'score' => $request->score,
            'comment' => $request->comment,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'login' => $login
        ]);
        //calculate the average score of the film
        $film_id = $request->film_id;
        $critics = Critic::where('film_id', $film_id)->get();
        $sum = 0;
        foreach($critics as $critic){
            $sum += $critic->score;
        }
        $average = $sum / count($critics);
        $film = Film::find($film_id);
        $film->score = $average;
        $film->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Critic created',
            'data' => $critic,
            'average' => $average
        ], 201);


        



        
           
           
       
             
    }

    //delete a critic
    public function destroy($id)
    {
        $critic = Critic::find($id);
        if(!$critic){
            return response()->json([
                'status' => 'Error',
                'message' => 'Critic does not exist'
            ], 404);
        }
        $critic->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Critic deleted'
        ], 200);
    }

    //last afficher les 5 derniers critiques
    //Route::get('films/critics/last/{id}','App\Http\Controllers\FilmController@last');
    public function last($id)
    {
        
        //what would be the sql query to get the last 5 critics of a film
        //select * from critics where film_id = $id order by id desc limit 5
        $critics = Critic::where('film_id', $id)->orderBy('id', 'desc')->limit(5)->get();

        //return in order 
        if(!$critics){
            return response()->json([
                'status' => 'Error',
                'message' => 'No critics found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Critics found',
            'data' => $critics
        ], 200);
    }

    //update
    public function update(Request $request, $id)
    {

        //make sure all the fields are filled in
        $this->validate($request, [
            'score' => 'required',
            'comment' => 'required'
        ]);



        $critic = Critic::find($id);
        if(!$critic){
            return response()->json([
                'status' => 'Error',
                'message' => 'Critic does not exist'
            ], 404);
        }
        $critic->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Critic updated'
        ], 200);
    }
}


   


