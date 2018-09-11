/*
sevenacts.js
7 acts of love script file
*/
function move(evt) {
  var e = new xEvent(evt);
  e.pageX;
  e.pageY;
}
function show_say_cheese(showit)
{
    var targetImg = (document.layers) ? document.layers['blob'].document.images['blobimage'] : document.images['blobimage'];
    if (showit) {
        xHide('marcus_drawing');
        xHide('archive_text');
        xShow('saycheese_photo');
        xShow('saycheese_text');
        targetImg.src = pinkblob.src;
    } else {
        if (mode == 'saycheese') return;
        xHide('saycheese_photo');
        xHide('saycheese_text');
        xShow('marcus_drawing');
        xShow('archive_text');
        targetImg.src = blueblob.src;
    }
}
//randomisation functions
function randomSort(a,b){
    return Math.floor(Math.random()*3)-1;
}
function randomSequence(num,start,limit){
    var currentNum=0;
    var idx=0;
    var numbers=new Array();
    var slots=new Array();
    for(i=start.valueOf();i<=limit.valueOf();i++){
        numbers[idx]=i;
        idx++;
    }
    numbers.sort(randomSort);
    for(i=0;i<num.valueOf();i++){
        if(currentNum==numbers.length){
            currentNum=0;
            numbers.sort(randomSort);
        }
        slots[i]=numbers[currentNum];
        currentNum++;
    }
    return slots.sort(randomSort)
};
//popup function
var _console = null;
function popup() {
  //create a simple popup with no window features
	//usage: popup(url,width,height,x-screen-position,y-screen-position,featurelist,fullscreen);
  if(arguments.length==0) return;
	var a=arguments;
	var popup=new Object;
	popup.url=a[0]||null;
	popup.w=a[1]||300;
	popup.h=a[2]||300;
	popup.x=a[3]||0;
	popup.y=a[4]||0;
	popup.extras=a[5]||"";
  popup.windowname=a[6]||"console";
  popup.fullscreen=a[7]||false;
	if(document.layers){
    popup.features=popup.fullscreen?"outerWidth="+screen.width+",outerHeight="+screen.height+",screenX=0,screenY=0":"innerWidth="+popup.w+",innerHeight="+popup.h+",screenX="+popup.x+",screenY="+popup.y;
	}else{
    popup.features=popup.fullscreen?"width="+screen.width+",height="+screen.height+",left=0,top=0":"width="+popup.w+",height="+popup.h+",left="+popup.x+",top="+popup.y;
	}
	if(popup.extras!="") popup.features+=","+popup.extras;
  if((_console==null)||(_console.closed)){
	  _console=window.open(popup.url,popup.windowname,popup.features);
    _console.focus();
	}else{
	  _console.close();
    _console=window.open(popup.url,popup.windowname,popup.features);
    _console.focus();
    _console.moveTo(popup.x,popup.y);
	}
}
/**
 * javascript functions for the bedrooms
 */
