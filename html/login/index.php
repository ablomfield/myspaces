<?php
session_start();

// Retrieve Settings and Functions
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

if (isset($_GET['code'])) {
    // Retrieve Code
    $oauth_code = $_GET['code'];
    $oauth_state = $_GET['state'];
    $accessarr = array(
        'grant_type' => 'authorization_code',
        'redirect_uri' => 'https://spaces.collabtoolbox.com/login/',
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $oauth_code
    );
    $accessenc = http_build_query($accessarr);
    $getaccess = curl_init();
    curl_setopt_array($getaccess, array(
        CURLOPT_URL => 'https://webexapis.com/v1/access_token',
        CURLOPT_RETURNTRANSFER => true, // return the transfer as a string of the return value
        CURLOPT_TIMEOUT => 0,   // The maximum number of seconds to allow cURL functions to execute.
        CURLOPT_POST => true,   // This line must place before CURLOPT_POSTFIELDS
        CURLOPT_POSTFIELDS => $accessenc // Data that will send
    ));
    $accessdata = curl_exec($getaccess);
    $accessjson = json_decode($accessdata);
    $accesstoken = $accessjson->access_token;
    $authexpires = $accessjson->expires_in;
    $refreshtoken = $accessjson->refresh_token;
    $refreshexpires = $accessjson->refresh_token_expires_in;
    $authexpires = date("Y-m-d H:i:s", time() + $authexpires);
    $refreshexpires = date("Y-m-d H:i:s", time() + $refreshexpires);
    $lastaccess = date("Y-m-d H:i:s", time());

    // Retrieve Details using authtoken
    $personurl = "https://webexapis.com/v1/people/me";
    $getperson = curl_init($personurl);
    curl_setopt($getperson, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $persondata = curl_exec($getperson);
    $personjson = json_decode($persondata);
    $_SESSION["loggedin"] = true;
    $_SESSION["accesstoken"] = $accesstoken;
    $displayname = $personjson->displayName;
    $_SESSION["displayname"] = $displayname;
    $orgid = $personjson->orgId;
    header("Location: /");
} else {
    header("Location: /");
}
