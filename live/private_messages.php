<?php
require_once("../includes/functions.php");
if (!isset($HTTP_GET_VARS["to"]) && !isset($HTTP_GET_VARS["from"]) && !isset($HTTP_GET_VARS["nick"]) && !isset($HTTP_GET_VARS["to_nick"])) {
    if (isset($HTTP_POST_VARS["to"]) && isset($HTTP_POST_VARS["from"]) && isset($HTTP_POST_VARS["message"]) && $HTTP_POST_VARS["message"] != "" && isset($HTTP_POST_VARS["nick"]) && isset($HTTP_POST_VARS["to_nick"])) {
        $query = sprintf("INSERT INTO therapist_lobby (sid, sid_to, nick, time, message) VALUES ('%s', '%s', '%s whispers to %s', %d, '%s');", $HTTP_POST_VARS["from"], $HTTP_POST_VARS["to"], $HTTP_POST_VARS["nick"], $HTTP_POST_VARS["to_nick"], time(), strip_tags($HTTP_POST_VARS["message"], '<a><b><i>'));
        $result = $dbconn->Execute($query);
        if ($result === false) {
            database_error($dbconn, __FILE__, __LINE__, "<p>Error storing message<br /><b>query:</b><br />" . $query . "</p>");
        }
    }
    print('<script language="JavaScript" type="text/javascript">self.close();</script>');
    exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Send a private message</title>
</head>
<body>
<?php
printf('<p>sending a private message to %s</p><form action="private_messages.php" method="post"><input type="hidden" name="to" value="%s" /><input type="hidden" name="from" value="%s" /><input type="hidden" name="nick" value="%s" /><input type="hidden" name="to_nick" value="%s" /><input type="text" name="message" size="30" /><input type="image" src="../images/send.gif" /></form>', $HTTP_GET_VARS["to_nick"], $HTTP_GET_VARS["to"], $HTTP_GET_VARS["from"], $HTTP_GET_VARS["nick"], $HTTP_GET_VARS["to_nick"]);
?>
</body>
</html>
