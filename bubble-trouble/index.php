<?


?>
<body>
<head>
<title>Bubble Trouble</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width; height=device-height; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link rel="apple-touch-icon-precomposed" href="appIcon.png">
<link rel="apple-touch-startup-image" href="appLoading.png">

<style type="text/css">
* {
        color:white;
        margin:0;
        padding:0;
        border:0;
}
body { background-color:0; }
#main {
	width:272px;
	height:208px;
	left:32;
	top:0;
	overflow:hidden;
	z-index:20;
}
#main * {
	position:absolute;
	z-index:20;
}

#stoepsel {
	position:absolute;
	background:url(stoepsel.gif) no-repeat 0 15px;
	left:160px;
	top:176px;
	width:24px;
	height:27px;
}

#waterbar {
	background-color:#004671;
}



</style>
<script type="text/javascript">
function $(name) {
			if (document.getElementById)  {
				return document.getElementById(name);
			} else if (document.all) {
				return document.all[name];
			} else if (document.layers) {
				return document.layers[name];
			}
			return null;
}

Array.prototype.inArray = function (value) {
	var i;
	for (i=0; i < this.length; i++) {
		if (this[i] === value) {
			return true;
		}
	}
	return false;
};

function toggle(node) {
	var el = getElement(node);
	if ( el.style.display != 'none' ) {
		el.style.display = 'none';
	} else {
		el.style.display = '';
	}
}

function HSV2RGB(h,s,v){
 //***Returns an rgb object from HSV values
 //***h (hue) should be a value from 0 to 360
 //***s (saturation) and v (value) should be a value between 0 and 1
 //***The .r, .g, and .b properties of the returned object are all in the range 0 to 1
 var r,g,b,i,f,p,q,t;
 while (h<0) h+=360;
 h%=360;
 s=s>1?1:s<0?0:s;
 v=v>1?1:v<0?0:v;

 if (s==0) r=g=b=v;
 else {
  h/=60;
  f=h-(i=Math.floor(h));
  p=v*(1-s);
  q=v*(1-s*f);
  t=v*(1-s*(1-f));
  switch (i) {
   case 0:r=v; g=t; b=p; break;
   case 1:r=q; g=v; b=p; break;
   case 2:r=p; g=v; b=t; break;
   case 3:r=p; g=q; b=v; break;
   case 4:r=t; g=p; b=v; break;
   case 5:r=v; g=p; b=q; break;
  }
 }
 return {r:255*r,g:255*g,b:255*b};
}

/////////////////////////////////////////////////////

var dude = {'x':0,'y':0, 'kl':0,'kr':0,'kd':0, 'bubbles':0,'l':8,'el':null};
var croc = [{'x':0,'y':0,'s':0,'el':null,'dx':0,'dy':0,'t':0,'i':null,'ldx':0},
{'x':0,'y':0,'s':0,'el':null,'dx':0,'dy':0,'t':0,'i':null,'ldx':0}];
var soap = {'y':0,'time':0,'el':null};
var duck = {'x':0,'el':null};
var bubbles = [{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0},
{'x':0,'y':0,'vis':0}]
var level;
var tick;

function colors() {
    var c = HSV2RGB(Math.random()*180,0.5,0.2);
	for (var i=0;i<7;i++) {
		var j=(i*0.4)+1;
		$('waterbar'+(6-i)).style.backgroundColor='rgb('+Math.round(c.r*j)+','+Math.round(c.g*j)+','+Math.round(c.b*j)+')';
	}
	var i=3.5;

    var c = HSV2RGB(Math.random()*180,0.4,0.2);
	for (var i=0;i<6;i++) {
		var j=4+(0.7*(7-i));
		$('skybar'+i).style.backgroundColor='rgb('+Math.round(c.r*j)+','+Math.round(c.g*j)+','+Math.round(c.b*j)+')';
	}
}

function keyp(e,v) {
  var evt=(e)?e:window.event;
   switch(evt.keyCode) {
      case 39: dude.kl=v; break;
      case 40: dude.ku=v; break;
      case 37: dude.kr=v; break;
   }
}

