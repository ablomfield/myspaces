<?php
if (isset($_SESSION["loggedin"])) {
  $loggedin = true;
  $accesstoken = $_SESSION["accesstoken"];
  $displayname = $_SESSION["displayname"];
} else {
  $loggedin = false;
}
