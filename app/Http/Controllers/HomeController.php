<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\ConfirmNewUserEmail;

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
            Mail::to('valdasgasilionis@yahoo.com')->send(new ConfirmNewUserEmail($email)); 
        }        
        
        return view('home');
    }
}
