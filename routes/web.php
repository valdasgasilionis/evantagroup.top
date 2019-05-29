<?php

use Illuminate\Http\Request;
use App\Rental;
use App\User;
use App\Notifications\Webhook;
use App\Mail\PaymentReceived;
use App\Mail\RentPaidMail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
  
     $vartotojai = User::all();
    /*  dd($users); */
 
    return view('home', ['vartotojai' => $vartotojai]);
});

/* Route::post('/verify_user', function(Request $request) {
    $user_id = $request->id;
    $user = User::find($user_id);
    $user->email_verified_at = now();
    $user->update();

    return back();
}); */


Route::post('/charge', function (Request $request) {
//first let's check if reserved status is still - available
    $rent_id_number = $request->id;
    $rental = Rental::find($rent_id_number);
    if ($rental->reserved === 0) {        
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey(config("services.stripe.secret"));

        $intent = \Stripe\PaymentIntent::create([
            'amount' => $request->eur.$request->ct,
            'currency' => 'eur',
            'metadata' => ['rent_id' => $request->id]
        ]);
        /* $id_number = $request->id; */

        return view('checkout', [
            'intent' => $intent,
            'id' => $request->id
    ]);
    } else {
        return redirect('/booked');
    }
    
});

Route::get('/rentals', function() {
    $rentals = Rental::all();
    return view('index', [
        'rentals'=> $rentals
    ])->middleware('verify');
});
//create new rental instance
Route::post('/rentals', function(Request $request) {
    $rental = new Rental;
    $rental->start = $request->start;
    $rental->end = $request->end;
    $rental->price = $request->price;

    $rental->save();

    return redirect('/rentals');
});
//modify existing rental item
Route::post('/modify', function(Request $request) {
    $rent_id = $request->id;
    $rental = Rental::find($rent_id);
    $rental->start = $request->start;
    $rental->end = $request->end;
    $rental->price = $request->price;

    $rental->update();

    return redirect('/rentals');
});
//delete rental completely
Route::post('delete', function(Request $request) {
    $rent_id = $request->id;
    $rental = Rental::find($rent_id);
    $rental->delete();

    return redirect('/rentals');
});
Route::get('/rentals/{id}/edit', function($id) {
    $item = Rental::find($id);
    if ($item->reserved === 0) {
        return view('edit', [
        'item' =>  $item
    ]);
    } else {
        return redirect('/booked');
    }
    
    
});
//modify rent item - only admin access
Route::get('/rentals/{id}/modify', function($id) {
    $rent = Rental::find($id);
    return view('modify', [
        'rent' => $rent
    ]);
});
Route::post('/rentals/{id}/finalize', function($id) {
    $case = Rental::find($id);
    $case->paid = 1;
    $case->save();

    return back();
});
//webhook testing STRIPE
Route::post('/webhook', function(Request $request) {
    // Retrieve the request's body and parse it as JSON:
    $request = @file_get_contents('php://input');
    $event_json = json_decode($request, true);
    $id_number = $event_json["data"]["object"]["metadata"]["rent_id"]; // this is how to access rent id number
    $webhook_id = $event_json["id"];

    // Do something with $event_json
        //find rental id number from webhook data
        //insert webhook id into notes collumn;
        //update status to reserved ->yes;
    $rental = Rental::find($id_number);
    $rental->notes = $webhook_id;
    $rental->reserved = 1;
    $rental->save(); 

    
        //create notification -> now it is stored in notifications table only;
    $user = App\User::find(1);
    $user->notify(new Webhook($id_number));
//send email to admin about successful payment
Mail::to('ooto@simulator.amazonses.com')->send(new RentPaidMail($rental));
    
    http_response_code(200);
			/* 	return back()->with('success','Message has been sent!');
			} catch (Exception $e) {
				return back()->with('error','Message could not be sent.');
			} */	
    /* MAIL::to('gasilionisvaldas@gmail.com')->send(new PaymentReceived($request)); */
    // Return a response to acknowledge receipt of the event
    // PHP 5.4 or greater
    
    });

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function() { 
    return view('test');
});
Route::get('/booked', function() {
    return view('booked');
});
// AJAX testing
/* Route::get('ajax', function() {
   
    return view('ajax'); 
}); */
Route::post('ajax', function(Request $request) {
    $rental = Rental::find($request);      
    $var = json_encode($rental);
   
    //send ajax response and process payment
    return response()->json($var);    
});

Route::post('/intent', function(Request $request) {
    $request = @file_get_contents('php://input');
    $event_json = json_decode($request, true);
    $id_number = $event_json["data"]["object"]["metadata"]["rent_id"]; // this is how to access rent id number
    $webhook_id = $event_json["id"];
//update reserved status to lock for payment processing
    $rental = Rental::find($id_number);
    $rental->reserved = 1;
    $rental->save();

// Do something with $event_json

// Return a response to acknowledge receipt of the event
http_response_code(200); // PHP 5.4 or greater
});
//change status back to reserved->0 if redirected form expired session
Route::get('/expired/{id}', function($id) {
    $rental = Rental::find($id);
//check if no webhook was stored for the proccessed payment, only then restore reserved status to 0
    if (empty($rental->notes)) {
       $rental->reserved = 0;
    $rental->save();    
    }
     return redirect('/rentals');
});
//MAIL TEST
/* Route::get('/mail', 'PhpMailerController@fetchForm');
Route::post('/mail', 'PhpMailerController@sendEmail');  */
//testing aws_sdk php mail functionality
Route::get('/aws', 'AwsSdkController@mail');
/* Route::get('sesmail', function() {
    $rental = Rental::find(5); */
    /* dd($rental); */
  /*   Mail::to('valdasgasilionis@yahoo.com')->send(new RentPaidMail($rental));
    return redirect('/');  */
/* }); */
