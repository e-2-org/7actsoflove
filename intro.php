<html>
<head>
<meta http-equiv="expires" content="31 Dec 1990">
<title>Develooping Flash Chat</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 
/*	develooping flash chat	    	                    */
/*	by Juan Carlos PosŽ                                 */
/*	juancarlos@develooping.com	                        */
/*	version 1.5.3	                                    */
require ('chat/required/config.php');
if(eregi("win", $HTTP_USER_AGENT) and eregi("MSIE", $HTTP_USER_AGENT)){
$browser= "explorerwin";
}
else{
$browser= "";
}
?>
<script language="JavaScript">
<!--
function check_the_form() { 
  var the_error='';
  var the_error_name='';
  var the_error_password='';
  var the_person=document.the_form.person.value;
  var the_password=document.the_form.password.value;
  var validperson=" abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
<?php 
if ($password_system=="ip"){
?>
  var validpassword=" 0123456789.";
<?php 
}else{
?>
var validpassword="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
<?php
}
?>
if (the_person.length < 4){
the_error = "<?php echo $alert_message_1;?>\n";
}
<?php 
if ($password_system!="ip"){
?>
if (the_password.length < 4){
the_error = the_error + "<?php echo $alert_message_2;?>\n";
}
<?php 
}
?>   
for (var i=0; i<the_person.length; i++) {
   if (validperson.indexOf(the_person.charAt(i)) < 0) {
         the_error_name = "<?php echo $alert_message_3;?>\n";
        }
    }  
for (var i=0; i<the_password.length; i++) {
   if (validpassword.indexOf(the_password.charAt(i)) < 0) {
    <?php 
if ($password_system=="ip"){
?>
the_error_password = "<?php echo $alert_message_4;?>\n";
<?php 
}else{
?>
the_error_password = "<?php echo $alert_message_5;?>\n";
<?php 
}
?>
        }
    }  
the_error = the_error + the_error_name + the_error_password ;  
if (the_error!=''){alert('<?php echo $intro_alert;?>\t\t\t\t\t\n\n'+the_error)}
  document.return_the_value = (the_error=='');
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF">
 <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0"
 ID="counter" WIDTH=475 HEIGHT=20>
 <PARAM NAME=movie VALUE="chat/count_users.swf?browser=<?php echo $browser;?>"><PARAM NAME=menu VALUE=false><PARAM NAME=quality VALUE=best><PARAM NAME=wmode VALUE=transparent><EMBED name="counter" src="chat/count_users.swf?browser=<?php echo $browser;?>" menu=false quality=best wmode=transparent WIDTH=475 HEIGHT=20 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" swLiveConnect="true"></EMBED>
</OBJECT>
<br>
<form name="the_form" action="chat/index.php" method="POST" onSubmit="check_the_form();return document.return_the_value">
  <font face="Verdana, Arial, Helvetica, sans-serif" size="1">
  <?php echo htmlentities($enter_sentence_1);
if ($password_system!="ip"){
echo htmlentities($enter_sentence_2);
}
echo htmlentities($enter_sentence_3);
?>
</font> 
  <table width="475" border="0" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top"> 
      <td colspan="5"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><br>
        </font></td>
    </tr>
    <tr>
    <td bgcolor="#FFFFFF" width="10"><img src="chat/graphics/left.gif" width="10" heigth="30"></td>
      <td align="right" bgcolor="#CECECE"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php if($nametaken==1 and $name_taken){echo"<font color='#9900000'>".$name_taken;}else{echo htmlentities($name_word);}if($nametaken==1){echo"&nbsp;</font>";};?> </font></td>
      <td align="left" bgcolor="#CECECE"> 
        <input type="text" name="person" maxlength="12" size="8" style="background:#CCCCCC; width:50">
        <?php 
if ($password_system=="ip"){

if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP"); 
else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR"); 
else $ip = "UNKNOWN"; 


?>
        <input type="hidden" name="password" value="<?php echo $ip;?>">
<?php 
}else{
?>
          </td><td align="right" bgcolor="#CECECE"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php echo htmlentities($password_word);?> </font></td>
      <td align="left" bgcolor="#CECECE"> 
        <input type="text" name="password" maxlength="12" size="8" style="background:#CCCCCC; width:50">
<?php 
}
?>
      </td>
      <td align="right" bgcolor="#CECECE"> 
        <input type="submit" name="Submit" value="<?php echo htmlentities($enter_button);?>" style="background:#CCCCCC; width:75">
      </td>
      <td bgcolor="#FFFFFF" width="10"><img src="chat/graphics/right.gif" width="10" heigth="30"></td>
    </tr>
    <tr align="left" valign="top"> 
<?php 
if ($password_system=="ip"){
?>
<td colspan="5">
<?php 
}else{
?>
<td colspan="7">
<?php 
}
?>       
<hr noshade size="1">
        <font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?php echo htmlentities($require_sentence);?>.- </font><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#990000">Develooping Flash Chat</font></b> v 1.5.3</font>
      </td>
    </tr>
  </table>
        </form>
</body>
</html>
