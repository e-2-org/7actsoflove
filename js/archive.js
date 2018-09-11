/*
archive.js
7 acts of love script file for archive
*/
var _x = 0;
var _y = 0;
var pinkblob = new Image(490, 151);
pinkblob.src = 'images/pink_blob.gif';
var blueblob = new Image(490, 151);
blueblob.src = 'images/blue_blob.gif';
var labels = new Object();
labels.saycheese
var roll = true;
var hotspots;
function move(evt) {
  var e = new xEvent(evt);
  e.pageX;
  e.pageY;
}
var mode = 'archive';
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
function showLabel(which, show)
{
    var targetImg = (document.layers) ? document.layers['label'].document.images['label'] : document.images['label'];
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
  popup.fullscreen=a[6]||false;
	if(document.layers){
    popup.features=popup.fullscreen?"outerWidth="+screen.width+",outerHeight="+screen.height+",screenX=0,screenY=0":"innerWidth="+popup.w+",innerHeight="+popup.h+",screenX="+popup.x+",screenY="+popup.y;
	}else{
    popup.features=popup.fullscreen?"width="+screen.width+",height="+screen.height+",left=0,top=0":"width="+popup.w+",height="+popup.h+",left="+popup.x+",top="+popup.y;
	}
	if(popup.extras!="") popup.features+=","+popup.extras;
  if((_console==null)||(_console.closed)){
	  _console=window.open(popup.url,"console",popup.features);
    _console.focus();
	}else{
	  _console.close();
    _console=window.open(popup.url,"console",popup.features);
    _console.focus();
    _console.moveTo(popup.x,popup.y);
	}
}