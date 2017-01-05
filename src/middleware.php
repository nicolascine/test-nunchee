<?php

// Application middleware
$sendRequest = function ($request, $response, $next) {

    $END_POINT = 'http://test-web.nunchee.com/nunchee/api/1.0/users/login_frontend';
    $loginParams = $request->getParsedBody();

    $fields = array(
        'username' => urlencode($loginParams['username']),
        'password' => urlencode($loginParams['password'])
    );

    //Url-ify the data for the POST
    foreach($fields as $key=>$value) { 
        $fields_string .= $key.'='.$value.'&'; 
    }
    rtrim($fields_string, '&');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $END_POINT);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $remote_server_output = curl_exec ($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    //Set response array
    $response_array = [];
    $response_array['http_code'] = $httpcode;
    $response_array['response'] = $remote_server_output;
    $response_array = stripslashes(json_encode($response_array));

    $response->getBody()->write($response_array);
    return $response;
};