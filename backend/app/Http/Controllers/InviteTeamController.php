<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\InviteTeam;

class InviteTeamController extends Controller
{
    
    public function index(){

        return response()->json(['message' => 'teste']);
    }

    public function invitePlayer(Request $request){
        
        $user_logado = auth()->user();
        
        
        if($this->verifyCaptain()){
            $invite = InviteTeam::create([
                "type" => $request->type,
                "status" => 1,
                "team_id" => $user_logado->team_id,
                "user_id" => $request->user_id
            ]);
            if($invite->save()) {
                $return = ['message' => "Successfully invited player!"];
            }else{ 
                $return = ['message' => 'Error inviting the player to the team!'];
            }
            return response()->json($return);
        }else{
            return response()->json(['message' => "You need to be the captain to invite a player!"]);
        }
    }

    public function inviteTeam(Request $request){

        $user_logado = auth()->user();

        if($user_logado->team_id != null){ return response()->json(['message' => "You cannot join another team, if you are already in one!"]); }

        $invite = InviteTeam::create([
            "type" => $request->type,
            "status" => 1,
            "team_id" => $request->team_id,
            "user_id" => $user_logado->id
        ]);
        if($invite->save()) {
            $return = ['message' => "Success in sending team invitations!"];
        }else{ 
            $return = ['message' => 'Error sending team invitation!'];
        }
        return response()->json($return);
    }

    public function acceptInviteTeam(Request $request){
        
        $user_logado = auth()->user();
        $invite = InviteTeam::find($request->invite_id);
        $team = Team::find($request->team_id);
        $invite->status = 2;
        
        $user_logado->team_id = $team->id;
        if($user_logado->save()) {
            $invite->save();
            $return = ['message' => "Invitation successfully accepted!"];
        }else{ 
            $return = ['message' => 'Error accepting invitation!'];
        }
        return response()->json($return);
        
    }

    public function declineInviteTeam(Request $request){
        
        $user_logado = auth()->user();
        $invite = InviteTeam::find($request->invite_id);
        $invite->status = 2;
        $invite->save();
        $return = ['message' => "Invite declined with successfully!"];
        return response()->json($return);
    }

    public function acceptInvitePlayer(Request $request){
        
        $user_logado = auth()->user();
        if($this->verifyCaptain()){
            $team = Team::find($user_logado->team_id);
            $invite = InviteTeam::find($request->invite_id);
            
            $user = User::find($request->user_id);
            $user->team_id = $team->id;
            $user->save();
            $invite->status = 2;
            $invite->save();

            return response()->json(['message' => "Invite accepted with successfully!"]);
        }else{
            return response()->json(['message' => "You need to be the captain to accept a player's invitation!"]);
        }

    }

    public function declineInvitePlayer(Request $request){
        
        $user_logado = auth()->user();
        if($this->verifyCaptain()){
            $invite = InviteTeam::find($request->invite_id);
            $invite->status = 3;
            $invite->save();

            return response()->json(['message' => "Invite declined with successfully!"]);
        }else{
            return response()->json(['message' => "You need to be the captain to decline a player's invitation!"]);
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