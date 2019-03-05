<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $r)
    {
        $like = Like::where(['username' => $r->username, 'id_resep' => $r->id_resep])->first();
        if($like)
        {
            $like->delete();
            return response()->json([
                'pesan' => 'sukses'  
            ]);
        }

        $like = new Like;
        $like->username = $r->username;
        $like->id_resep = $r->id_resep;
        $like->save();
        return response()->json([
            'pesan' => 'sukses'  
        ]);
    }
}
