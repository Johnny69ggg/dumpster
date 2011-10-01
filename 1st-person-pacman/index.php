<?php
$level=array();
$level[0]=' ################### ';
$level[1]=' #........#........# ';
$level[2]=' #*##.###.#.###.##*# ';
$level[3]=' #.................# ';
$level[4]=' #.##.#.#####.#.##.# ';
$level[5]=' #....#...#...#....# ';
$level[6]=' ####.### # ###.#### ';
$level[7]='    #.#       #.#    ';
$level[8]='#####.# ##§## #.#####';
$level[9]='     .  #   #  .     ';
$level[10]='#####.# ##### #.#####';
$level[11]='    #.#       #.#    ';
$level[12]=' ####.# ##### #.#### ';
$level[13]=' #........#........# ';
$level[14]=' #.##.###.#.###.##.# ';
$level[15]=' #*.#...........#.*# ';
$level[16]=' ##.#.#.#####.#.#.## ';
$level[17]=' #....#...#...#....# ';
$level[18]=' #.######.#.######.# ';
$level[19]=' #.................# ';
$level[20]=' ###################1';
?>
<html>
<head>
<title>pac by mnt/codeninja.de</title>
<style>
body { margin:0; border:0; padding:0; overflow:hidden; }
.t { width:24px; height:24px; }
.g { width:24px; height:24px; position:relative; }
#topbar {
        text-align:center; font-family:courier; font-weight:bold; font-size:24px; width:504px;
        z-index:10;
}
#playfield { width:504px; height:528px; clip:rect(0,504px,528px,0);
position:absolute;
}
</style>
<script type="text/javascript">

function GetElement(name) {
  if (GetElement)  {
        return document.getElementById(name);
  } else if (document.all) {
        return document.all[name];
  } else if (document.layers) {
        return document.layers[name];
  }
  return false;
}

function SetPosition(name,x,y) {
  var e=GetElement(name);
  e.style.left = x+'px';
  e.style.top = y+'px';
}

function SetSrc(name,href) {
  var e=GetElement(name);
  e.src = href;
}

function SetHtml(name,html) {
  var e=GetElement(name);
  e.innerHTML = html;
}

var _spd=20;
var offl=0;
var offt=0;
var ghostx=new Array(5);
var ghosty=new Array(5);
var ghostd=new Array(5);
var ghosts=new Array(5);
var loopv=0;
var pacdir=-1;
var ppacdir=-1;
var pacori=0;
var lastpacori=0;
var lns=new Array(21);
var lvllns=new Array(21);
var score=0;
var level=1;
var pill=0;
var pillcnt=0;

var pfel=0;
var rot=0;
var rotnow=0;

var KEYUP=38;
var KEYDN=40;
var KEYL=37;
var KEYR=39;

///////////////////////////////////

function dirx(d) {
   switch (d) {
      case -1:return 0;break;
      case 0:return -1;break;
      case 1:return 0;break;
      case 2:return 1;break;
      case 3:return 0;break;
   }
}

function diry(d) {
   switch (d) {
      case -1:return 0;break;
      case 0:return 0;break;
      case 1:return 1;break;
      case 2:return 0;break;
      case 3:return -1;break;
   }
}

function updatescore() {
  var t = score+' ';
  while (t.length<7) { t='0'+t; }
  SetHtml('score',t);
}

function resetpos(n) {
   switch (n) {
      case 0: ghostx[n]=10; ghosty[n]=8; ghostd[n]=0; ghosts[n]=0; break;
      case 1: ghostx[n]=10; ghosty[n]=9; ghostd[n]=0; ghosts[n]=0; break;
      case 2: ghostx[n]=10; ghosty[n]=9; ghostd[n]=0; ghosts[n]=0; break;
      case 3: ghostx[n]=11; ghosty[n]=9; ghostd[n]=0; ghosts[n]=0; break;
    case 4: ghostx[n]=10; ghosty[n]=11; ghostd[n]=-1; ghosts[n]=0; break;
     // case 4: ghostx[n]=18; ghosty[n]=19; ghostd[n]=-1; ghosts[n]=0; break;
   }
}

function resetlevel(f) {
   resetpos(0);
   resetpos(1);
   resetpos(2);
   resetpos(3);
   resetpos(4);

   pacdir=-1;

   if (f) {
      for (var y=0;y<21;y++) {
         lns[y]=lvllns[y];
         for (var x=0;x<21;x++) {
            SetSrc('x'+x+'y'+y,lns[y].substr(x,1)+'.gif');
         }
      }
      pillcnt=0;
   }
   
   SetHtml('level',level);
   pill=0;
   pillstate(0);
}

