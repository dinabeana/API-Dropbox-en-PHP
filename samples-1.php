<?php

session_start();

require_once('config.php');

$url_step_2 = 'http://localhost/API-Dropbox-en-PHP/samples-2.php';

$ch = curl_init(); 

// On crée le tableau d'entête
$headers = array( 
	'Authorization: OAuth oauth_version="1.0", oauth_signature_method="PLAINTEXT", oauth_consumer_key="' . $app_key . '", oauth_signature="' . $app_secret . '&"' 
);

curl_setopt($ch, CURLOPT_HTTPHEADER		 , $headers); 
curl_setopt($ch, CURLOPT_URL					 , "https://api.dropbox.com/1/oauth/request_token");  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  
$request_token_response = curl_exec($ch);

parse_str($request_token_response, $parsed_request_token);

$json_access = json_decode($request_token_response);

// On teste la validé de notre demande de token
if (isset( $json_access->error)) {
	echo '<br><br>FATAL ERROR: ' . $json_access->error . '<br><br>';
	die();
}

// On stocke en session les données
$_SESSION['myapp'] 															= array();
$_SESSION['myapp']['oauth_request_token']        = $parsed_request_token['oauth_token'];
$_SESSION['myapp']['oauth_request_token_secret'] = $parsed_request_token['oauth_token_secret'];

// On redirige vers la page
header( 'Location: https://www.dropbox.com/1/oauth/authorize?oauth_token=' . $parsed_request_token['oauth_token'] . '&oauth_callback=' . $url_step_2 );
