<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\InviteTeam;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    
    public function index(){

        $teams = Team::all();
        for($i = 0; $i < count($teams); $i++){
            $teams[$i]->players; 
            $teams[$i]->invites;
        }
        return $teams;
    }

    public function store(Request $request){ 
        $image = $request->image->store('teams', 'public');
        $data = $request->all();
        if(auth()->user()->team_id != null){ return response()->json(['message' => "It was not possible to create the team, you have or are already in a team!"]); }
        $team = Team::create([
            'name' => $data['name'],
            'tag' => $data['tag'],
            'description' => $data['description'],
            'user_id' => auth()->user()->id,
        ]);
        if($request->image){
            
            $team->image = $image;
        }
        
        if($team->save()) {
            $user = auth()->user();
            $user->team_id = $team->id;
            $user->update();
            $return = ['message' => "Team successfully registered!", 'team_id' => $team->id];
        }else{ 
            $return = ['message' => 'Error when registering the team!'];
        }
        return response()->json($return);
    }

    public function getTeam($id){

        if(Team::find($id)){ 

            $players = Team::find($id)->players;
            $team = Team::find($id);
            $team['players'] = $players;
            return response()->json($team);
        }else{
            return response()->json(['message' => 'Team does not exist!']);
        }
        
    }

    public function getTeamUser(){

        $user = auth()->user();
        if(Team::find($user->team_id)){ 

            $players = Team::find($user->team_id)->players;
            $invites = Team::find($user->team_id)->invites;
            
            $team = Team::find($user->team_id);
            $team['players'] = $players;
            $team['invites'] = $team->getInvitesAtivos($invites);
            return response()->json($team);
        }else{
            return response()->json(['message' => 'Team does not exist!']);
        }
        
    }

    public function update(Request $request){

        if($this->verifyCaptain()){
            $team = Team::find(auth()->user()->team_id);
            if($team){
                $dados_atualizados = $request->all();
                $team->name = $dados_atualizados['name'];
                $team->tag = $dados_atualizados['tag'];
                $team->description = $dados_atualizados['description'];
                if($request->image){
                    //apaga imagem anterior
                    Storage::disk('public')->delete($team->image);
        
                    //cria a imagem;
                    $imagem = $request->image->store('teams', 'public');
        
                    //atualiza o endereço da imagem no banco
                    $team->image = $imagem;
                    $team->save();
                }
                $return = $team->update() ? ['message' => "Team updated successfully!"] : ['message' => 'Error when updating the team!'];
                return response()->json($return);
            }else{ 
                return response()->json(['message' => 'Team does not exist!']);
            }
        }else{
            return response()->json(['message' => "You need to be the captain to update team information!"]);
        }

    }

    public function removePlayer($id){

        if($this->verifyCaptain()){
            if(auth()->user()->id == $id){ return response()->json(['message' => "You cannot expel yourself from your team"]); }
            $user_kickado = User::find($id);

            $user_kickado->team_id = null;
            
            $return = $user_kickado->save() ? ['message' => "Player successfully kicked out!"] : ['message' => 'Error kicking the player!'];
            return response()->json($return);
        }else{
            return response()->json(['message' => "You need to be the captain to kicking the player!"]);
        }       
    }

    public function removeTeam(){

        if($this->verifyCaptain()){
            $team = Team::find(auth()->user()->team_id);
            $players = $team->players;
            if(count($players) > 1){
                return response()->json(['message' => 'Expel all players before deleting the team!']);
            }else{
                Storage::disk('public')->delete($team->image);
                if($team->delete()){
                    $auth_user = auth()->user();
                    $auth_user->team_id = null;
                    $auth_user->update();
                    $return = ['message' => "Team successfully deleted!"];
                }else{
                    $return = ['message' => 'Error when deleting the team!'];
                }
            return response()->json($return);
            }
        }else{
            return response()->json(['message' => "You need to be the captain to deleting the team!"]);
        }   
    }

    public function addPlayer($id){

        if($this->verifyCaptain()){
            $user = User::find($id);
            if($user->team_id == null){
                $user->team_id = auth()->user()->team_id; 
                $user->save();
                $return = $user->save() ? ['message' => "Successfully recruited player!"] : ['message' => 'Error recruiting player!'];
                return response()->json($return);
            }else{
                return response()->json(['message' => "This player is already on another team!"]);
            }
        }else{
            return response()->json(['message' => "You need to be the captain to recruit a player!"]);
        }       
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
