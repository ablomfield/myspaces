<?php
function webexgetmanagedorgs($accesstoken, $personid)
{
    $getmanagedurl = "https://webexapis.com/v1/partner/organizations?managedBy=" . $personid;
    $chgetmanaged = curl_init();
    curl_setopt($chgetmanaged, CURLOPT_URL, $getmanagedurl);
    curl_setopt($chgetmanaged, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($chgetmanaged, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chgetmanaged, CURLOPT_HEADER, 1);
    curl_setopt(
        $chgetmanaged,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Accept: */*',
            'Connection: keep-alive',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $getmanagedresponse = curl_exec($chgetmanaged);
    $getmanagedinfo = curl_getinfo($chgetmanaged);
    $getmanagedcode = $getmanagedinfo["http_code"];
    $header_size = curl_getinfo($chgetmanaged, CURLINFO_HEADER_SIZE);
    $getmanagedjson = substr($getmanagedresponse, $header_size);
    $getmanagedarr = json_decode($getmanagedjson);
    if ($getmanagedcode == 200) {
        $managedorgarr = array();
        for ($i = 0; $i < count($getmanagedarr); $i++) {
            $managedorgarr[] = $getmanagedarr[$i]->orgId;
        }
    } else {
        $managedorgarr = array();
    }
    return $managedorgarr;
}

function webexgetpersonroles($accesstoken, $personid)
{
    $getpersonurl = "https://webexapis.com/v1/people/" . $personid;
    $chgetperson = curl_init();
    curl_setopt($chgetperson, CURLOPT_URL, $getpersonurl);
    curl_setopt($chgetperson, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($chgetperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($chgetperson, CURLOPT_HEADER, 1);
    curl_setopt(
        $chgetperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Accept: */*',
            'Connection: keep-alive',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $getpersonresponse = curl_exec($chgetperson);
    $getpersonifo = curl_getinfo($chgetperson);
    $getpersoncode = $getpersonifo["http_code"];
    $header_size = curl_getinfo($chgetperson, CURLINFO_HEADER_SIZE);
    $getpersonjson = substr($getpersonresponse, $header_size);
    $getpersonarr = json_decode($getpersonjson);
    if ($getpersoncode == 200) {
        $rolesarr = array();
        for ($i = 0; $i < count($getpersonarr->roles); $i++) {
            $rolesarr[] = $getpersonarr->roles[$i];
        }
    } else {
        $rolesarr = array();
    }
    return $rolesarr;
}
function webexgetmyname($accesstoken)
{
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
    $personjson = curl_exec($getperson);
    $personarr = json_decode($personjson);
    $displayname = $personarr->displayName;
    if ($displayname == "") {
        $displayname = "Error!";
    }
    return $displayname;
}

function webexgetpersonname($accesstoken, $personid)
{
    $personurl = "https://webexapis.com/v1/people/" . $personid;
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
    $personjson = curl_exec($getperson);
    $personarr = json_decode($personjson);
    $displayname = $personarr->displayName;
    if ($displayname == "") {
        $displayname = "Error!";
    }
    return $displayname;
}

function webexgetorgbyemail($accesstoken, $email)
{
    $personurl = "https://webexapis.com/v1/people/?email=" . $email;
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
    $personjson = curl_exec($getperson);
    $personarr = json_decode($personjson);
    if (count($personarr->items) != 0) {
        $orgid = $personarr->items[0]->orgId;
    } else {
        $orgid = NULL;
    }
    return $orgid;
}

function webexgetpersoncreated($accesstoken, $personid)
{
    $personurl = "https://webexapis.com/v1/people/" . $personid;
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
    $personjson = curl_exec($getperson);
    $personarr = json_decode($personjson);
    $strcreatedate = $personarr->created;
    $strcreatedate = substr($strcreatedate, 0, 10);
    //$createdate = new DateTime($strcreatedate);
    //$strcreatedate = $createdate->format("F Y");
    return $strcreatedate;
}

function webexgetpersonemail($accesstoken, $personid)
{
    $personurl = "https://webexapis.com/v1/people/" . $personid;
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
    $personjson = curl_exec($getperson);
    $personarr = json_decode($personjson);
    if (isset($personarr->emails[0])) {
        $email = strtolower($personarr->emails[0]);
    } else {
        $email = NULL;
    }

    return $email;
}

function webexgetorgbychorgid($accesstoken, $chorgid)
{
    $orgurl = "https://webexapis.com/v1/organizations/" . $chorgid;
    $getorg = curl_init($orgurl);
    curl_setopt($getorg, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getorg, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getorg,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $orgjson = curl_exec($getorg);
    $orgarr = json_decode($orgjson);
    if (isset($orgarr->id)) {
        $orgid = $orgarr->id;
    } else {
        $orgid = NULL;
    }
    return $orgid;
}

function webexgetorgname($accesstoken, $orgid)
{
    $orgurl = "https://webexapis.com/v1/organizations/" . $orgid;
    $getorg = curl_init($orgurl);
    curl_setopt($getorg, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getorg, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getorg,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $orgjson = curl_exec($getorg);
    $orgarr = json_decode($orgjson);
    if (isset($orgarr->displayName)) {
        $orgname = $orgarr->displayName;
    } else {
        $orgname = NULL;
    }
    return $orgname;
}

function webexgetmanager($accesstoken, $personid)
{
    $personurl = "https://webexapis.com/v1/people/" . $personid;
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
    $personjson = curl_exec($getperson);
    $personarr = json_decode($personjson);
    if (isset($personarr->managerId)) {
        $managerid = $personarr->managerId;
    } else {
        $managerid = NULL;
    }
    return $managerid;
}


function webexgetmessage($accesstoken, $messageid)
{
    $messageurl = "https://webexapis.com/v1/messages/" . $messageid;
    $getmessage = curl_init($messageurl);
    curl_setopt($getmessage, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $messagejson = curl_exec($getmessage);
    $messagearr = json_decode($messagejson);
    $messagetext = $messagearr->text;
    if ($messagetext == "") {
        $messagetext = "Error!";
    }
    return $messagetext;
}

function webexdeletemessage($accesstoken, $messageid)
{
    $messageurl = "https://webexapis.com/v1/messages/" . $messageid;
    $getmessage = curl_init($messageurl);
    curl_setopt($getmessage, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($getmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    curl_exec($getmessage);
    return true;
}

function webexgetcard($accesstoken, $cardid)
{
    $cardurl = "https://webexapis.com/v1/attachment/actions/" . $cardid;
    $getcard = curl_init($cardurl);
    curl_setopt($getcard, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getcard, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getcard,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $cardjson = curl_exec($getcard);
    $cardarr = json_decode($cardjson);
    $inputs = $cardarr->inputs;
    return $inputs;
}

function webexsendcard($accesstoken, $sendtype, $recipient, $cardtemplate, $substitutions)
{
    $sendjson = "{\n";
    if ($sendtype == "person") {
        $sendjson = $sendjson . "    \"toPersonId\": \"{{recipient}}\",\n";
    } else {
        $sendjson = $sendjson . "    \"roomId\": \"{{recipient}}\",\n";
    }

    $sendjson = $sendjson . "    \"markdown\": \"[Learn more](https://adaptivecards.io) about Adaptive Cards.\",\n";
    $sendjson = $sendjson . "    \"attachments\": [\n";
    $sendjson = $sendjson . "        {\n";
    $sendjson = $sendjson . "            \"contentType\": \"application/vnd.microsoft.card.adaptive\",\n";
    $sendjson = $sendjson . "            \"content\": \n";
    $cardbody = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/cardtemplates/" . $cardtemplate . ".json");
    $sendjson = $sendjson . $cardbody;
    $sendjson = $sendjson . "    }\n";
    $sendjson = $sendjson . "  ]\n";
    $sendjson = $sendjson . "}";
    $sendjson = str_replace("{{recipient}}", $recipient, $sendjson);
    if (is_array($substitutions)) {
        $keys = array_keys($substitutions);
        foreach ($substitutions as $key => $value) {
            $sendjson = str_replace("{{{$key}}}", $substitutions[$key], $sendjson);
        }
    }
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $returnjson = curl_exec($postmessage);
    return $returnjson;
}

function webexsendmessage($accesstoken, $roomid, $messagetext)
{
    $senddata = array(
        'roomId'      => $roomid,
        'text'        => $messagetext,
    );
    $sendjson = json_encode($senddata);
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replyjson = curl_exec($postmessage);
    return $replyjson;
}

function webexsendformatted($accesstoken, $roomid, $messagetext)
{
    $senddata = array(
        'roomId'      => $roomid,
        'markdown'    => $messagetext,
    );
    $sendjson = json_encode($senddata);
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replyjson = curl_exec($postmessage);
    return $replyjson;
}

function webexsendfile($accesstoken, $roomid, $messagefile)
{
    $senddata = array(
        'roomId'      => $roomid,
        'files'       => $messagefile,
    );
    $sendjson = json_encode($senddata);
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replyjson = curl_exec($postmessage);
    return $replyjson;
}

function webexinviteperson($accesstoken, $roomid, $emailaddr)
{
    $senddata = array(
        'roomId'      => $roomid,
        'personEmail' => $emailaddr,
    );
    $sendjson = json_encode($senddata);
    $inviteurl = "https://webexapis.com/v1/memberships/";
    $inviteperson = curl_init($inviteurl);
    curl_setopt($inviteperson, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($inviteperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($inviteperson, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $inviteperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replyjson = curl_exec($inviteperson);
    return $replyjson;
}

function webexparteradminadd($accesstoken, $orgid, $personid)
{
    $adminurl = "https://webexapis.com/v1/partner/organizations/$orgid/partnerAdmin/$personid/assign";
    $parteradmin = curl_init($adminurl);
    curl_setopt($parteradmin, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($parteradmin, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $parteradmin,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replyjson = curl_exec($parteradmin);
    return $replyjson;
}

function webexparteradminremove($accesstoken, $orgid, $personid)
{
    $adminurl = "https://webexapis.com/v1/partner/organizations/$orgid/partnerAdmin/$personid/unassign";
    $parteradmin = curl_init($adminurl);
    curl_setopt($parteradmin, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($parteradmin, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $parteradmin,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replyjson = curl_exec($parteradmin);
    return $replyjson;
}

function ping($host, $timeout = 1)
{
    /* ICMP ping packet with a pre-calculated checksum */
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $socket  = socket_create(AF_INET, SOCK_RAW, 1);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
    socket_connect($socket, $host, null);
    $ts = microtime(true);
    socket_send($socket, $package, strLen($package), 0);
    if (socket_read($socket, 255)) {
        $result = microtime(true) - $ts;
    } else {
        $result = "Error!";
    }
    socket_close($socket);
    return $result;
}