function dudeKi() {
	if (dude.ku) {
		dude.y+=2;
	} else {
		dude.y-=1.7;
	}
	if (dude.kl) dude.x+=2;
	if (dude.kr) dude.x-=2;

	if (dude.x<0) dude.x=0;
	if (dude.x>254) dude.x=254;
	if (dude.y>95) dude.y=95;
	if (dude.y<0 && dude.bubbles<9) dude.y=0;
	dude.el.style.top=(88+dude.y)+'px';
	dude.el.style.left=(34+dude.x)+'px';
}

function crocKi() {
	for (var i=0;i<2;i++) {
		croc[i].t--;
		if (croc[i].t<=0) {
			croc[i].t=20+Math.round(Math.random()*50);

			croc[i].dx = Math.random()>0.5 ? 1 : -1;
			croc[i].dy = Math.random()>0.5 ? Math.random() : -Math.random();
		}

		croc[i].x += croc[i].dx;
		croc[i].y += croc[i].dy;

		croc[i].el.style.top=(88+croc[i].y)+'px';
		croc[i].el.style.left=(34+croc[i].x)+'px';

		if (croc[i].x<10) croc[i].dx=1;
		if (croc[i].x>240) croc[i].dx=-1;
		if (croc[i].y<10) croc[i].dy=1;
		if (croc[i].y>85) croc[i].dy=-1;

		if (croc[i].ldx != croc[i].dx) {
			croc[i].el.src = croc[ croc[i].dx>0 ? 1: 0 ].i.src;
			croc[i].ldx = croc[i].dx;
		}
	}
}

function fairBubbleX() { 	//8-288, mod 28
	var radius=100+(30*level);
	if (radius>200) radius=200;
	var x = dude.x+(Math.random()*radius)-(Math.random()*radius);
	if (x<0) x=0;
	if (x>250) x=250;
	return x;
}

function bubblesKi() {
	for (i=0;i<11;i++) {
		if (i<10) {
			bubbles[i].y-=1;
			if (bubbles[i].y<=0) {
				bubbles[i].y=105+Math.round(Math.random()*30);
			}
		} else {
			bubbles[i].y-=1.7;
			if (bubbles[i].y<=0) {
				bubbles[i].x=fairBubbleX();
				bubbles[i].y=105+Math.round(Math.random()*20*level);
			}
		}
		bubbles[i].el.style.left=(bubbles[i].x+34)+'px';
		if ((bubbles[i].y<0) || (bubbles[i].y>105)) {
			bubbles[i].el.style.top='-100px';
		} else {
			bubbles[i].el.style.top=(bubbles[i].y+88)+'px';
		}
	}
}

// dude=14x10, croc=16x14
function crocCollision() {
	for (var i=0;i<2;i++) {
		if ((croc[i].x+15>=dude.x) &&
		    (croc[i].x+2<=dude.x) &&
		    (croc[i].y+13>=dude.y) &&
		    (croc[i].y+2<=dude.y)) {
				killAnim();
		}
	}
}

// duck=16x16
function duckCollision() {
	if ((dude.y<2) &&
		(duck.x+12>=dude.x) &&
		(duck.x-2<=duck.x)) {
		killAnim();
	}
}

// dude=14x10 1=4x2 0=8x5
function bubblesCollision() {
	for (i=0;i<10;i++) {
		if ((bubbles[i].x+3>=dude.x) &&
		    (bubbles[i].x-12<=dude.x) &&
		    (bubbles[i].y+1>=dude.y) &&
		    (bubbles[i].y-8<=dude.y)) {
				bubbles[i].y=105+Math.round(Math.random()*30);
				dude.bubbles-=(dude.bubbles>0?1:0);
		    }
	}

	if ((bubbles[i].x+7>=dude.x) &&
	    (bubbles[i].x-12<=dude.x) &&
	    (bubbles[i].y+1>=dude.y) &&
	    (bubbles[i].y-10<=dude.y)) {
			dude.bubbles+=1;
			bubbles[i].y=105+Math.round(Math.random()*20*level);
			bubbles[i].x=fairBubbleX();
	}
}

