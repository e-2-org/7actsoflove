<?php
/*
 * functions.php
 */
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");

require_once(dirname(__FILE__) . "/config.php");
require_once(dirname(__FILE__) . "/adodb/adodb.inc.php");
$dbconn = NewADOConnection($config["db_type"]);
$dbconn->Connect($config["db_host"], $config["db_user"], $config["db_password"], $config["db_name"]);

function database_error($db = '', $prg = '', $line = 0, $message = 'Database error')
{
    $lcmessage = "<p>" . $message . "<br />" . "Program: " . $prg . " - Line No.: " . $line . "<br />Database: " . $db->database . "<br />";
    if ($db->ErrorNo() <> 0) {
        $lcmessage .= "Error (" . $db->ErrorNo() . ") : " . $db->ErrorMsg() . "<br />";
    }
    die($lcmessage);
}
function is_current_session($sid)
{
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist_marcus WHERE current_sid = '%s';", $sid);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting current session information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) {
        return false;
    } else {
        return true;
    }
}
function get_current_session()
{
    global $dbconn;
    $query = "SELECT * FROM therapist_marcus;";
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting current session information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) {
        return false;
    } else {
        $res = $result->GetRowAssoc(false);
        return $res["current_sid"];
    }
}
function is_old_session($sid)
{
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist_live WHERE sid = '%s';", $sid);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting current session information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) {
        return false;
    } else {
        return true;
    }
}
function get_current_message() {
    global $dbconn;
    $query = "SELECT * FROM therapist_messages;";
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting messages<br /><b>query:</b><br />" . $query . "</p>");
    }
	  $msgResult = $result->GetRowAssoc(false);
    return stripslashes($msgResult["message"]);
}
function keep_session_active($sid)
{
    global $dbconn;
    $query = sprintf("UPDATE therapist_patients SET session_active = %d WHERE sid = '%s';", time(), $sid);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) return array();
    return $result->GetRowAssoc(false);
}
function get_user($sid)
{
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist_patients WHERE sid = '%s';", $sid);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) return array();
    return $result->GetRowAssoc(false);
}
function patient_exists($sid)
{
    global $dbconn;
    $query = sprintf("SELECT * FROM therapist_patients WHERE sid = '%s';", $sid);
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error storing user information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) {
        return false;
    } else {
        return true;
    }
}
function get_live_users()
{
    global $dbconn, $config;
    $query = sprintf("SELECT * FROM therapist_patients WHERE session_active >= %d;", (time() - ($config["refreshrate"] * 2)));
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
    }
    if ($result->EOF) return array();
	  for(; !$result->EOF; $result->MoveNext()) {
		    $users[] = $result->GetRowAssoc(false);	
  	}
	  return $users;
}

?>