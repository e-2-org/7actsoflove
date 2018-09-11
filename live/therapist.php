<?php
require_once("../includes/functions.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/javascript" src="../js/sevenacts.js"></script>
<script language="JavaScript" type="text/javascript" src="../js/x.js"></script>
<script language="JavaScript" type="text/javascript" src="../js/x_util.js"></script>
<link rel="stylesheet" href="../css/sevenacts.css" type="text/css" />
<title>7 acts of love: live</title>
</head>
<?php
if (!isset($HTTP_GET_VARS["sid"])) {
    print('<body><p>Please <a href="bedroom.php">go back to the beginning</a>.</p>');
} else {
    if (patient_exists($HTTP_GET_VARS["sid"])) {
        $info = get_user($HTTP_GET_VARS["sid"]);
        $bed = $info["bed"];
        $stick = $info["stick"];
        $sid = $HTTP_GET_VARS["sid"];
        $nick = stripslashes($info["nick"]);
        $email = stripslashes($info["email"]);
        $bed_path = $config["image_dir"] . $config["path_sep"] . "bedroom" . $bed . ".jpg";
        $bed_info = getimagesize($bed_path);
        print('<body onload="document.forms[\'session_input\'].message.focus();">');
        printf('<div class="therapy_bed" id="t_bed"><img src="../images/bedroom%d.jpg" alt="bedroom" width="%d" height="%d" border="0" /></div>', $bed, $bed_info[0], $bed_info[1]);
        printf('<div class="therapy_bed" id="t_position"><img src="../images/stick%d/bedroom%d.gif" width="%d" height="%d" alt="position" border="0" /></div>', $stick, $bed, $bed_info[0], $bed_info[1]);
        printf('<table summary="therapy session" width="180" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="180" colspan="2"><iframe src="session.php?sid=%s&entry=1" id="sessionframe" frameborder="0" name="sessionframe" width="180" height="400"></iframe></td></tr>', $sid);
        printf('<tr><td valign="top" colspan="2"><form name="session_input" id="session_input" action="session.php#foot" target="sessionframe" method="post" onsubmit="return check_live_input();"><input type="hidden" name="sid" value="%s" /><input type="hidden" name="nick" value="%s" /><input type="hidden" name="bed" value="%d" /><input type="hidden" name="stick" value="%d" /><input type="text" name="message" value="" class="input_box" /></td></tr><tr><td valign="top"><input type="image" src="../images/send.gif" width="88" height="41" alt="send" border="0" /></form></td><td valign="top" align="right"><a href="javascript:exit_live_session(\'%s\');"><img src="../images/exit.gif" alt="exit" width="65" height="41" border="0" /></a></td></tr></table>', $sid, $nick, $bed, $stick, $sid);
    }
}
?>
</body>
</html>
