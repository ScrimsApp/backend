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

        return $user;
    }

    public function getPlayers(){

        $users = User::paginate(8);

        return $users;
    }
}
