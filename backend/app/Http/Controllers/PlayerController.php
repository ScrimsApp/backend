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

    public function update(Request $request){

        $dados = $request->all();
        $user = auth()->user();

        $user->name = $request->name;
        
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
        $user->save();
        return response()->json(['mensagem' => 'Usuário atualizado com sucesso!'], 200);

    }
}
