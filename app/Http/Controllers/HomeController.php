<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\ConfirmNewUserEmail;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (session('newuser')) {
            $email = session('newuser');
            $user_collection = User::where('email', $email)->get();
            $user = $user_collection[0];
            Mail::to('valdasgasilionis@yahoo.com')->send(new ConfirmNewUserEmail($user)); 
        }        
        
        return view('home');
    }
}
