<?php
require_once("../../includes/functions.php");
if (isset($HTTP_POST_VARS["message"])) {
    update_message($HTTP_POST_VARS["message"]);
}
function update_message($message)
{
    global $dbconn;
    $query = sprintf("UPDATE therapist_messages SET message = '%s';", strip_tags(addslashes($message), '<b><i><a>'));
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting messages<br /><b>query:</b><br />" . $query . "</p>");
    }
}
print('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><title>Lobby messages</title><link rel="stylesheet" href="../../css/sevenacts.css" type="text/css" /></head><body><form method="post" action="messages.php">');
printf('<textarea name="message" cols="40" rows="4" style="width:200px;height:150px;border:1px solid #f00;">%s</textarea><input type="image" src="../images/message_button.gif" width="150" height="150" /></form>', get_current_message());
print('</body></html>');
?>
