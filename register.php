<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>7 acts of love</title>
<style type="text/css">
body, p {
  font:12pt Geneva,Arial,Helvetica,sans-serif;
  color:#666;
}
a:link, a:active, a:visited {
  font:12pt Geneva,Arial,Helvetica,sans-serif;
  color:#999;
  background-color:#fff;
  text-decoration:none;
}
a:hover, a:visited:hover {
  font:12pt Geneva,Arial,Helvetica,sans-serif;
  color:#999;
  background-color:#fcf;
  text-decoration:none;
}
</style>
</head>
<body>
<?php
if (isset($_POST["email"])) {
    if (looks_like_an_email(trim($_POST["email"]))) {
        mail("tech@e-2.org", "7 acts registration", trim($_POST["email"]));
        print('<p>Thanks for registering.</p><p>You will get an email invitation to take part in the live residency when it takes place.</p><a href="javascript:self.close()">[close window]</a>');
    } else {
        print('<p>Sorry...</p><p>The email address: ' . trim($_POST["email"]) . ' doesn\'t look valid. Please <a href="register.php">try again</a>.</p>');
    }
} else {
?>
<form method="post" action="register.php">
<p>Please type in your email address and click on the register button</p>
<input type="text" name="email" value="" /><input type="submit" value="register" />
</form>
<?php
}
?>
</body>
</html>
<?php
function looks_like_an_email($email) {
   $pattern="^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dev|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|home|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|loc|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.) {3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$";
   if (eregi($pattern, $email)) {
        return true;
    } else {
        return false;
    }
}
?>
