<?php
require_once("../../includes/functions.php");
if (isset($HTTP_GET_VARS["exit"])) {
    $query = "TRUNCATE TABLE therapist_marcus;";
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
    }
    $query = sprintf("INSERT INTO therapist_live (sid, nick, time, message) VALUES ('%s', 'system', %d, '%s');", $HTTP_GET_VARS["sid"], time(), 'marcus has just left the room');
    $result = $dbconn->Execute($query);
    if ($result === false) {
        database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
    }
}
if (isset($HTTP_GET_VARS["sid"])) {
    $user = get_user($HTTP_GET_VARS["sid"]);
    if (!isset($HTTP_GET_VARS["refresh"]) && !isset($HTTP_GET_VARS["exit"])) {
        $query = sprintf("INSERT INTO therapist_marcus (current_sid) VALUES ('%s');", $HTTP_GET_VARS["sid"]);
        $result = $dbconn->Execute($query);
        if ($result === false) {
            database_error($dbconn, __FILE__, __LINE__, "<p>Error inserting current user<br /><b>query:</b><br />" . $query . "</p>");
        }
        $query = sprintf("INSERT INTO therapist_lobby (sid, sid_to, nick, time, message) VALUES ('%s', '%s', 'marcus', %d, '%s, could you come into the bedroom now please? To do so, <a href=\"javascript:go_to_session(\'%s\');\">click here</a>');", md5('marcus'), $user["sid"], time(), $user["nick"], $user["sid"]);
        $result = $dbconn->Execute($query);
        if ($result === false) {
            database_error($dbconn, __FILE__, __LINE__, "<p>Error adding a message to the lobby<br /><b>query:</b><br />" . $query . "</p>");
        }
        $query = sprintf("INSERT INTO therapist_live (sid, nick, time, message) VALUES ('%s', 'system', %d, '%s');", $HTTP_GET_VARS["sid"], time(), 'marcus has just entered the room');
        $result = $dbconn->Execute($query);
        if ($result === false) {
            database_error($dbconn, __FILE__, __LINE__, "<p>Error getting user information<br /><b>query:</b><br />" . $query . "</p>");
        }
    }
    if (is_current_session($HTTP_GET_VARS["sid"])) {
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
printf('<meta http-equiv="Refresh" content="%d,http://%s/live/marcus/user_list.php?sid=%s&refresh=1" />', $config["refreshrate"], $config["hostname"], $HTTP_GET_VARS["sid"]);
?>
<style type="text/css">
<!--
body, p {
  font:10px verdana,arial,helvetica,sans-serif;
  color:#666;
}
a {
  font:10px verdana,arial,helvetica,sans-serif;
  color:#66f;
  text-decoration:none;
} 
-->
</style>
<body>
<p class="intro">You are currently talking to:</p>
<?php
        printf('<p>%s [<a href="mailto:%s">%s</a>]</p>', $user["nick"], $user["email"], $user["email"]);
        exit;
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
<!--
.intro {
  font:bold 10px verdana,arial,helvetica,sans-serif;
  color:#666;
}
a.old {
  font:bold 10px verdana,arial,helvetica,sans-serif;
  color:#f66;
  text-decoration:none;
} 
a.new {
  font:bold 10px verdana,arial,helvetica,sans-serif;
  color:#66f;
  text-decoration:none;
} 
-->
</style>

<script language="JavaScript" type="text/javascript">
<!--
function start_session(sid, bed, stick)
{
    /*var dbg = 'Lets see what we have:\n\n';
    dbg += 'this should point to part of a form I need\n' + top.window.document.forms['session_input'].sid;
    dbg += '\n\nthese two should be http://www.7actsoflove.org/live/images/blank.gif\n\n';
    dbg += top.window.document.images['bed'].src + '\n' + top.window.document.images['stick'].src + '\n\n';
    dbg += 'this should be http://www.7actsoflove.org/live/marcus/session.php:\n\n';
    dbg += top.window.frames['sessionframe'].location.href;
    alert(dbg);
    return;*/
    top.document.forms['session_input'].sid.value = sid;
    top.document.images['bed'].src = 'http://' + '<?php echo $config["hostname"] ?>' + '/live/images/bedroom' + bed + '.jpg';
    top.document.images['stick'].src = 'http://' + '<?php echo $config["hostname"] ?>' + '/live/images/stick' + stick + '/bedroom' + bed + '.gif';
    top.frames['sessionframe'].location.href = 'http://' + '<?php echo $config["hostname"] ?>' + '/live/marcus/session.php?sid=' + sid + '&source=oreet';
    location = 'user_list.php?sid='+sid;
}
//-->
</script>
<?php
printf('<meta http-equiv="Refresh" content="%d,http://%s/live/marcus/user_list.php" />', $config["refreshrate"], $config["hostname"]);
?>
<title>userlist</title>
</head>
<body>
<p class="intro">Click on a user's handle to start a conversation with them.</p>
<table summary="" cellpadding="0" cellspacing="0" border="0">
<?php
$live_users = get_live_users();
for ($i = 0; $i < count($live_users); $i++) {
    $cls = is_old_session($live_users[$i]["sid"])? 'old': 'new';
    printf('<tr><td valign="top"><a href="javascript:start_session(\'%s\',%d,%d);" class="%s">%s</a></td></tr>', $live_users[$i]["sid"], $live_users[$i]["bed"], $live_users[$i]["stick"], $cls, $live_users[$i]["nick"]);
}
print('</table>');
if (count($live_users)) {
    print('<script language="JavaScript" type="text/javascript">top.window.playbell();</script>');
}
?>
</table>
</body>
</html>
