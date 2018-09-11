<?php
/**
 * session.php
 *
 * therapy session script
 */
require_once("../includes/functions.php");
if (isset($HTTP_GET_VARS["sid"])) {
    $sid = $HTTP_GET_VARS["sid"];
} elseif (isset($HTTP_POST_VARS["sid"])) {
    $sid = $HTTP_POST_VARS["sid"];
} else {
    exit;
}
keep_session_active($sid);
if (isset($HTTP_POST_VARS["message"]) && trim($HTTP_POST_VARS["message"]) != "") {
    //store the message in the database
    store_message($HTTP_POST_VARS);
}
print head($sid);
//$user = get_user($sid);
$msgs = get_messages($sid);
for ($i = 0; $i < count($msgs); $i++) {
    $priv = ($msgs[$i]["sid"] == $sid || $msgs[$i]["sid_to"] == $sid)? true: false;
    print entry(array("start"=>$msgs[$i]["nick"], "body"=>strip_tags(stripslashes($msgs[$i]["message"]), '<b><i><a>'), "private"=>$priv));
}
print foot();
function store_message($details) {
    global $dbconn;
    if ($details["private"] == 1) {
        $_sid = $sid;
        $_sid_to = $details["to"];
    } else {
        $_sid = '';
        $_sid_to = '';
    }
    $query = sprintf("INSERT INTO therapist_lobby (sid, sid_to, nick, time, message) VALUES ('%s', '%s', '%s', %d, '%s');", $_sid, $_sid_to, addslashes($details["nick"]), time(), strip_tags($details["message"], '<a><b><i>'));
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error storing message<br /><b>query:</b><br />" . $query . "</p>");
    }
}
function get_messages($sid) {
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist_lobby WHERE (sid = '' OR sid = '%s' OR sid_to = '%s') AND time >= %d ORDER BY time DESC;", $sid, $sid, (time() - (60 * 60)));
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
    $output .= sprintf('<meta http-equiv="Refresh" content="%d,http://%s/live/lobby_session.php?sid=%s" />', $config["refreshrate"], $config["hostname"], $sid);
    $output .= '<title>Therapy session</title>';
    $output .= '<script language="JavaScript" type="text/javascript" src="../js/session.js"></script>';
    $output .= '<link rel="stylesheet" href="../css/session.css" type="text/css" media="screen" />';
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
    if ($details["private"]) {
        $output = sprintf('<tr><td valign="top"><span style="font-style:italic;color:#333">%s:&nbsp;</span><span class="entry_body">%s</span></td></tr>', $details["start"], $details["body"]);
    } else {
        $output = sprintf('<tr><td valign="top"><span class="entry_start">%s:&nbsp;</span><span class="entry_body">%s</span></td></tr>', $details["start"], $details["body"]);
    }
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