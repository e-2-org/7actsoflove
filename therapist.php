<?php
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
require_once("includes/config.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/javascript" src="js/sevenacts.js"></script>
<script language="JavaScript" type="text/javascript" src="js/x.js"></script>
<script language="JavaScript" type="text/javascript" src="js/x_util.js"></script>
<link rel="stylesheet" href="css/sevenacts.css" type="text/css" />
<title></title>
</head>
<body>
<?php
if (!isset($_POST["sid"]) || !isset($_POST["bed"]) || !isset($_POST["stick"])) {
    print('<body><p>Please <a href="bedroom.php">go back to the beginning</a>.</p>');
} else {
    $bed = $_POST["bed"];
    $stick = $_POST["stick"];
    $sid = $_POST["sid"];
    $session_start = time();
    $nick = stripslashes($_POST["nick"]);
    $bed_path = $config["image_dir"] . $config["path_sep"] . "bedroom" . $bed . ".jpg";
    $bed_info = getimagesize($bed_path);
    print('<body onload="document.forms[\'session_input\'].message.focus();" onunload="check_finished();">');
    printf('<div class="therapy_bed" id="t_bed"><img src="images/bedroom%d.jpg" alt="bedroom" width="%d" height="%d" border="0" /></div>', $bed, $bed_info[0], $bed_info[1]);
    printf('<div class="therapy_bed" id="t_position"><img src="images/stick%d/bedroom%d.gif" width="%d" height="%d" alt="position" border="0" /></div>', $stick, $bed, $bed_info[0], $bed_info[1]);
    printf('<table summary="therapy session" width="180" cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="180" colspan="2"><iframe src="session.php?nick=%s" id="sessionframe" frameborder="0" name="sessionframe" width="180" height="400"></iframe></td></tr>', $nick);
    printf('<tr><td valign="top" colspan="2"><form name="session_input" id="session_input" action="session.php#foot" target="sessionframe" method="post" onsubmit="return check_input();"><input type="hidden" name="sid" value="%s" /><input type="hidden" name="nick" value="%s" /><input type="hidden" name="bed" value="%d" /><input type="hidden" name="stick" value="%d" /><input type="hidden" name="session_start" value="%d" /><input type="hidden" name="response_to" value="" /><input type="text" name="message" value="" class="input_box" /><input type="hidden" name="send" value="1" /></td></tr><tr><td valign="top"><input type="image" src="images/send.gif" width="88" height="41" alt="send" border="0" /></form></td><td valign="top" align="right"><a href="javascript:exit_session();"><img src="images/exit.gif" alt="exit" width="65" height="41" border="0" /></a></td></tr></table>', $sid, $nick, $bed, $stick, $session_start);
    printf('<iframe scrolling="no" src="sounds.php?nick=%s" id="soundsframe" name="soundsframe" frameborder="0" width="100" height="100"></iframe>', $nick);
    print('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="10" height="10" id="therapist_start" align="">');
    print('<param name="movie" value="start.swf" />');
    printf('<param name="flashvars" value="nick=%s" />', $nick);
    print('<param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF" />');
    printf('<embed src="start.swf" loop="false" menu="false" quality="high" bgcolor="#FFFFFF" swliveconnect="true" width="10" height="10" name="therapist_start" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="nick=%s" /></object>', $nick);
    printf('<form name="therapist_input" id="therapist_input" action="session.php#foot" method="post" target="sessionframe"><input type="hidden" name="sid" value="%s" /><input type="hidden" name="nick" value="%s" /><input type="hidden" name="session_start" value="%d" /><input type="hidden" name="next_response" value="" /><input type="hidden" name="send" value="1" /></form>', $sid, $nick, $session_start);
}
?>
</body>
</html>
