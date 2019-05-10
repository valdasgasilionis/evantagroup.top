<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {   
    return view('welcome');
});

Route::post('/charge', function (Request $request) {
    // Set your secret key: remember to change this to your live secret key in production
    // See your keys here: https://dashboard.stripe.com/account/apikeys
    \Stripe\Stripe::setApiKey(config("services.stripe.secret"));

    $intent = \Stripe\PaymentIntent::create([
        'amount' => $request->eur.$request->ct,
        'currency' => 'eur',
    ]);
    return view('checkout', [
        'intent' => $intent
    ]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
