<?php
if (isset($_SESSION["userpkid"])) {
  $userpkid = $_SESSION["userpkid"];
  $email = $_SESSION["email"];
} else {
  if ($_SERVER['REQUEST_URI'] != "/" && $_SERVER['REQUEST_URI'] != "/index.php" && substr($_SERVER['REQUEST_URI'],0,13) != "/accessdenied") {
    header("Location: /", true, 302);
    exit;
  } else {
    $userpkid = "";
    $email = "";
    $displayname = "";
    $timezone = "America/Chicago";
  }
}

if (isset($_SESSION["isadmin"])) {
  $isadmin = $_SESSION["isadmin"];
} else {
  $isadmin = 0;
}

if (isset($_SESSION["displayname"])) {
  $displayname = $_SESSION["displayname"];
} else {
  $displayname = "";
}

if (isset($_SESSION["timezone"])) {
  $timezone = $_SESSION["timezone"];
} else {
  $timezone = "America/Chicago";
}
?>