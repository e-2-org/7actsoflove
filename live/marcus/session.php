<?php
/**
 * session.php
 *
 * therapy session script for marcus
 */
require_once("../../includes/functions.php");
if (isset($HTTP_GET_VARS["exit"])) {
    $query = "TRUNCATE TABLE therapist_marcus;";
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
    }
}
if (isset($HTTP_GET_VARS["sid"])) {
    $sid = $HTTP_GET_VARS["sid"];
} elseif (isset($HTTP_POST_VARS["sid"])) {
    $sid = $HTTP_POST_VARS["sid"];
} else {
    exit;
}
if (!is_current_session($sid)) {
    print head($sid);
    print('<p>Pick a name from the right to start a new session.</p>');
    print foot();
    exit;
}
if (isset($HTTP_POST_VARS["message"]) && trim($HTTP_POST_VARS["message"]) != "") {
    //store the message in the database
    store_message($HTTP_POST_VARS);
}
print head($sid);
//$user = get_user($sid);
$msgs = get_messages($sid);
for ($i = 0; $i < count($msgs); $i++) {
    print entry(array("start"=>$msgs[$i]["nick"], "body"=>stripslashes(htmlspecialchars($msgs[$i]["message"]))));
}
print foot();
function store_message($details) {
    global $dbconn;
    $query = sprintf("INSERT INTO therapist_live (sid, nick, time, message) VALUES ('%s', '%s', %d, '%s');", $details["sid"], addslashes($details["nick"]), time(), htmlspecialchars($details["message"]));
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error storing message<br /><b>query:</b><br />" . $query . "</p>");
    }
}
function get_messages($sid) {
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist_live WHERE sid = '%s' ORDER BY time DESC;", $sid);
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
function head($sid)
{
    global $config;
    $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $output .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>';
    $output .= sprintf('<meta http-equiv="Refresh" content="%d,http://%s/live/marcus/session.php?sid=%s" />', $config["refreshrate"], $config["hostname"], $sid);
    $output .= '<title>Therapy session</title>';
    $output .= '<script language="JavaScript" type="text/javascript" src="../../js/session.js"></script>';
    $output .= '<link rel="stylesheet" href="../../css/session.css" type="text/css" media="screen" />';
    $output .= '</head><body>';
    $output .= '<table summary="therapeutic interaction" cellpadding="0" cellspacing="0" border="0">';
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
    return '<a name="foot"></a></body></html>';
}
?>