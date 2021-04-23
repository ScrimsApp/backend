<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteMatches extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'team_2'];

    protected $table = 'invite_teams';

    public function team_2(){
        return $this->belongsTo(Team::class);
    }
}
