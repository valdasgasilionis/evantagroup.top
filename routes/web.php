<?php

use Illuminate\Http\Request;
use App\Rental;
use App\Notifications\Webhook;

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

    return view('home');
});


Route::post('/charge', function (Request $request) {
    // Set your secret key: remember to change this to your live secret key in production
    // See your keys here: https://dashboard.stripe.com/account/apikeys
    \Stripe\Stripe::setApiKey(config("services.stripe.secret"));

    $intent = \Stripe\PaymentIntent::create([
        'amount' => $request->eur.$request->ct,
        'currency' => 'eur',
        'metadata' => ['rent_id' => $request->id]
    ]);
    $id_number = $request->id;

    return view('checkout', [
        'intent' => $intent,
        'id_number' => $id_number
    ]);
});

Route::get('/rentals', function() {
    $rentals = Rental::all();
    return view('index', [
        'rentals'=> $rentals
    ]);
});

Route::post('/rentals', function(Request $request) {
    $rental = new Rental;
    $rental->start = $request->start;
    $rental->end = $request->end;
    $rental->price = $request->price;

    $rental->save();

    return back();
});

Route::get('/rentals/{id}/edit', function($id) {
    $item = Rental::find($id);
    $number = $id;
    return view('edit', [
        'item' =>  $item
    ]);
});
//UPDATE rental status to 'booked'
/* Route::get('/rentals/{id}/update', function($id) {
    $rent = Rental::find($id);     
    $rent->reserved = 1;   
    $rent->save();

    return redirect('/rentals');
}); */
Route::post('/rentals/{id}/finalize', function($id) {
    $case = Rental::find($id);
    $case->paid = 1;
    $case->save();

    return back();
});
//webhook testing STRIPE
Route::post('/webhook', function(Request $payload) {

    // Set your secret key: remember to change this to your live secret key in production
    // See your keys here: https://dashboard.stripe.com/account/apikeys
    \Stripe\Stripe::setApiKey(config("services.stripe.secret"));

    // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = 'whsec_zfBx7FF4KYuhIxCzaS8rBRx6BsYgw7pr';

    // Retrieve the request's body and parse it as JSON:

    $payload = @file_get_contents('php://input');
    $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    $event = null;

    /* $request = @file_get_contents('php://input'); */
    $event_json = json_decode($payload, true);
    $id_number = $event_json["data"]["object"]["metadata"]["rent_id"]; // this is how to access rent id number
    $webhook_id = $event_json["id"];
    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    } catch(\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        exit();
    } catch(\Stripe\Error\SignatureVerification $e) {
        // Invalid signature
        http_response_code(400);
        exit();
    }
    
    // Handle the event
    switch ($event->type) {
        case 'charge.succeeded':
            $chargeSucceeded = $event->data->object; // contains a StripePaymentIntent
            handlePaymentIntentSucceeded($chargeSucceeded);
            break;
     
        // ... handle other event types
        default:
            // Unexpected event type
            http_response_code(400);
            exit();
    }
   
    // Do something with $event_json
    function handlePaymentIntentSucceeded($chargeSucceeded) {
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
        // Return a response to acknowledge receipt of the event
    /*  http_response_code(200); */ // PHP 5.4 or greater
        http_response_code(200);
    };
});
      

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function() { 
    return view('test');
});