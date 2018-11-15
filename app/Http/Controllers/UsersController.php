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
        /*echo Hash::make('admin');
        $users=new Users;*/
        $credentials = $r->only('username', 'password');

        if (Auth::attempt($credentials)) 
        {
            // Authentication passed...
            return 'sukses';
        } 
        else 
        {
            return 'gagal';
        }
    }

    public function register(Request $r)
    {
        // return 'dsdsd';
        $user_ketemu=Users::where('username',$r->username)->get();
        if (!$user_ketemu->isEmpty())
        {
            return 'username sudah digunakan';
        }

        $email_ketemu=Users::where('email', $r->email)->get();
        if (!$email_ketemu->isEmpty())
        {
            return 'email sudah digunakan';
        }

        if (strlen($r->password) < 8)
        {
            return 'password minimal 8 karakter';
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
        return 'sukses';
        
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