function setlvlarray(x,y,c) {
   SetSrc('x'+x+'y'+y,c+'.gif');
   var t=lns[y];
   lns[y]=t.substr(0,x)+c+t.substr(x+1);
}

function pillstate(s) {
   for(var i=0;i<4;i++) {
       if (s>0) {
          if (ghosts[i]==0) {
             SetSrc('g'+i,'pillghost'+s+'.gif');
          }
       } else {
          SetSrc('g'+i,'ghost'+i+'.gif');
          if (ghosts[i]!=0) {
             resetpos(i);
          }
          ghosts[i]=0;
       }
   }
}


function funeral(w,t) {
   //pac died
   if (w==4) {
      setTimeout('resetlevel(0);loop();',5000);
      return;
   }

   SetSrc('g'+w,'pillghost0.gif');
   ghosts[w]=1;
   setTimeout('loop()',_spd);
}

function canmoveto(x,y) {
         var c=lns[y].substr(x,1);
         return (c!='l') && (c!='g');
}

function keyp(e) {
  var evt=(e)?e:window.event;
  switch (rot) {
  case 0:
   switch(evt.keyCode) {
      case KEYL: ppacdir=0; rot=90; break;     //left
      case KEYUP: ppacdir=3; rot=0; break;    //up
      case KEYR: ppacdir=2; rot=270; break;   //right
      case KEYDN: ppacdir=1; rot=180; break;   //down
   }
   break;
  case 90:
   switch(evt.keyCode) {
      case KEYUP: ppacdir=0; rot=90; break;     //left
      case KEYR: ppacdir=3; rot=0; break;    //up
      case KEYDN: ppacdir=2; rot=270; break;   //right
      case KEYL: ppacdir=1; rot=180; break;   //down
   }
   break;
  case 180:
   switch(evt.keyCode) {
      case KEYR: ppacdir=0; rot=90; break;     //left
      case KEYDN: ppacdir=3; rot=0; break;    //up
      case KEYL: ppacdir=2; rot=270; break;   //right
      case KEYUP: ppacdir=1; rot=180; break;   //down
   }
   break;
  case 270:
   switch(evt.keyCode) {
      case KEYDN: ppacdir=0; rot=90; break;     //left
      case KEYL: ppacdir=3; rot=0; break;    //up
      case KEYUP: ppacdir=2; rot=270; break;   //right
      case KEYR: ppacdir=1; rot=180; break;   //down
   }
   break;

   }

   if (window.pageYOffset) window.pageYOffset='0px';
}

////////////////////////////////

function init(){
if (document.body.scrollTop) {
 alert('does not work in IE');return;
}
<?php
echo '   ';
foreach ($level as $idx=>$l) {
   echo 'lvllns['.$idx.']="'.str_replace(array(' ','#','.','*','§','1'),array('b','l','d','p','g','m'),$l).'"; lns['.$idx.']=lvllns['.$idx.']; ';
}
?>

   pfel=GetElement('playfield');
   offl = pfel.offsetLeft;
   offt = pfel.offsetTop+24;
   document.onkeydown = function(e){keyp(e)}
  // document.onkeypress = function(e){keyp(e)}
   resetlevel(1);
   SetPosition('pac', offl + (ghostx[4]*24) + (loopv * dirx(ghostd[4])), offt + (ghosty[4]*24) + (loopv * diry(ghostd[4])) );
   loop();
}

var xc=new Array();
var yc=new Array();
var xcd=new Array();
var ycd=new Array();

/////////////////////

