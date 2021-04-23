<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    use HasFactory;

    protected $fillable = ['team_1', 'team_2', 'status', 'format', 'date'];

    protected $table = 'matches';

    public function challenger(){
        return $this->belongsTo(Team::class, 'foreign_key', 'team_1');
    }

    public function oponnent(){
        return $this->belongsTo(Team::class, 'foreign_key', 'team_2');
    }
}
