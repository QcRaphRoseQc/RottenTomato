<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Actor_film;
use App\Models\Actor;
use App\Models\Critic;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $films = Film::all();

        //calculate the average score of the film

        
        // $critics = Critic::where('film_id', $film_id)->get();
        // $sum = 0;
        // foreach($critics as $critic){
        //     $sum += $critic->score;
        // }
        // $average = $sum / count($critics);
        // $film = Film::find($film_id);
        // $film->score = $average;
        // $film->save();

        foreach($films as $film){
            $critics = Critic::where('film_id', $film->id)->get();
            $sum = 0;

            //check how many critics have rated the film if less than 5 critic-> score is 0
            if(count($critics) < 5){
                $film->score = 0;
            }else{
                foreach($critics as $critic){
                    $sum += $critic->score;
                }
                $average = $sum / count($critics);
                $film->score = $average;
            }
            $film->save();
        }


        //order by id and return the films array
        $films = Film::orderBy('id', 'asc')->get();


        

        return response()->json([
            'status' => 'success',
            'message' => 'Liste Des Films',
            'data' => $films,
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $role_id = Auth::user()->role_id;        

        if($role_id == 2){
            return response()->json([
                'status' => 'Error',
                'message' => 'Only administrators can create films'
            ], 401);
        }

        else {
            $film = Film::create($request->all());

            //create a new actor_film for each actor in the request
            $actors = $request->actors;
            foreach($actors as $actor){
                $actor_film = new Actor_film();
                $actor_film->film_id = $film->id;
                $actor_film->actor_id = $actor;
                $actor_film->save();
            }
            
            
            return response()->json([
                'status' => 'success',
                'message' => 'Film created',
                'data' => $film,
                'actors' => $actors,
            ], 201);

           

           
        

            
        
        }    
                   


            return response()->json([
                'status' => 'success',
                'message' => 'Film created',
                'data' => $film
            ], 201);
        


    }

    public function destroy(Request $request, $id){
        $role_id = Auth::user()->role_id;

        if($role_id == 2){
            return response()->json([
                'status' => 'Error',
                'message' => 'Only administrators can delete films'
            ], 401);
        }

        if(!Film::find($id)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Film not found'
            ], 404);
        }

        

        else {
            $film = Film::find($id);
            $film->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Film deleted',
                'data' => $film
            ], 200);
        }
    }

    public function actors(Request $request, $id){
        if(!Film::find($id)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Film not found'
            ], 404);
        }

        else {
            
            $actors = Actor_film::where('film_id', $id)->get();
            $actors_name = [];
            foreach($actors as $actor){
                $actors_name[] =Actor::find($actor->actor_id)-> first_name . ' ' . Actor::find($actor->actor_id)->last_name;
            }
                        
            return $actors_name;
        }
    }


    public function search(Request $request){

        $keywords = $request->keywords;        
        
        //toupper
        $keywords = strtoupper($keywords);

        if(!$keywords){
            $films = Film::all();
            return $films;
        }
        
        

        $films = Film::where(Film::raw('UPPER(title)'), 'like', '%'.$keywords.'%')->orWhere(Film::raw('UPPER(description)'), 'like', '%'.$keywords.'%')->get(); 
        
        foreach($films as $film){
            $film->critics = Critic::where('film_id', $film->id)->get();
        }


        //order by release_year desc without id

        foreach($films as $film){
            $actors = Actor_film::where('film_id', $film->id)->get();
            $actors_name = [];
            foreach($actors as $actor){
                $actors_name[] =Actor::find($actor->actor_id)-> first_name . ' ' . Actor::find($actor->actor_id)->last_name;
            }
            $film->actors = $actors_name;
        }


        $films = $films->sortByDesc('release_year')->values()->all();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Films found',
            'data' => $films
        ], 200);

    }

    public function show($id){
        if(!Film::find($id)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Film not found'
            ], 404);
        }

        else {

            $film = Film::find($id);

            $critics = Critic::where('film_id', $id)->get();

            //order by 

            return response()->json([
                'status' => 'success',
                'message' => 'Film found',
                'data' => $film,
                'critics' => $critics
            ], 200);
        }
    }

    // Afficher les 3 derniers films ajouter
    public function last(){
        $films = Film::orderBy('id', 'desc')->take(3)->get();

        //pour chaque film retourner les acteurs
        foreach($films as $film){
            $actors = Actor_film::where('film_id', $film->id)->get();
            $actors_name = [];
            foreach($actors as $actor){
                $actors_name[] =Actor::find($actor->actor_id)-> first_name . ' ' . Actor::find($actor->actor_id)->last_name;
            }
            $film->actors = $actors_name;
        }

        return $films;
    }

    //afficher un film
    public function showFilm($id){
        $film = Film::find($id);
        return $film;
    }

    //getActors retourner tout les acteurs dans la table actor_film
    public function getActors(){
        
        $actors = Actor::all();

        return $actors;
    }
}