function movement() {
  for (var i=0;i<5;i++) {

      //general
      ghostx[i] += dirx(ghostd[i]);
      ghosty[i] += diry(ghostd[i]);
      if (ghostx[i]<0) { ghostx[i]=20; } else {
         if (ghostx[i]>=20) { ghostx[i]=0; }
      }

      //pac
      if (i==4) {
         var s=lns[ ghosty[i] ];
         var t=s.substr( ghostx[i] ,1);
         if ((t=='p') || (t=='d')) {
            pillcnt++;
            if (pillcnt<151) {
               setlvlarray(ghostx[i],ghosty[i],'b');
               score+=(t=='p'?500:50);
               updatescore();
               if (t=='p') {
                  pill=600;
                  pillstate(2);
               }
            } else {
               level++;
               score+=2500;
               updatescore();
               resetlevel(1);
            }
         }

if (canmoveto(ghostx[4]+dirx(ppacdir),ghosty[4]+diry(ppacdir))) {
   pacdir=ppacdir;
   if (rot!=rotnow) {
      rotnow=rot;
      pfel.style.MozTransform='rotate('+rotnow+'deg)';
      pfel.style.WebkitTransform='rotate('+rotnow+'deg)';
   }
}

         var s=lns[ ghosty[i] + diry(pacdir) ];
         var t=s.substr( ghostx[i] + dirx(pacdir) ,1);
         if ((t!='d') && (t!='p') && (t!='b')) {
            ghostd[i]=-1;
         } else {
            ghostd[i]=pacdir;
         }
      }//pac

      //ghosts
      if (i<4) {
         while (1) {
            if (Math.random()<0.5) {
               var d=ghostd[i]-1; if (d<0) { d=3; }
               if (lns[ ghosty[i]+diry(d) ].substr( ghostx[i]+dirx(d) ,1) != 'l') {
                  ghostd[i]=d;
                  continue;
               }
            }
         
            if (Math.random()<0.5) {
               var d=(ghostd[i]+1) %4;
               if (lns[ ghosty[i]+diry(d) ].substr( ghostx[i]+dirx(d) ,1) != 'l') {
                  ghostd[i]=d;
                  continue;
               }
            }

            if (lns[ ghosty[i]+diry(ghostd[i]) ].substr( ghostx[i]+dirx(ghostd[i]) ,1) == 'l') {
               var d=Math.round(Math.random()*3);
               ghostd[i]=d;
            } else {
               break;
            }
         }
      }

  }//for
}

function loop() {
   var pacx=(ghostx[4]*24) + (loopv * dirx(ghostd[4]));
   var pacy=(ghosty[4]*24) + (loopv * diry(ghostd[4]));

   for (var i=0;i<4;i++) {
       var gx = (ghostx[i]*24) + (loopv * dirx(ghostd[i]));
       var gy = (ghosty[i]*24) + (loopv * diry(ghostd[i]));
       SetPosition('g'+i, offl+gx, offt + gy);
       if (( Math.abs((gx+12)-(pacx+12))<13 ) && ( Math.abs((gy+12)-(pacy+12))<13 )) {
          if (pill>0) {
             if (ghosts[i]==0) {
                funeral(i);return;
             }
          } else {
             funeral(4);return;
          }
       }
   }

   switch (rotnow) {
      case 0:
          pfel.style.left=(-pacx+(10*24))+'px';
          pfel.style.top=(-pacy+(11*24))+'px';
          break;
      case 180:
          pfel.style.left=(pacx-(10*24))+'px';
          pfel.style.top=(pacy-(8*24))+'px';
          break;

      case 90:
          pfel.style.top=(-pacx+(11.5*24))+'px';
          pfel.style.left=(pacy-(9.5*24))+'px';
          break;
      case 270:
          pfel.style.top=(pacx-(8.5*24))+'px';
          pfel.style.left=(-pacy+(9.5*24))+'px';
          break;
   }

   loopv=loopv+(level<10?2:4);
   if (loopv==24) {
      loopv=0;
      movement();
   }
   
   if (pill>0) {
      pill--;
      if (pill==100) {
         pillstate(1);
      }
      if (pill==0) {
         pillstate(0);
      }
   }

   setTimeout('loop()',_spd);
}

</script>
</head>
<body text="white" bgcolor="black" onload="init();">
<div id="outer">
<div id="playfield">
<img src="ghost0.gif" id="g0" class="g"><img src="ghost1.gif" id="g1" class="g" style="margin-left:-24px"><img src="ghost2.gif" id="g2" class="g" style="margin-left:-24px"><img src="ghost3.gif" id="g3" class="g" style="margin-left:-24px"><br />
<?php
     for ($y=0; $y<21; $y++) {
         for ($x=0; $x<21; $x++) {
               echo '<img src="b.gif" id="x'.$x.'y'.$y.'" class="t">';
         }
         echo '<br />';
     }
?></div>
<div id="topbar">SCORE <span id="score">000000 </span>&nbsp; LIVES <span id="lives">&#8734;</span> &nbsp; LEVEL <span id="level">1</span></div>
</div>

<img src="pac3.gif" id="pac" class="g" style="position:absolute;">

<div style="display:none">
<img src="b.gif">
<img src="böa">
<img src="d.gif">
<img src="g.gif">
<img src="ghost0.gif">
<img src="ghost1.gif">
<img src="ghost2.gif">
<img src="ghost3.gif">
<img src="index.php">
<img src="l.gif">
<img src="mt.js">
<img src="p.gif">
<img src="pac0.gif">
<img src="pac1.gif">
<img src="pac2.gif">
<img src="pac3.gif">
<img src="pillghost0.gif">
<img src="pillghost1.gif">
<img src="pillghost2.gif">
</div>

</body>
</html>
