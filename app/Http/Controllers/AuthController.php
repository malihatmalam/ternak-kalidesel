<?php

namespace App\Http\Controllers;
use Auth;
use App\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {

        return view('manajemen.login.login');
    }


    public function registrasi()
    {

        return view('manajemen.login.regristasi');
    }

    public function registrasiDoctor()
    {

        return view('manajemen.login.regristasi-doctor');
    }

    public function postregistrasi(Request $request)
    {  
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();
    

        return redirect('/manager');
    }

    public function postlogin(Request $request)
    {

        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',

        ]);

        if(Auth::attempt($request->only('email','password'))){
            if (Auth::user()->role == 2) {
                return redirect(route('diagnosis.index.doctor'));
            }
            return redirect('/manager');
        }

        return redirect('/manager/login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/manager/login');
    }



}
