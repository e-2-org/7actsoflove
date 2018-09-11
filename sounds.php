<?php
/**
 * sounds.php
 */
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
print('<html><head><title>Therapist sounds</title></head><body>');
if (isset($_GET["id"])) {
    $i = (int) $_GET["id"];
    printf('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="10" height="10" id="therapist" align="">', $i);
    printf('<param name="movie" value="therapist/s%d_therapist.swf" />', $i);
    print('<param name="loop" value="false" /><param name="menu" value="false" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF" />');
    printf('<embed src="therapist/s%d_therapist.swf" loop="false" menu="false" quality="high" bgcolor="#FFFFFF" swliveconnect="true" width="10" height="10" name="therapist" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>', $i, $i);
}
print('</body></html>');
?>
