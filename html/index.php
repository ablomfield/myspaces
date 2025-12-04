<?php
session_start();

// Check Logged In
include($_SERVER['DOCUMENT_ROOT'] . "/includes/checkaccess.php");

// Retrieve Settings and Functions
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo ($sitetitle); ?></title>
    <link rel="icon" type="image/icon" href="/images/keymaster.ico">
    <link rel="stylesheet" href="/css/keymaster.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src='https://code.jquery.com/jquery-1.4.2.js'></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-dark-grey.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
</head>

<body>
    <div class="parent">
        <div class="km-logo">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/logo.php"; ?>
        </div>
        <div class="km-title">
            <?php echo ($sitetitle); ?>
        </div>
        <div class="km-body" style="width: 800px">
            <?php
            if ($loggedin) {
                echo ("<h1>Hello $displayname!</h1>\n");
                $recmax = 1000;
                $relurl = "rel=\"next\"";
                $spacecount = 1;
                $getroomsurl = "https://webexapis.com/v1/rooms?max=" . strval($recmax);
                $loopcount = 1;
                while ($relurl == "rel=\"next\"" && $loopcount < 100) {
                    ++$loopcount;
                    $chgetrooms = curl_init();
                    curl_setopt($chgetrooms, CURLOPT_URL, $getroomsurl);
                    curl_setopt($chgetrooms, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($chgetrooms, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($chgetrooms, CURLOPT_HEADER, 1);
                    curl_setopt(
                        $chgetrooms,
                        CURLOPT_HTTPHEADER,
                        array(
                            'Content-Type: application/json',
                            'Accept: */*',
                            'Connection: keep-alive',
                            'Authorization: Bearer ' . $accesstoken
                        )
                    );
                    $getroomsresponse = curl_exec($chgetrooms);
                    $header_size = curl_getinfo($chgetrooms, CURLINFO_HEADER_SIZE);
                    $getroomsheader = substr($getroomsresponse, 0, $header_size);
                    $headersarr = [];
                    foreach (explode("\r\n", trim($getroomsheader)) as $row) {
                        if (preg_match('/(.*?): (.*)/', $row, $matches)) {
                            $headersarr[$matches[1]] = $matches[2];
                        }
                    }
                    if (isset($headersarr['link'])) {
                        $getroomsurl = substr(substr($headersarr['link'], 0, strpos($headersarr['link'], ";") - 1), 1, 100);
                        $relurl = substr($headersarr['link'], strpos($headersarr['link'], ";") + 2);
                    } else {
                        $nexturl = "";
                        $relurl = "none";
                    }
                    $getroomsjson = substr($getroomsresponse, $header_size);
                    $getroomsarr = json_decode($getroomsjson);
                    echo ("Showing " . count($getroomsarr->items) . "items.<br>\n");
                    //print_r($getroomsarr);
                    for ($roomindex = 0; $roomindex <= count($getroomsarr->items) - 1; $roomindex++) {
                        if (isset($getroomsarr->items[$roomindex]->id)) {
                            $roomid = $getroomsarr->items[$roomindex]->id;
                        } else {
                            $roomid = "";
                        }
                        if (isset($getroomsarr->items[$roomindex]->title)) {
                            $roomtitle = $getroomsarr->items[$roomindex]->title;
                            $roomtitle = str_replace("'", "\'", $roomtitle);
                            $roomtitle = str_replace("\"", "\\\"", $roomtitle);
                        } else {
                            $roomtitle = "";
                        }
                        if (isset($getroomsarr->items[$roomindex]->created)) {
                            $roomcreated = date_format(date_create($getroomsarr->items[$roomindex]->created), 'Y-m-d H:i:s');
                        } else {
                            $roomcreated = NULL;
                        }
                        ++$spacecount;
                    }
                    flush();
                }
                echo ("<p>You are in $spacecount spaces!</p>\n");
            } else {
                echo ("            <a href=\"" . $oauth_url . "\">\n");
                echo ("                <img width=\"400\" src=\"/images/signin.png\" alt=\"Sign In with Webex\" />\n");
                echo ("            </a>\n");
            }
            ?> </div>
        <div class="km-footer">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>
        </div>
    </div>
</body>

</html>