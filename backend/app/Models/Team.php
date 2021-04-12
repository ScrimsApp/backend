<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tag', 'image', 'user_id'];

    protected $table = 'teams';

    public function players() {
        return $this->hasMany(User::class);
    }

    public function invites() {
        return $this->hasMany(InviteTeam::class);
    }

    public function getInvitesAtivos($invites){

        $arr_invites = array();
        foreach($invites as $invite){
            $invitado = InviteTeam::find($invite->id);
            $objInvite = [
                "id" => $invitado->id,
                'type' => $invitado->type,
                'status' => $invite->status,
                'team' => $invitado->team,
                'player' => $invitado->user
            ];
            if($invitado->status === 1 && $invitado->type == "team"){
                $arr_invites[] = $objInvite;
            }
        }

        return $arr_invites;
    }

    public function getInvitesAceitos($invites){

        $arr_invites = array();
        foreach($invites as $invite){
            $invitado = InviteTeam::find($invite->id);
            $objInvite = [
                "id" => $invitado->id,
                'type' => $invitado->type,
                'status' => $invite->status,
                'team' => $invitado->team,
                'player' => $invitado->user
            ];
            if($invitado->status === 2 && $invitado->type == "team"){
                $arr_invites[] = $objInvite;
            }
        }

        return $arr_invites;
    }
    public function getInvitesRecusados($invites){

        $arr_invites = array();
        foreach($invites as $invite){
            $invitado = InviteTeam::find($invite->id);
            $objInvite = [
                "id" => $invitado->id,
                'type' => $invitado->type,
                'status' => $invite->status,
                'team' => $invitado->team,
                'player' => $invitado->user
            ];
            if($invitado->status === 3 && $invitado->type == "team"){
                $arr_invites[] = $objInvite;
            }
        }

        return $arr_invites;
    }
}
