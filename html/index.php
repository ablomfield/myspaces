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
                echo "<h1>Hello, {$displayname}!</h1>\n";

                $recmax       = 1000;
                $getroomsurl = "https://webexapis.com/v1/rooms?max={$recmax}";
                $spacecount  = 0;
                $groupcount  = 0;
                $directcount = 0;

                $oldestTs    = PHP_INT_MAX;
                $oldestTitle = "";
                $newestTs    = 0;
                $newestTitle = "";

                $loopcount = 0;

                /* ---------- Reusable cURL handle ---------- */
                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER         => true,
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'Accept: */*',
                        'Authorization: Bearer ' . $accesstoken
                    ],
                ]);

                do {
                    $loopcount++;
                    curl_setopt($ch, CURLOPT_URL, $getroomsurl);

                    $response = curl_exec($ch);
                    if ($response === false) {
                        break;
                    }

                    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    $headers    = substr($response, 0, $headerSize);
                    $body       = substr($response, $headerSize);

                    /* ---------- Pagination handling ---------- */
                    $nextUrl = null;
                    foreach (explode("\r\n", $headers) as $line) {
                        if (stripos($line, 'link:') === 0 && strpos($line, 'rel="next"') !== false) {
                            preg_match('/<([^>]+)>/', $line, $m);
                            $nextUrl = $m[1] ?? null;
                            break;
                        }
                    }
                    $getroomsurl = $nextUrl;

                    $data = json_decode($body, true);
                    if (empty($data['items'])) {
                        break;
                    }

                    foreach ($data['items'] as $room) {
                        $spacecount++;

                        if (!empty($room['type'])) {
                            if ($room['type'] === 'group') {
                                $groupcount++;
                            } elseif ($room['type'] === 'direct') {
                                $directcount++;
                            }
                        }

                        if (!empty($room['created'])) {
                            $ts = strtotime($room['created']);
                            if ($ts < $oldestTs) {
                                $oldestTs    = $ts;
                                $oldestTitle = $room['title'] ?? '';
                            }
                            if ($ts > $newestTs) {
                                $newestTs    = $ts;
                                $newestTitle = $room['title'] ?? '';
                            }
                        }
                    }
                } while ($getroomsurl && $loopcount < 100);

                curl_close($ch);

                /* ---------- Output (single echo) ---------- */
                echo ("<p><b>You are in {$spacecount} spaces!</b></p>\n");
                echo ("<table>\n");
                echo ("  <tr>\n");
                echo ("    <td align=\"right\"><i>1:1 Spaces</i></td>\n");
                echo ("    <td align=\"center\">{$directcount}</td>\n");
                echo ("  </tr>\n");
                echo ("  <tr>\n");
                echo ("    <td align=\"right\"><i>Group Spaces</i></td>\n");
                echo ("    <td align=\"center\">{$groupcount}</td>\n");
                echo ("  </tr>\n");
                echo ("  <tr>\n");
                echo ("    <td align=\"right\"><i><b>Total Spaces</b></i></td>\n");
                echo ("    <td align=\"center\"><b>{$spacecount}</b></td>\n");
                echo ("  </tr>\n");
                echo ("</table>\n");

                echo ("<p>The oldest space is \"{$oldestTitle}\" (Created {date('M jS Y', $oldestTs)}).</p>\n");
                echo ("<p>The newest space is \"{$newestTitle}\" (Created {date('M jS Y', $newestTs)}).</p>\n");
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