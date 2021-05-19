<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\InviteTeam;

class PlayerController extends Controller
{
    public function getPlayer(){

        $user = auth()->user();
        $invites = $user->invites;
        $user['invites'] = $user->getInvitesAtivos($invites);
        $user['team'] = $user->team;

        return $user;
    }
    
    public function getPlayerId($id){
        $user = User::find($id);

        $objUser = [
            'name' => $user['name'],
            'person_id' => $user['person_id'],
            'team_id' => $user['team_id'],
            'image' => $user['image']
        ];
        return $objUser;

    }

    public function getPlayers(){

        $users = User::orderBy('id', 'desc')->paginate(8);

        return $users;
    }
}
