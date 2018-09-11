<?php
/**
 * session.php
 *
 * therapy session script
 */
require_once("includes/adodb/adodb.inc.php");
require_once("includes/config.php");
$dbconn = NewADOConnection($config["db_type"]);
$dbconn->Connect($config["db_host"], $config["db_user"], $config["db_password"], $config["db_name"]);

if (!isset($_POST["send"])) {
    // just entered the room
    $nick = $_GET["nick"];
    print head($nick);
    if (isset($_GET["start"])) {
        print entry(array("start"=>"god-therapist", "body"=>$config["therapist_text"][0]));
    }
    print foot();
} else {
    if (isset($_POST["message"]) && trim($_POST["message"]) != "") {
        //store the message in the database
        store_message($_POST);
    }
    print head($_POST["nick"]);
    $msgs = get_messages($_POST["sid"], $_POST["session_start"]);
    for ($i = 0; $i < count($msgs); $i++) {
        print entry(array("start"=>"god-therapist", "body"=>$config["therapist_text"][$msgs[$i]["response_to"]]));
        print entry(array("start"=>$msgs[$i]["nick"], "body"=>stripslashes($msgs[$i]["message"])));
    }
    if (isset($_POST["next_response"]) && !empty($msgs)) {
        print entry(array("start"=>"god-therapist", "body"=>$config["therapist_text"][$_POST["next_response"]]));
    }
    print foot();
}
function store_message($details) {
    global $dbconn;
    $query = sprintf("INSERT INTO therapist (sid, nick, time, message, response_to, bed, stick, session_start) VALUES ('%s', %s, %d, %s, %d, %d, %d, %d);", $details["sid"], $dbconn->Quote(htmlspecialchars($details["nick"])), time(), $dbconn->Quote(htmlspecialchars($details["message"])), $details["response_to"], $details["bed"], $details["stick"], $details["session_start"]);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error storing message<br /><b>query:</b><br />" . $query . "</p>");
    }
}
function get_messages($sid, $session_start) {
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist WHERE sid = '%s' AND session_start = %d;", $sid, $session_start);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting messages<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) return array();
	  for(; !$result->EOF; $result->MoveNext()) {
		    $messages[] = $result->GetRowAssoc(false);	
  	}
	  return $messages;
}
function head($nick)
{
    $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $output .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>';
    $output .= '<title>Therapy session</title>';
    $output .= '<link rel="stylesheet" href="css/session.css" type="text/css" media="screen" />';
    $output .= '</head><body>';
    $output .= '<table summary="therapeutic interaction" cellpadding="0" cellspacing="0" border="0">';
    $output .= sprintf('<tr><td colspan="2" class="entry">%s has just entered the room</td></tr>', $nick);
    return $output;
}
/**
 * entry()
 *
 * returns a formatted chat entry
 * @param array $details
 */
function entry($details)
{
    $output = sprintf('<tr><td valign="top"><span class="entry_start">%s:&nbsp;</span><span class="entry_body">%s</span></td></tr>', $details["start"], $details["body"]);
    return $output;
}
/**
 * foot
 */
function foot()
{
    return '</table><br /><br /><a name="foot"></a></body></html>';
}
function database_error($db = '', $prg = '', $line = 0, $message = 'Database error')
{
    $lcmessage = "<p>" . $message . "<br />" . "Program: " . $prg . " - Line No.: " . $line . "<br />Database: " . $db->database . "<br />";
    if ($db->ErrorNo() <> 0) {
        $lcmessage .= "Error (" . $db->ErrorNo() . ") : " . $db->ErrorMsg() . "<br />";
    }
    die($lcmessage);
}
