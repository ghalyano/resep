<?php

namespace App\Http\Controllers;

use App\Users;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Hash;
use Auth;

class UsersController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $r)
    {
        $credentials = $r->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user_info = Users::find($r->username);
            $user_info->token_login = str_random(32);
            $user_info->save();

            return response()->json([
                'pesan' => 'sukses',
                'data' => $user_info
            ]);
        } else {
            return response()->json([
                'pesan' => 'gagal'
            ]);
        }
    }

    public function register(Request $r)
    {
        // return 'dsdsd';
        $user_ketemu = Users::where('username', $r->username)->get();
        if (!$user_ketemu->isEmpty()) {
            return response()->json([
                'pesan' => 'Username sudah digunakan'
            ]);
        }

        $email_ketemu = Users::where('email', $r->email)->get();
        if (!$email_ketemu->isEmpty()) {
            return response()->json([
                'pesan' => 'Email sudah digunakan'
            ]);
        }

        if (strlen($r->password) < 8) {
            return response()->json([
                'pesan' => 'Password minimal 8 karakter'
            ]);
        }

        $user = new Users;
        // $user = User::findOrFail()
        $user->username = $r->username;
        $user->nama = $r->nama;
        $user->email = $r->email;
        $user->password = Hash::make($r->password);
        $user->foto = null;
        $user->bio = null;
        $user->token_login = str_random(32);
        $user->save();
        return response()->json([
            'pesan' => 'sukses',
            'data' => $user
        ]);
    }

    public function profil(Request $r)
    {
        $profil_saya = Users::where('username', $r->username)->with(['resep' => function ($query) {
            $query->orderBy('waktu_post', 'desc');
            $query->with('kategori');
            $query->withCount(['komentar', 'like']);
        }])->first();
        // $reseps = $profil_saya->resep;
        return response()->json([
            'pesan' => 'sukses',
            'data' => $profil_saya,

        ]);
    }

    public function update_profil(Request $r)
    {
        $user = Users::where('token_login', $r->token_login)->first();
        // $user->username=$r->username;
        $user->nama = $r->nama;
        $user->email = $r->email;
        // $user->password=Hash::make($r->password);
        // TODO set set change picture later
        // $user->foto=null;
        $user->bio = $r->bio;

        if ($user->save()) {
            return 'sukses';
        } else {
            return 'gagal';
        }
    }

    public function ganti_password(Request $r)
    {
        $user = Users::where('token_login', $r->token_login)->first();
        $pass_sama = Hash::check($r->password_lama, $user->password);
        if ($pass_sama) {
            $user->password = Hash::make($r->password_baru);
            $user->save();
            return "sukses";
        } else {
            return "password berbeda";
        }
    }
}
