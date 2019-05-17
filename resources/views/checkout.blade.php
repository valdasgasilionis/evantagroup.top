<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">        

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://js.stripe.com/v3/"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 20vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }   
            /**
            * The CSS shown here will not be introduced in the Quickstart guide, but shows
            * how you can use CSS to style your Element's container.
            */
            .StripeElement {
            box-sizing: border-box;

            height: 40px;

            padding: 10px 12px;

            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;

            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
            }

            .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
            }

            .StripeElement--invalid {
            border-color: #fa755a;
            }

            .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
            }        
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
        <div class="container">
            <div class="form-row"> 
                <div class="form-group col-md-6">                   
                    <label for="amount">You are paying:</label>            
                    <input type="text" class="form-control" value="{{$intent->amount/100}} eur" readonly>
                </div>
                <div class="form-group col-md-6">                                
                    <label for="cardholder-name">cardholder name</label>            
                    <input id="cardholder-name" class="form-control" type="text">
                </div>
                    <input type="hidden" id="hidden" value="{{$id}}">
            </div>
                      <!-- placeholder for Elements -->
            <div class="form-row">
                <div class="form-group col-md-6">            
                    <label for="card-element-number">Card number</label>
                    <div id="card-element-number"></div>
                </div>
                <div class="form-group col-md-4">                   
                    <label for="card-element-expiry">Expiration date</label>
                    <div id="card-element-expiry"></div>
                </div>
                <div class="form-group col-md-2">                    
                    <label for="card-element-cvc">CVV</label>
                    <div id="card-element-cvc"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-2">                           
                    <button id="card-button" class="form-control" data-secret="<?= $intent->client_secret ?>">Submit Payment</button>
                </div>
            </div> 
        </div>  
        
        <script>
            var stripe = Stripe('{{config("services.stripe.key")}}');

            var elements = stripe.elements();

            var cardElementnumber = elements.create('cardNumber'); //creates element for card number
            var cardElementExpiry = elements.create('cardExpiry'); //creates element for card expiry date
            var cardElementCvc = elements.create('cardCvc');  //creates element for card cvc(cvv) number

            cardElementnumber.mount('#card-element-number');
            cardElementExpiry.mount('#card-element-expiry');
            cardElementCvc.mount('#card-element-cvc');

            var cardholderName = document.getElementById('cardholder-name');
            var cardButton = document.getElementById('card-button');
            var clientSecret = cardButton.dataset.secret;

            cardButton.addEventListener('click', function(ev) {
    //make AJAX jquerry call to database to check reserved status
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    

            var id = $("#hidden").val();
        $.ajax({
           type:'POST',
           url:'ajax',
           data:{id:id},
           dataType: "json",
           success: function(rent){
               var arr = rent.split(",");
              var param = arr[6];
              var st = param.split(":");
              var xx = st[1];
              var param = boolean(xx);

               alert(param);
               if (param === 0) {
                     stripe.handleCardPayment(
                        clientSecret, cardElementnumber, {
                            payment_method_data: {
                                billing_details: {name: cardholderName.value}
                            }
                        }
                    ).then(function(result) {
                        if (result.error) {
                        // Display error.message in your UI.
                            alert('error');
                        } else {
                        // The payment has succeeded. Display a success message.
                        alert('success');
                        window.location.replace("/rentals");        
                }
                    });
               } else {
                window.location.replace("/booked");
               }
           }
        });
	});
                                            
        </script>
    </body>
</html>
