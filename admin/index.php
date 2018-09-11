<?php
/**
 * index.php
 *
 * session administration utility
 */
print head();
print('<ul><li><a href="therapist.php">god-therapist sessions</a></li><li><a href="live.php">live sessions</a></li></ul>');
print foot();
exit;
require_once("../includes/adodb/adodb.inc.php");
require_once("../includes/config.php");
$dbconn = NewADOConnection($config["db_type"]);
$dbconn->Connect($config["db_host"], $config["db_user"], $config["db_password"], $config["db_name"]);
if (!isset($HTTP_GET_VARS["sid"])) {
    print head();
    print('<h1>Sessions</h2><ul>');
    $sessions = get_all_sessions();
    for ($i = 0; $i < count($sessions); $i++) {
        printf('<li><a href="index.php?sid=%s">%s</a> [%s]</li>', $sessions[$i]["sid"], $sessions[$i]["nick"], strftime("%A %d %B, %Y", $sessions[$i]["session_start"]));
    }
    print('</ul>');
    print foot();
} else {
    print head();
    print('<a href="index.php">index</a><br /><table summary="session text" cellpadding="3" cellspacing="0" border="0">');
    $msgs = get_session_contents($HTTP_GET_VARS["sid"]);
    for ($i = 0; $i < count($msgs); $i++) {
        if ($i == 0) {
            printf('<tr><td colspan="2"><h1>Session with %s</h1><span class="entry_start">date: %s</span><br /><br /></td></tr>', $msgs[$i]["nick"], strftime("%A %d %B, %Y", $msgs[$i]["session_start"]));
        }
        print entry(array("start"=>"god-therapist", "body"=>$config["therapist_text"][$msgs[$i]["response_to"]]));
        print entry(array("start"=>$msgs[$i]["nick"], "body"=>stripslashes($msgs[$i]["message"])));
    }
    print('</table>' . foot());
}
function get_session_contents($sid) {
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist WHERE sid = '%s';", $sid);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error storing message<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) return array();
	  for(; !$result->EOF; $result->MoveNext()) {
		    $messages[] = $result->GetRowAssoc(false);	
  	}
	  return $messages;
}
function get_all_sessions() {
    global $dbconn;
    $query = "SELECT DISTINCT sid, nick, session_start FROM therapist ORDER BY session_start DESC";
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting messages<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) return array();
	  for(; !$result->EOF; $result->MoveNext()) {
		    $sessions[] = $result->GetRowAssoc(false);	
  	}
	  return $sessions;
}

function head()
{
    $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $output .= '<html xmlns="http://www.w3.org/1999/xhtml" lang="en"><head>';
    $output .= '<title>Therapy session</title>';
    $output .= '<link rel="stylesheet" href="../css/session.css" type="text/css" media="screen" />';
    $output .= '</head><body>';
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
    $output = sprintf('<tr><td valign="top" align="right"><span class="entry_start">%s:&nbsp;</span></td><td valign="top"><span class="entry_body">%s</span></td></tr>', $details["start"], $details["body"]);
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
