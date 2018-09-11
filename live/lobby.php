<?php
require_once("../includes/functions.php");
if (isset($HTTP_POST_VARS["sid"]) && isset($HTTP_POST_VARS["nick"]) && isset($HTTP_POST_VARS["bed"]) && isset($HTTP_POST_VARS["stick"]) && isset($HTTP_POST_VARS["email"])) {
    $sid = $HTTP_POST_VARS["sid"];
    $bed = $HTTP_POST_VARS["bed"];
    $stick = $HTTP_POST_VARS["stick"];
    $sid = $HTTP_POST_VARS["sid"];
    $nick = stripslashes($HTTP_POST_VARS["nick"]);
    $email = stripslashes($HTTP_POST_VARS["email"]);
    if (!patient_exists($sid)) {
        //add them to the patients table
        $query = sprintf("INSERT INTO therapist_patients (sid, nick, email, bed, stick, session_start, session_active, session_end) VALUES ('%s', '%s', '%s', %d, %d, %d, %d, 0);", $sid, addslashes($nick), addslashes($email), $bed, $stick, time(), time());
        $result = $dbconn->Execute($query);
        if ($result === false) {
            database_error($dbconn, __FILE__, __LINE__, "<p>Error storing user information<br /><b>query:</b><br />" . $query . "</p>");
        }
    }
} elseif (isset($HTTP_GET_VARS["sid"]) && patient_exists($HTTP_GET_VARS["sid"])) {
    $info = get_user($HTTP_GET_VARS["sid"]);
    $bed = $info["bed"];
    $stick = $info["stick"];
    $sid = $HTTP_GET_VARS["sid"];
    $nick = stripslashes($info["nick"]);
    $email = stripslashes($info["email"]);
} elseif (isset($HTTP_GET_VARS["sid"]) && isset($HTTP_GET_VARS["nick"])) {
    //must be oreet
    $sid = $HTTP_GET_VARS["sid"];
    $nick = $HTTP_GET_VARS["nick"];
} else {
    exit;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/javascript" src="../js/sevenacts.js"></script>
<script language="JavaScript" type="text/javascript" src="../js/x.js"></script>
<script language="JavaScript" type="text/javascript" src="../js/x_util.js"></script>
<link rel="stylesheet" href="../css/sevenacts.css" type="text/css" />
<title>lobby</title>
</head>
<?php
print('<body onload="document.forms[\'session_input\'].message.focus();">');
print('<table summary="therapy session" cellpadding="2" cellspacing="0" border="0"><tr>');
printf('<td valign="top" width="180" colspan="2"><iframe src="lobby_session.php?sid=%s" id="sessionframe" frameborder="0" name="sessionframe" width="180" height="400"></iframe></td>', $sid);
print('<td valign="top" width="458" rowspan="3"><table summary="" cellpadding="0" cellspacing="0" border="0"><tr><td valign="middle" width="200" height="120" align="center"><img src="images/lobby.gif" alt="lobby" width="196" height="76" border="0" /></td><td valign="top"><iframe src="lobby_messages.php" width="250" height="120" frameborder="0" name="messagesframe" id="messagesframe"></iframe></td></tr>');
print('<tr><td colspan="2" valign="top"><img src="images/hotel-lobby.jpg" alt="lobby" width="458" height="360" border="0" /></td></tr></table></td>');
printf('<td valign="top" width="140" rowspan="3"><iframe src="user_list.php?sid=%s" id="usersframe" frameborder="0" name="usersframe" width="140" height="360"></iframe></td></tr>', $sid);
printf('<tr><td valign="top" colspan="2"><form name="session_input" id="session_input" action="lobby_session.php#foot" target="sessionframe" method="post" onsubmit="return check_live_input();"><input type="hidden" name="sid" value="%s" /><input type="hidden" name="nick" value="%s" /><input type="hidden" name="sid_to" value="" /><input type="hidden" name="private" value="0" /><input type="text" name="message" value="" class="input_box" /></td></tr><tr><td valign="top"><input type="image" src="../images/send.gif" width="88" height="41" alt="send" border="0" /></form></td><td valign="top" align="right"><a href="../index.html" target="_top"><img src="../images/exit.gif" alt="exit" width="65" height="41" border="0" /></a></td></tr></table>', $sid, $nick);
?>
</body>
</html>
