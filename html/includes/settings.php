<?php
// Retrieve YAML Settings
$yamlsettings = yaml_parse_file('/opt/spaces/settings.yaml');
$dbserver = $yamlsettings['Database']['ServerName'];
$dbuser = $yamlsettings['Database']['Username'];
$dbpass = $yamlsettings['Database']['Password'];
$dbname = $yamlsettings['Database']['DBName'];

// Load Settings
$dbconn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
}
$rssettings = mysqli_query($dbconn, "SELECT * FROM settings") or die("Error in Selecting " . mysqli_error($dbconn));
$rowsettings = mysqli_fetch_assoc($rssettings);
$appversion = $rowsettings["appversion"];
$sitetitle = $rowsettings["sitetitle"];
$client_id = $rowsettings["client_id"];
$client_secret = $rowsettings["client_secret"];
$integration_id = $rowsettings["integration_id"];
$oauth_url = $rowsettings["oauth_url"];

// Set Debug
if (isset($_SESSION["enabledebug"])) {
    if ($_SESSION['enabledebug'] <> 1) {
        $_SESSION["enabledebug"] = 0;
    }
} else {
    $_SESSION["enabledebug"] = 0;
}

// Keymaster Encrypt Function
function keymaster_encrypt($inputstring) {
    $yamlsettings = yaml_parse_file('/opt/keymaster/settings.yaml');
    $encrypt_key = $yamlsettings['Encryption']['Key'];
    $encrypt_iv = $yamlsettings['Encryption']['IV'];

    $encrypt_ciphering = "AES-128-CTR";
    $encrypt_options = 0;

    $outputstring = openssl_encrypt(
        $inputstring,
        $encrypt_ciphering,
        $encrypt_key,
        $encrypt_options,
        $encrypt_iv
    );

    return $outputstring;
}

// Keymaster Decrypt Function
function keymaster_decrypt($inputstring) {
    $yamlsettings = yaml_parse_file('/opt/keymaster/settings.yaml');
    $encrypt_key = $yamlsettings['Encryption']['Key'];
    $encrypt_iv = $yamlsettings['Encryption']['IV'];

    $encrypt_ciphering = "AES-128-CTR";
    $encrypt_options = 0;

    $outputstring = openssl_decrypt(
        $inputstring,
        $encrypt_ciphering,
        $encrypt_key,
        $encrypt_options,
        $encrypt_iv
    );

    return $outputstring;
}

