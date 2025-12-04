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
        'redirect_uri' => 'https://keymaster.pnslabs.com/login/',
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
    $authtoken = $accessjson->access_token;
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
            'Authorization: Bearer ' . $authtoken
        )
    );
    $persondata = curl_exec($getperson);
    $personjson = json_decode($persondata);
    $displayname = $personjson->displayName;
    $emailarr = $personjson->emails;
    $email = $emailarr[0];
    if (isset($personjson->avatar)) {
        $avatar = $personjson->avatar;
    } else {
        $avatar = "";
    }
    $emaildomain = substr($email, strpos($email, '@') + 1);
    $orgid = $personjson->orgId;

    // Check if User Exists in Database
    $rsusercheck = mysqli_query($dbconn, "SELECT * FROM users WHERE email = '" . $email . "'");
    if (mysqli_num_rows($rsusercheck) == 0) {
        header("Location: /accessdenied/?reason=selfregistration");
        exit("Denied - Self Registration Not Allowed");
    } else {
        $rowusercheck = mysqli_fetch_assoc($rsusercheck);
        $userpkid = $rowusercheck["pkid"];
        $isadmin = $rowusercheck["isadmin"];
        $_SESSION["userpkid"] = $userpkid;
        $_SESSION["displayname"] = $displayname;
        $_SESSION["isadmin"] = $isadmin;
        $_SESSION["email"] = $email;
        if ($avatar !== "" && $avatar !== $rowusercheck["avatar"]) {
            $avsource = imagecreatefromjpeg($avatar);
            $size = getimagesize($avatar);
            $savepath = fopen("../avatars/" . $userpkid . ".png", 'wb');
            $avsave = imagecreatetruecolor(250, 250);
            imagecopyresampled($avsave, $avsource, 0, 0, 0, 0, 250, 250, $size[0], $size[1]);
            imagepng($avsave, $savepath);
        }
        $updatesql = "UPDATE users SET displayname = '" . str_replace("'", "''", $displayname) . "', email = '" . $email . "', avatar = '" . $avatar . "', orgid = '" . $orgid . "', lastaccess = '" . $lastaccess . "', lastip='" . $_SERVER["REMOTE_ADDR"] . "' WHERE pkid = '" . $userpkid . "'";
        mysqli_query($dbconn, $updatesql);
        header("Location: /");
    }
} else {
    header("Location: /");
}