var selected_stick = false;
function overlay_stick(stick_id, bed_id)
{
    document.forms[0].stick.value = stick_id;
    for (var i = 1; i <= 7; i++) {
        if (i == stick_id) {
            xShow("stick"+i+"_"+bed_id);
            col = '#6699ff';
            selected_stick = stick_id;
        } else {
            xHide("stick"+i+"_"+bed_id);
            col = '#ffffff';
        }
        xBackground("stick_thumb"+i, col);
    }
}
var selected_bed = false;
function select_bedroom(id)
{
    document.forms[0].bed.value = id;
    var col;
    for (var i = 0; i < 15; i++) {
        if (i == id) {
            col = '#ff9999';
            selected_bed = id;
        } else {
            col = '#ffffff';
        }
        xBackground("bed_thumb"+i, col);
    }
}
function hilite_bed(id, o)
{
    if (selected_bed && selected_bed == id) return;
    col = o? '#ffcccc':'#ffffff';
    xBackground("bed_thumb"+id, col);
}
function hilite_stick(id, o)
{
    if (selected_stick && selected_stick == id) return;
    col = o? '#99ffff':'#ffffff';
    xBackground("stick_thumb"+id, col);
}
function go_to_position()
{
    var errorMsg = "";
    if (document.forms[0].bed.value == "") {
        errorMsg += '    - select a bed\n';
    }
    if (document.forms[0].nick.value == "" || document.forms[0].nick.value == "Type a name in here") {
        document.forms[0].nick.value = '';
        errorMsg += '    - type a name\n';
        document.forms[0].nick.focus();
    }
    if (arguments.length == 1) {
        //live
        if (document.forms[0].email.value == "" || document.forms[0].email.value == "Type your email address in here") {
            document.forms[0].email.value = '';
            if (!errorMsg) {
                document.forms[0].email.focus();
            }
            errorMsg += '    - type your email address\n';
        } else {
            var reEmail = /^.+\@.+\..+$/;
            if (!reEmail.test(document.forms[0].email.value)) {
                errorMsg += '    - check your email address.\n';
            }
        }
    }
    if (errorMsg) {
        alert('Please do the following to continue:\n' + errorMsg);
    } else {
        document.forms[0].submit();
    }
}
function go_to_therapist()
{
    var errorMsg = "";
    if (document.forms[0].stick.value == "") {
        alert('Please select a place to sit');
    } else {
        document.forms[0].submit();
    }
}
var reply_after = setTimeout("", 10000); 
function play_clip(id)
{
    clearTimeout(reply_after);
    document.forms['session_input'].message.value = "";
    document.forms['session_input'].message.focus();
    top.frames['soundsframe'].location = "therapist/sound"+id+".html";
    checkLoadProgress = setTimeout("check_sound()", 1000);
}
/*
main function to check user input, and make the decision as to where the user
proceeds to in the therapy session. Small responses to some questions will result
in "tell me more" or "mmm hmmm" responses. Uses a global variable to hold the
progress of the responses, and inserts the id of the current response into the 
form
3. require an answer which is more than 10 words long. Shorter responses result in "Mmm hmmm..."
6. - if Yes, trigger "tell me more", if no, trigger "Mmmm..." (in other words, probe for a longer response)
7. require an answer which is more than 10 words long. Shorter responses result in "Mmm hmmm..."
9. - if No, trigger "Mmm hmmm... that's interesting...", if less than 10 words long, trigger "tell me more"
10. Single word responses result in "Mmm hmmm..."
12. if Yes, trigger "tell me more"
13. if Yes, trigger "Mmm hmmm..."
16. require an answer which is more than 10 words long. Shorter responses result in "Mmm hmmm..."
18. Single word responses result in "tell me more..."
19. if Yes, trigger "Aha..."
22. if Yes, trigger "Mmm hmmm... that's interesting...", if less than 10 words long, trigger "Tell me more..."
25. require an answer which is more than 10 words long. Shorter responses result in "Mmm hmmm..."
26. if Yes, trigger "Tell me more about that..."
29. require an answer which is more than 10 words long. Shorter responses result in "tell me more..."
30. if Yes, trigger "Mmm hmmm..."
32. if Yes, trigger "Mmm hmmm, tell me more..."
34. require an answer which is more than 10 words long. Shorter responses result in "Interesting..."
37. if Yes, trigger "tell me more..."
40. require an answer which is more than 10 words long. Shorter responses result in "Mmm..."
41. Single word responses result in "Mmm..."
43. if Yes, trigger "tell me more"
44. Single word responses result in "Mmm..."
*/
var response_id = 0;
var previous_response = 0;
var next_response;
var session_active = true;
function check_live_input()
{
    var msg = document.forms['session_input'].message.value;
    if (msg == "") {
        alert('Please type your response\nin the box, then press "Send"');
        return false;
    }
    return true;
}
function check_live_input_marcus()
{
    var msg = document.forms['session_input'].message.value;
    if (msg == "") {
        alert('Please type your response\nin the box, then press "Send"');
        return false;
    }
    if (document.forms['session_input'].sid.value == "") {
        alert('Please select a user below to start the conversation');
        return false;
    }
    return true;
}
function check_input()
{
    if ((window.sessionframe.location.search.indexOf("start") == -1) && response_id == 0) {
        document.forms['session_input'].message.focus();
        return false;
    }
    var msg = document.forms['session_input'].message.value;
    if (msg == "") {
        alert('Please type your response\nin the box, then press "Send"');
        return false;
    }
    msg = msg.toLowerCase();
    var words = msg.split(" ");
    var msglength = msg.length;
    var wordcount = words.length;
    document.forms['session_input'].response_to.value = response_id;
    switch (response_id) {
    case 3:
        next_response = (wordcount <= 5)? 4: 6;
        break;
    case 4:
        next_response = (wordcount <= 5)? 5: 6;
        break;
    case 6:
        next_response = (msg == "yes" || wordcount == 1)? 15: (msg == "no")? 8: 7;
        break;
    case 7:
        next_response = (wordcount <= 10)? 38: 9;
        break;
    case 8:
        next_response = 7;
        break;
    case 9:
        next_response = (msg == "no")? 23: (wordcount <= 10)? 33: 10;
        break;
    case 10:
        next_response = (wordcount == 1)? 11: 12
        break;
    case 12:
        next_response = (msg == "yes")? 15: 13;
        break;
    case 13:
        next_response = (msg == "yes")? 14: 16;
        break;
    case 14:
        next_response = 16;
        break;
    case 15:
        next_response = (previous_response == 6)? 7: 13;
        break;
    case 16:
        next_response = (wordcount <= 10)? 17: 18;
        break;
    case 18:
        next_response = (wordcount == 1)? 21: 19
        break;
    case 19:
        next_response = (wordcount == 1)? 20: 22;
        break;
    case 20:
        next_response = 22;
        break;
    case 21:
        next_response = 19;
        break;
    case 22:
        next_response = (wordcount <= 10)? 24: 25;
        break;
    case 23:
        next_response = (previous_response == 9)? 10: 25;
        break;
    case 25:
        next_response = (wordcount <= 10)? 27: 26;
        break;
    case 26:
        next_response = (msg == "yes")? 28: 29;
        break;
    case 27:
        next_response = 26;
        break;
    case 29:
        next_response = (wordcount <= 10)? 33: 30;
        break;
    case 30:
        next_response = (msg == "yes")? 31: 32;
        break;
    case 32:
        next_response = (msg == "yes")? 33: 34;
        break;
    case 33:
        next_response = (previous_response == 9)? 10: (previous_response == 29)? 30: 34;
        break;
    case 34:
        next_response = (wordcount <= 10)? 36: 37;
        break;
    case 37:
        next_response = (msg == "yes")? 39: 40;
        break;
    case 38:
        next_response = 9;
        break;
    case 40:
        next_response = (wordcount <= 10)? 42: 41;
        break;
    case 41:
        next_response = (wordcount == 1)? 54: 43;
        break;
    case 42:
        next_response = 41;
        break;
    case 43:
        next_response = (msg == "yes")? 55: 44;
        break;
    case 44:
        next_response = (wordcount == 1)? 45: 46;
        break;
    case 47:
        // end of the session
        session_active = false;
        setTimeout("exit()", 10000);   
        break;
    case 54:
        next_response = 43;
        break;
    case 55:
        next_response = 44;
        break;
    default:
        next_response = (response_id + 1);   
        break;
    }
    document.forms['therapist_input'].next_response.value = next_response;
    reply_after = setTimeout("play_clip("+next_response+")", 1000);
    previous_response = response_id;
    response_id = next_response;
    return true;
}
function exit_live_session(sid)
{
    top.frames["sessionframe"].location.href = 'session.php?sid='+sid+'&exit=1';
}
function exit_live_session_marcus(sid)
{
    var sid = top.window.document.forms['session_input'].sid.value;
    if (sid == "") {
        alert("but you aren't talking to anyone yet!");
    } else {
        top.frames["userframe"].location.href = 'user_list.php?sid='+sid+'&exit=1';
    }
}
var exitTimer = setTimeout("", 1000);
var alerted = false;
function exit_session()
{
    deleteCookie("sid");
    clearTimeout(exitTimer);
    if ((window.sessionframe.location.search.indexOf("start") == -1) && response_id == 0) {
        if (!alerted) {
            alert("You will be re-directed to the home page when the introduction has finished");
            alerted = true;
        }
        exitTimer = setTimeout("exit_session()", 1000);
        return;
    }
    document.forms['therapist_input'].next_response.value = 47;
    var InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;
    var mc = InternetExplorer ? top.window.therapist_start : top.window.document.therapist_start;
    mc.StopPlay();
    reply_after = setTimeout("play_clip(47)", 1000);
    document.forms['therapist_input'].submit();
    setTimeout("exit()", 10000);   
}
function exit()
{
    if (session_active) deleteCookie("sid");
    window.location = 'index.html';
}
/*
Checks the percentage load of the therapist voice flash file, and triggers the
sending of data to session.php when the file has finished loading
*/
var checkLoadProgress = setTimeout("", 10000);
function check_sound()
{
    clearTimeout(checkLoadProgress);
    var InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;
    var mc = InternetExplorer ? top.frames['soundsframe'].therapist : top.frames['soundsframe'].document.therapist;
    if (mc && mc.PercentLoaded() == 100) {
        send_response();
        return;
    }
    checkLoadProgress = setTimeout("check_sound()", 100);
}
/*
sends a response to user input to session.php so the text of the therapist
shows in the chatbox at the same time as the voice starts. Called by check_sound()
when the flash movie loading is completed
*/
function send_response()
{
    document.forms['therapist_input'].submit();
}

