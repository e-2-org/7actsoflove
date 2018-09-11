<?php
$email_text = <<<EOTXT
7 Acts of Love
a live art web based project by Oreet Ashery commissioned by e-2

Launch

Friday 5th March 2004
6.30 - 8.30 pm
At the Great Eastern Hotel, Liverpool Street, London EC2M 7QN

For the past two years Oreet Ashery's alter ego, Marcus Fisher, has been
engaged with the live interaction Say Cheese. These one-to-one
interactions have taken place in seven different bedrooms, in seven
different cities all over the world.

7 acts of love online is a new work commissioned by internet arts
organisation e-2, which creates an interface between the live
project and the medium of the internet.

7 acts of love online develops the idea of the one-to-one interactions and
takes loss, separation and therapy as its themes.

The launch of the new work on Friday 5 March will take place on the first
floor of the Great Eastern Hotel. The work can be seen in three bedrooms.

Free bar for first hour.

1 minute walk from Liverpool St tube

For further information contact sue@e-2.org

EOTXT;
$email_html = <<EOHTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>7actsoflove</title>

</head>


<body bgcolor="#FFFFFF"  leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top"  width="101">
<img src="http://www.e-2.org/7acts/html_email/illustration_plant_lge.gif" width="101" height="331" alt="" border="0"><br><img src="http://www.e-2.org/7acts/html_email/spacer.gif" width="120" height="1" alt="" border="0"></td><td width="290"><br>
<br><img src="spacer.gif" width="290" height="1" alt="" border="0"><br>
<font style="font-family:arial;font-size:25px;color:#000000;">7 Acts of Love</font><br>
<font style="font-family:arial;font-size:11px;color:#000000;">a live art web based project by Oreet Ashery<br>
commissioned by e-2</font><br><br>
<font style="font-family:verdana;font-size:13px;font-weight:bold;color:#000000;background:#FEF0F6">Launch<br>
Friday 5th March 2004<br>
6.30 - 8.30 pm<br><br></font>
<font style="font-family:verdana;font-size:11px;color:#000000;background:#FEF0F6">At the Great Eastern Hotel, Liverpool Street <br>London EC2M 7QN</font><br>
<br>
<font style="font-family:verdana;font-size:10px;color:#000000;">
For the past two years Oreet Ashery's alter ego, Marcus Fisher, has been
engaged with the live interaction Say Cheese. These one-to-one
interactions have taken place in seven different bedrooms, in seven
different cities all over the world.
<br><br>
7 acts of love online is a new work commissioned by internet arts
organisation e-2, which creates an interface between the live
project and the medium of the internet.

7 acts of love online develops the idea of the one-to-one interactions and
takes loss, separation and therapy as its themes.
<br><br>
The launch of the new work on Friday 5 March will take place on the first
floor of the Great Eastern Hotel. The work can be seen in three bedrooms.
Free bar for first hour.</font><br><br><br>
<font style="font-family:verdana;font-size:9px;color:#000000;">1 minute walk from Liverpool St tube</font><br>
<font style="font-family:verdana;font-size:9px;color:#000000;text-decoration:none;">For further information contact <a class="link" href="mailto:sue@e-2.org" style="font-family:verdana;font-size:9px;color:#666666;text-decoration:none;">sue@e-2.org</a></font>
<br><br><br>
<img src="http://www.e-2.org/7acts/html_email/logos.gif"  alt="" border="0">

</td>
<td valign="bottom"><img src="http://www.e-2.org/7acts/html_email/illustration_oreet_sml.gif" width="179" height="417" alt="" border="0" vspace="25" hspace="10"></td>
</tr></table>
</body>
</html>
EOHTML;
$addresseess = array();
if (isset($_GET["email"])) {
    $addressee = array("fn" => "", "sn" => "", "email" => stripslashes($_GET["email"]));
    array_push($addressees, $addressee);
} else {
    print('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><title>Send a 7 acts email to someone</title></head><body><form method="get"><input type="text" name="email" value="" /><input type="submit" value="send" /></form></body></html>');
    exit;
}
for ($i = 0; $i < count($addressees); $i++
    if (looks_like_an_email($addressees[$i]["email"])) {
        $headers = array("From"     => "mailer@7actsoflove.org",
                         "Subject"  => "Seven Acts of Love",
                         "X-Mailer" => "7 acts auto-mailer");
        require("Mail.php");
        require("Mail/mime.php");
        $msg = new Mail_mime("\r\n");
        $msg->setTXTBody($email_text);
        $msg->setHTMLBody($email_html);
        $msg_body = $msg->get();
        $msg_headers = $msg->headers($headers);
        $mail =& Mail::factory('mail');
        $mail->send($addressees[$i]["email"], $msg_headers, $msg_body);
        print('Success!<br />The card has been sent to ' . $addressees[$i]["email"] . '<br /><a href="mailer.php">Click here to return to send some more</a>');
    } else {
        print('Sorry!<br />The email address: ' . $addressees[$i]["email"] . ' looks a bit fishy.<br /><a href="mailer.php">Click here to try again</a>');
    }
}
function looks_like_an_email($email) {
    $pattern = "'^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$'i";
    if (eregi($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}
?>
