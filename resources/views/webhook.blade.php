@extends('layouts.app')
    @section('content')
    <?php
        // Retrieve the request's body and parse it as JSON:
        $input = @file_get_contents('php://input');
        $event_json = json_decode($input);
        
        http_response_code(200);
    ?>
    @endsection