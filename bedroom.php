<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript" type="text/javascript" src="js/sevenacts.js"></script>
<script language="JavaScript" type="text/javascript" src="js/x.js"></script>
<script language="JavaScript" type="text/javascript" src="js/x_util.js"></script>
<link rel="stylesheet" href="css/sevenacts.css" type="text/css" />
<?php
require_once("includes/config.php");
if (!isset($_POST["sid"])) {
    // first arrival to this page
    // generate a unique id and store as a cookie
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    $sid = md5(uniqid(mt_rand()));
?>
<script language="JavaScript" type="text/javascript">
<!--
var now = new Date();
fixDate(now);
now.setTime(now.getTime() + (7 * 24 * 60 * 60 * 1000));
setCookie("sid", "<?php echo $sid; ?>", now, "/");
//-->
</script>
<?php
    // print a selection of bedrooms
    print('<title>Choose a bedroom</title></head><body><img src="images/choose_a_bedroom.gif" alt="Choose a bedroom" width="245" height="76" border="0" /><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" width="100%"><table align="center" summary="select the bedroom you would like to go to" cellpadding="0" cellspacing="0" border="0"><tr>');
    for ($i = 1; $i <= $config["bedrooms"]; $i++) {
        $image_path = $config["image_dir"] . $config["path_sep"] . "bed_thumbs" . $config["path_sep"] . "bedroom" . $i . ".jpg";
        if (file_exists($image_path)) {
            $image_info = getimagesize($image_path);
            printf('<td><div id="bed_thumb%d" class="bed_thumbs"><a href="javascript:select_bedroom(%d);" onmouseover="hilite_bed(%d, true);" onmouseout="hilite_bed(%d, false);"><img src="images/bed_thumbs/bedroom%d.jpg" alt="bedroom %d" width="150" height="%d" border="0" /></a></div></td>', $i, $i, $i, $i, $i, $i, round((150 / $image_info[0]) * $image_info[1]));
        } else {
            printf("<td><p>image %d does not appear to exist</p></td>", $i);
        } 
        if (($i > 1) && ($i % 4 == 0)) {
            print('</tr><tr>');
        }
        if ($i == $config["bedrooms"]) {
            print('<td colspan="2" valign="middle" align="center"><form name="user" action="bedroom.php" method="post">');
            printf('<input type="hidden" name="sid" value="%s" /><input type="hidden" name="bed" value="" /><input type="text" name="nick" value="Type a name in here" onfocus="this.value=\'\'" onblur="if(this.value==\'\'){this.value=\'Type a name in here\';}" /><a href="javascript:go_to_position();"><img src="images/next.gif" alt="next" width="88" height="41" border="0" /></a></td></tr>', $sid);
        }
    }
    print('</table></td></tr></table>');
} else {
    //second time at this page
    $sid = $_POST["sid"];
    $bedroom = $_POST["bed"];
    $nick = stripslashes($_POST["nick"]);
    $bedroom_x = 190;
    $bedroom_y = 20;
    //second arrival to this page - print a selection of stick figures to overlay on the bedroom image
    print('<title>Where would you like to sit?</title>');
    print('</head><body><table summary="header" cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td valign="top" align="left"><img src="images/where_to_sit.gif" alt="Where would you like to sit?" width="245" height="76" border="0" /></td><td valign="top" align="right"><a href="javascript:go_to_therapist();"><img src="images/enter.gif" alt="enter" width="88" height="41" border="0" style="margin-right:50px;" /></a></td></tr></table>');
    print('<table summary="Where would you like to sit?" cellpadding="0" cellspacing="0" border="0">');
    $sticklayers = '';
    for ($i = 1; $i <= 7; $i++) {
        $image_path = $config["image_dir"] . $config["path_sep"] . "stick" . $i . $config["path_sep"] . "bedroom" . $bedroom . ".gif";
        $image_info = getimagesize($image_path);
        $thumb_path = $config["image_dir"] . $config["path_sep"] . "stick" . $i . ".gif";
        $thumb_info = getimagesize($thumb_path);
        $sticklayers .= sprintf('<div id="stick%d_%d" class="sticklayer"><img src="images/stick%d/bedroom%d.gif" alt="stick figure" width="%d" height="%d" border="0" /></div>',$i , $bedroom, $i, $bedroom, $image_info[0], $image_info[1]);
        printf('<tr><td><div id="stick_thumb%d" class="stickthumb"><a href="javascript:overlay_stick(%d, %d);" onmouseover="hilite_stick(%d, true);" onmouseout="hilite_stick(%d, false);"><img src="images/stick%d.gif" alt="click on the figure to choose this position in the bedroom" width="%d" height="%d" border="0" /></a></div></td></tr>', $i, $i, $bedroom, $i, $i, $i, $thumb_info[0], $thumb_info[1]);
        if ($i == 7) {
           //last cell
           printf('<tr><td><form action="therapist.php" method="post"><input type="hidden" name="sid" value="%s" /><input type="hidden" name="bed" value="%d" /><input type="hidden" name="stick" value="" /><input type="hidden" name="nick" value="%s" /></form></td></tr>', $sid, $bedroom, $nick);
        }
    }
    print('</table>');
    $image_path = $config["image_dir"] . $config["path_sep"] . "bedroom" . $bedroom . ".jpg";
    if (file_exists($image_path)) {
        $image_info = getimagesize($image_path);
        printf('<div id="bedroom_image"><img src="images/bedroom%d.jpg" alt="bedroom image" width="%d" height="%d" border="0" /></div>', $bedroom, $image_info[0], $image_info[1]);
    }
    print($sticklayers);
}   
?>
</body>
</html>
