<!DOCTYPE html>

<html>

<head>

    <title>Laravel 5.7 Ajax Request example</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">

    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
</head>

<body>
    <div class="container">
        <h1>Laravel 5.7 Ajax Request example</h1>
        <form >
            <div class="form-group">
                <label>Rent ID:</label>
                <input type="text" class="form-control">
            </div>
            
           {{--  <div class="form-group">
                <button class="btn btn-success btn-submit">Submit</button>
            </div> --}}
        </form>
    {{-- return ajax request --}}
        <div>The status of this item is:<span></span></div>
    </div>
</body>

<script type="text/javascript">
      $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

    $("input").keyup(function(e){
        e.preventDefault();  
            var id = $("input").val();
        $.ajax({
           type:'POST',
           url:'ajax',
           data:{id:id},
           dataType: "json",
           success: function(rent){
               var arr = rent.split(",");
              var param = arr[7];
              var st = param.split(":");
               alert(st[1]);
           }
        });
	});
</script>   
</html>