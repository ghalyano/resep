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

        if (Auth::attempt($credentials)) 
        {
            // Authentication passed...
            return response()->json([
                'pesan' => 'sukses',
                'data' => Auth::user()
            ]);
        } 
        else 
        {
            return response()->json([
                'pesan' => 'gagal'  
            ]);
        }
    }

    public function register(Request $r)
    {
        // return 'dsdsd';
        $user_ketemu=Users::where('username',$r->username)->get();
        if (!$user_ketemu->isEmpty())
        {
             return response()->json([
                'pesan' => 'Username sudah digunakan'  
            ]);
        }

        $email_ketemu=Users::where('email', $r->email)->get();
        if (!$email_ketemu->isEmpty())
        {
            return response()->json([
                'pesan' => 'Email sudah digunakan'  
            ]);
        }

        if (strlen($r->password) < 8)
        {
            return response()->json([
                'pesan' => 'Password minimal 8 karakter'  
            ]);
        }

        $user=new Users;
        // $user = User::findOrFail()
        $user->username=$r->username;
        $user->nama=$r->nama;
        $user->email=$r->email;
        $user->password=Hash::make($r->password);
        $user->foto=null;
        $user->bio=null;
        $user->save();
        return response()->json([
            'pesan' => 'sukses'  
        ]);
    }

    public function profil (Request $r)
    {
        $profil_saya = Users::where('username', $r->username)->first();
        return response()->json($profil_saya);
    }

    public function update_profil(Request $r)
    {
        $user = User::findOrFail($r->username);
        $user->username=$r->username;
        $user->nama=$r->nama;
        $user->email=$r->email;
        $user->password=Hash::make($r->password);
        $user->foto=null;
        $user->bio=null;
        $user->save();
        return response()->json([
            'pesan' => 'sukses'  
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(Users $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit(Users $users)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Users $users)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(Users $users)
    {
        //
    }
}
