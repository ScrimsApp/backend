<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\InviteTeam;
use Illuminate\Support\Facades\Storage;

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
            'description' => $user['description'],
            'person_id' => $user['person_id'],
            'team_id' => $user['team_id'],
            'image' => $user['image'],
            'created_at' => $user['created_at'],
        ];
        return $objUser;

    }

    public function getPlayers(){

        $users = User::query()
                          ->select('users.id', 'users.name', 'users.image', 'users.created_at', 'users.person_id')
                          ->orderBy('id', 'desc')
                          ->paginate(8);
                                                // users.description
        return $users;
    }

    public function update(Request $request){

        $user = auth()->user();
        if($user){
            $user->name = $request->name;
            $user->description = $request->description;
            if($user->email != $request->email){
                $user->email = $request->email;
                $user->email_verified_at = null;
            }
            if($request->password){
                $user->password = Hash::make($request->password);
            }
        
            if($request->image){
                //apaga imagem anterior
                Storage::disk('public')->delete($user->image);
            
                //cria a imagem;
                $imagem = $request->image->store('players', 'public');
            
                //atualiza o endereço da imagem no banco
                $user->image = "http://localhost:8000/storage/" . $imagem;
            }
    
            $return = $user->update() ? ['message' => "User updated successfully!"] : ['message' => 'Error when updating the user!'];
            return response()->json($return);
        }else{ 
            return response()->json(['message' => 'User does not exist!'], 404);
        }

    }
}
