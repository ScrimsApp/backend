<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InviteMatches extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'team_2', 'status'];

    protected $table = 'invite_matches';

    public function match(){
        return $this->belongsTo(Match::class);
    }
    public function team_2(){
        return $this->belongsTo(Team::class);
    }
}
