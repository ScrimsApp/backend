<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'email',
        'password',
        'team_id',
        'description',
        'image',
        'person_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }  


    public function team(){
        return $this->belongsTo(Team::class);
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
            if($invitado->status === 1 && $invitado->type == "player"){
                $arr_invites[] = $objInvite;
            }
        }

        return $arr_invites;
    }
}
