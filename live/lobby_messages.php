<?php
require_once("../includes/functions.php");
printf('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><meta http-equiv="Refresh" content="15,http://%s/live/lobby_messages.php"><head><title>Lobby messages</title></head><body style="margin:0px;padding:0px;"><form method="post" action="messages.php">', $config["hostname"]);
printf('<p style="font:bold 16px arial,helvetica,sans-serif;color:#f00;">%s</p>', get_current_message());
print('</body></html>');
?>
