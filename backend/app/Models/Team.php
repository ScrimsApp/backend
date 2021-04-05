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
}
