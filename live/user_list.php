<?php
require_once("../includes/functions.php");
if (isset($HTTP_GET_VARS["sid"])) {
    $sid = $HTTP_GET_VARS["sid"];
} else {
    exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/javascript">
<!--
function send_private_message(to, from, to_nick, nick)
{
    window.open('private_messages.php?to='+to+'&from='+from+'&to_nick='+to_nick+'&nick='+nick, "pm", "width=400,height=200");
}
//-->
</script>

<style type="text/css">
<!--
.intro {
  font:bold 10px verdana,arial,helvetica,sans-serif;
  color:#666;
}
a {
  font:bold 10px verdana,arial,helvetica,sans-serif;
  color:#66f;
  text-decoration:none;
} 
-->
</style>
<?php
printf('<meta http-equiv="Refresh" content="%d,http://%s/live/user_list.php?sid=%s" />', $config["refreshrate"], $config["hostname"], $sid);
?>
<title>userlist</title>
</head>
<body>
<table summary="" cellpadding="0" cellspacing="0" border="0">
<?php
$user = get_user($sid);
$live_users = get_live_users();
for ($i = 0; $i < count($live_users); $i++) {
    $current_sid = get_current_session();
    if ($live_users[$i]["sid"] != $sid && $live_users[$i]["sid"] != $current_sid) {
        printf('<tr><td valign="top"><a href="javascript:send_private_message(\'%s\',\'%s\',\'%s\',\'%s\');">%s</a></td></tr>', $live_users[$i]["sid"], $sid, $live_users[$i]["nick"], $user["nick"], $live_users[$i]["nick"]);
    }
}
?>
</table>
</body>
</html>