// name - name of the cookie
// value - value of the cookie
// [expires] - expiration date of the cookie (defaults to end of current session)
// [path] - path for which the cookie is valid (defaults to path of calling document)
// [domain] - domain for which the cookie is valid (defaults to domain of calling document)
// [secure] - Boolean value indicating if the cookie transmission requires a secure transmission
// * an argument defaults when it is assigned null as a placeholder
// * a null placeholder is not required for trailing omitted arguments
function setCookie(name, value, expires, path, domain, secure) {
  path = "/";
  var curCookie = name + "=" + escape(value) +
      ((expires) ? "; expires=" + expires.toGMTString() : "") +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      ((secure) ? "; secure" : "");
  document.cookie = curCookie;
}

// name - name of the desired cookie
// * return string containing value of specified cookie or null if cookie does not exist
function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else
    begin += 2;
  var end = document.cookie.indexOf(";", begin);
  if (end == -1)
    end = dc.length;
  return unescape(dc.substring(begin + prefix.length, end));
}

// name - name of the cookie
// [path] - path of the cookie (must be same as path used to create cookie)
// [domain] - domain of the cookie (must be same as domain used to create cookie)
// * path and domain default if assigned null or omitted if no explicit argument proceeds
function deleteCookie(name, path, domain) {
  if (getCookie(name)) {
    document.cookie = name + "=" + 
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}

// date - any instance of the Date object
// * hand all instances of the Date object to this function for "repairs"
function fixDate(date) {
  var base = new Date(0);
  var skew = base.getTime();
  if (skew > 0)
    date.setTime(date.getTime() - skew);
}
