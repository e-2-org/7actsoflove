<?php
require_once("../../includes/functions.php");
$sid = md5("oreet");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="JavaScript" type="text/javascript" src="../../js/sevenacts.js"></script>
<script language="JavaScript" type="text/javascript" src="../../js/x.js"></script>
<script language="JavaScript" type="text/javascript" src="../../js/x_util.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function go_to_lobby()
{
  var nick = document.forms['lobby'].nick.value;
  if (nick == "") {
    alert("please enter a handle");
    return;
  }
  window.open('../lobby.php?sid=<?php echo $sid ?>&nick='+nick, 'lobby', 'width=800,height=600');
}
var enablebell = true;
function playbell()
{
    if (enablebell) {
        window.document.bell.Play();
    }
}
function stopbell()
{
    enablebell = false;
}
function startbell()
{
    enablebell = true;
}
function checkstatus()
{
    var msg = "entry bell is ";
    msg += enablebell? 'enabled':'disabled';
    alert(msg);
}
//-->
</script>
<link rel="stylesheet" href="../../css/sevenacts.css" type="text/css" />
<style type="text/css">
<!--
#user_list {
  position:absolute;
  left:600px;
  top:20px;
}
#messages {
  position:absolute;
  left:300px;
  top:350px;
}
-->
</style>

<title>7 acts of love live</title>
</head>
<body>
<div class="therapy_bed" id="t_bed"><img src="../images/blank.gif" id="bed" name="bed" width="400" height="320" alt="bedroom" border="0" /></div>
<div class="therapy_bed" id="t_position"><img src="../images/blank.gif" id="stick" name="stick" width="400" height="320" alt="position" border="0" /></div>
<table summary="therapy session" width="180" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td valign="top" width="180" colspan="2">
      <iframe src="session.php" id="sessionframe" frameborder="0" name="sessionframe" width="180" height="400"></iframe>
    </td>
  </tr>
  <tr>
    <td valign="top" colspan="2">
    <form name="session_input" id="session_input" action="session.php#foot" target="sessionframe" method="post" onsubmit="return check_live_input_marcus();">
    <input type="hidden" name="sid" value="" />
    <input type="hidden" name="nick" value="marcus" />
    <input type="text" name="message" value="" class="input_box" />
    </td>
  </tr>
  <tr>
    <td valign="top">
    <input type="image" src="../../images/send.gif" width="88" height="41" alt="send" border="0" />
    </form>
    </td>
    <td valign="top" align="right">
    <a href="javascript:exit_live_session_marcus();">
      <img src="../../images/exit.gif" alt="exit" width="65" height="41" border="0" />
    </a>
    </td>
  </tr>
  <tr>
    <td valign="top" colspan="2">
      <form name="lobby" id="lobby">
      <p style="font:10px verdana,sans-serif"><input type="text" size="6" name="nick" value="" /><input type="button" onclick="go_to_lobby();" value="go to lobby" /></p>
      </form>
      <p style="font:10px verdana,sans-serif">entry bell:<br />
      - <a href="javascript:stopbell();">disable</a><br />
      - <a href="javascript:startbell();">enable</a><br />
      - <a href="javascript:checkstatus();">check status</a></p>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="10" height="10" id="bell" align=""><param name="movie" value="bell.swf" /><param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF" /><embed src="bell.swf" loop="false" menu="false" quality="high" bgcolor="#FFFFFF" swliveconnect="true" width="10" height="10" name="bell" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>
    </td>
  </tr>
</table>
<div id="user_list">
<iframe src="user_list.php" id="userframe" frameborder="0" name="userframe" width="180" height="200"></iframe>
</div>
<div id="messages">
<iframe src="messages.php" id="messagesframe" frameborder="0" name="messagesframe" width="400" height="200"></iframe>
</div>
</body>
</html>
