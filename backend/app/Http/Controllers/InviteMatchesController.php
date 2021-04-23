<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InviteMatchesController extends Controller
{
    
    public function createInvite(Request $request){

        $data = $request->all();

        $auth_user = auth()->user();
        if($this->verifyCaptain()){
           $inviteMatch = InviteMatches::create([
               "match_id" => $data['match_id'],
               "team_2" => $auth_user->team_id,
               "status" => 1
           ]);

           if($inviteMatch->save()) {
               $return = ['message' => "Invite match created successfully!"];
           }else{ 
               $return = ['message' => 'Error creating match inviting!'];
           }
        }else{
           return response()->json(['message' => "You need to be the captain to creating match inviting!"]);
        }
        
    }

    public function acceptInvite(Request $request){
        $data = $request->all();

        $auth_user = auth()->user();

        if($this->verifyCaptain()){
            $inviteMatch = InviteMatches::find($data['invite_id']);
            $inviteMatch->status = 2;

            $match = Match::find($inviteMatch->match_id);
            $match->team_2 = $inviteMatch->team_2;
            $match->status = 2;
            if($inviteMatch->save()) {
                $inviteMatch->update();
                return response()->json(['message' => "Invite accepted with successfully!"]);
            }else{
                return response()->json(['message' => "Invite not accepted!"]);
            }
        }else{
            return response()->json(['message' => "You need to be the captain to creating match!"]);
        }
    }

    public function declineInvite(Request $request){
        
        $user_logado = auth()->user();
        if($this->verifyCaptain()){
            $invite = InviteMatches::find($request->invite_id);
            $invite->status = 3;
            $invite->save();

            return response()->json(['message' => "Invite declined with successfully!"]);
        }else{
            return response()->json(['message' => "You need to be the captain to decline a matches invitation!"]);
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