function init() {
    document.onkeydown = function(e){keyp(e,1)}
    document.onkeyup = function(e){keyp(e,0)}

	//cache dom-nodes
	dude.el = $('dude');
	for (var i=0;i<2;i++) {
		croc[i].el=$('croc'+i);
		croc[i].i = new Image();
		croc[i].i.src = 'croc'+i+'.gif';
	}
	duck.el=$('duck');
	soap.el=$('soap');
	for (i=0;i<10;i++) {
		bubbles[i].el=$('bubble'+i);
	}
	bubbles[i].el=$('plus');

	level=1;
    levelInit();
}

var killCnt=0;
function killAnim() {
	if (tick) { window.clearInterval(tick); tick=null; }
	if (killCnt==40) {
		killCnt=0;
		if (dude.l==0) {
			dude.l=8;
			startScreen();
			return;
		}
		levelInit();
		return;
	}

	killCnt++;
	for (var i=0;i<7;i++) {
		$('waterbar'+(6-i)).style.backgroundColor='rgb('+Math.round(Math.random()*255)+','+Math.round(Math.random()*255)+','+Math.round(Math.random()*255)+')';
	}

	window.setTimeout('killAnim()',20);
}

function startScreen() {
	levelInit();
}

levelCnt=0;
function levelNext() {
	if (tick) { window.clearInterval(tick); tick=null; }
	if (levelCnt==40) {
		level++;
		levelInit();
	}

	//score hochzählen

	levelCnt++;
	window.setTimeout('levelNext()',20);
}

function levelInit() {
	colors();

	dude.x=112; dude.y=0; dude.bubbles=0;
	for (var i=0;i<10;i++) {
		bubbles[i].x=8+(28*i);
		bubbles[i].y=8*Math.round(Math.random()*11);
	}

	for (i=0;i<1;i++) {
		croc[i].x=40;
		croc[i].y=80+(80*i);
		croc[i].t=0;
		croc[i].d=0;
	}

	tick = window.setInterval('levelTick()',20);
}

function levelTick() {
	dudeKi();
	crocKi();
	bubblesKi();

	bubblesCollision();
	crocCollision();
	duckCollision();

	if (dude.y<-90) {
		levelNext();
	}
}


</script>
</head>
<body onload="init();">
<img src="back.gif" style="position:absolute;z-index:5;top:0;left:0" />
<?
  echo '<div id="waterbar" style="position:absolute;left:0px;top:84px;height:156px;width:336px"></div>'."\n";
  echo '<img id="waves" style="position:absolute;left:32px;top:85px;height:2px;width:272px;z-index:4" src="waveanim.gif"></div>'."\n";
  for ($i=0;$i<7;$i++) {
   echo '<div id="waterbar'.$i.'" style="position:absolute;left:32px;top:'.(85+($i*16)).'px;height:'.($i==7?22:16).'px;width:272px"></div>'."\n";
  }
  for ($i=0;$i<6;$i++) {
   echo '<div id="skybar'.$i.'" style="position:absolute;left:0;top:'.($i==0?0:29+($i*9)).'px;height:'.($i==0?39:10).'px;width:336px"></div>'."\n";
  }
?>
<div id="stoepsel"></div>
<div id="main">
<img src="duck.gif" id="duck" style="top:76px;left:34px;" />
<img src="croc0.gif" id="croc0" />
<img src="croc1.gif" id="croc1" />
<img src="dude.gif" id="dude" />
<? for($i=0;$i<10;$i++) { echo '<img src="1.gif" id="bubble'.$i.'" class="bub" />'; } ?>
<img src="0.gif" id="plus" class="bub" />
<img src="soap.gif" id="soap" style="top:-32px" />

</div>

</body>
</html>
