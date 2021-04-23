<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Match;
use App\Models\Team;
class MatchesController extends Controller
{
    

    public function store(Request $request){

        $dados = $request->all();
        $user = auth()->user();
        if($this->verifyCaptain()){
            $match = Match::create([
                "team_1" => $user->team_id,
                "status" => 1,
                "format" => $dados['format'],
                "date" => $dados['date'],
                "time" => $dados['time']
            ]);
            if($match->save()) {
                $return = ['message' => "Match created successfully!"];
            }else{ 
                $return = ['message' => 'Error creating match!'];
            }
        }else{
            return response()->json(['message' => "You need to be the captain to creating match!"]);
        }

        return response()->json($return);
    }

    public function index(){

        $matches = Match::all();
        for($i = 0; $i < count($matches); $i++){
            $matches[$i]['team_1'] = Team::find($matches[$i]['team_1']);
            $matches[$i]['team_2'] = Team::find($matches[$i]['team_2']);
        }
        return $matches;
    }

    public function getMatch($id){

        $match = Match::find($id);
        
        $players = Team::find($match['team_1'])->players;
        $team = Team::find($match['team_1']);
        $team['players'] = $players;
        $match['team_1'] = $team;

        $team2 = Team::find($match['team_2']);
        $players2 = Team::find($match['team_2'])->players;
        $team2['players'] = $players2;
        $match['team_2'] = $team2;


        return response()->json($match);
    }

    private function format_date_db($date){
        $date = explode('/', $date);
        $new_date = $date[2].'-'.$date[1].'-'.$date[0];
        return $new_date;
    }

    private function format_date_br($date){
        $date = explode('-', $date);
        $new_date = $date[2].'/'.$date[1].'/'.$date[0];
        return $new_date;
    }

    private function verifyCaptain(){
        $auth_user = auth()->user();
        $team = Team::find($auth_user->team_id);
        if($team){
            if($team->user_id == $auth_user->id){
                return true;
            }else{
                return false;
            }
        }
    }
}
