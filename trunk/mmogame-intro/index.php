<html>
<head>
<title>intro</title>
<style type="Text/css">
body { background-color:black; color:grey; font-size:9px; margin:0; border:0; padding:0; }
.nodisp { display:none; }
br { clear:both; }

#logo1,#logo2 { position: absolute; top:10px; left:48px; overflow:hidden; width:224px; height:32px; background-repeat:no-repeat; }
#logo1 { background-image:url(logo1.png); }
#logo2 { z-index: 2; background-image:url(logo2.png); }
#logoblind { position:absolute; z-index:10; top:10px; left:-32px; }

.let { width:8px; height:26px; background-image:url(font1.png); display:inline-block; background-repeat:no-repeat; }

#screen { top:10px; left:10px; text-align:left; width:320px; height:240px; border:1px solid grey; background-color:black; position:absolute; overflow:hidden; }
#scrollwrap { position: absolute; top:55px; left:10px; width:300px; height:185px; overflow:hidden; }
#scroll { margin-top:185px; position:absolute; }

#bartop { z-index:10; top:55px; left:0px; position:absolute; }
#barbot { z-index:10; top:192px; left:0px; position:absolute; }

.star { top:0px; z-index:15; position:absolute; }

<?
  $font1 = ' abcdefghijklmnopqrstuvwxyzä.,?!()+=&*:1234567890';
?>

</style>
<script type="text/javascript">
    function el(name) {
      if (document.getElementById)  {
            return document.getElementById(name);
      } else if (document.all) {
            return document.all[name];
      } else if (document.layers) {
            return document.layers[name];
      }
      return false;
    }
     
    function sound(p) {
        var f;
        if (navigator.appName.indexOf("Microsoft") != -1) f=window['sound']
        else f=document['sound'];
        if (p) f.playmod()
        else f.stopmod();
    }

var mode = 0;

var scrolltop;
var scrollel;

var blend;
var blenddir;
var logo1el;
var logo2el;
var logoblendel;
var logono=0;

var star1,star2,star3;
var star1el,star2el,star3el;

function init() {
   star1el=el('star1');star2el=el('star2');star3el=el('star3');
   star1=0;star2=0;star3=0;

   mode=0;
   
   scrolltop = 0;
   scrollel = el('scroll');
   
   logo1el = el('logo1');
   logo2el = el('logo2');
   logoblendel = el('logoblind');
   blend=-31;
   logono=2;

   window.setInterval('frame();',20);
   sound(1);
}

function frame() {
   scrolltop=scrolltop-0.5;
   if (scrolltop<(-scrollel.scrollHeight-185)) {
      scrolltop = 0;
   }
   scrollel.style.top = scrolltop;

   star1=star1-3;
   if (star1<-318) { star1=star1+318; }
   star1el.style.left = star1;
   star2=star2-2;
   if (star2<-318) { star2=star2+318; }
   star2el.style.left = star2;
/*   star3=star3-1;
   if (star3<-318) { star3=star3+318; }
   star3el.style.left = star3;*/

   if (mode == 0) {
      blend=blend+2;
      
      logo1el.style.left = 48;
      logo1el.style.width = blend;
      logo1el.style.backgroundPosition = '0 0';

      logo2el.style.left = Math.max(0,48+blend);
      logo2el.style.width = Math.max(0,228-blend);
      logo2el.style.backgroundPosition = -blend+' 0';

      logoblendel.style.left = 16+blend;
      if(blend>=260){
                     blend = -32;
                     mode = 1;
                     logono++; if (logono>14) { logono=1; }
                     logo2el.style.backgroundImage = 'url(logo'+logono+'.png)';
      }
   }
   if (mode == 1) {
      blend=blend+2;
      
      logo2el.style.left = 48;
      logo2el.style.width = Math.max(0,blend-16);
      logo2el.style.backgroundPosition = '0 0';

      logo1el.style.left = Math.max(0,32+blend);
      logo1el.style.width = Math.max(0,228-blend+16);
      logo1el.style.backgroundPosition = (-blend+16)+' 0';

      logoblendel.style.left = blend;
      if(blend>=260){
                     blend = -32;
                     mode = 0;
                     logono++; if (logono>14) { logono=1; }
      logo1el.style.backgroundImage = 'url(logo'+logono+'.png)';
}
   }
}

</script>
</head>
<body onload="init();">

<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,
0,0" width="1" height="1" id="sound" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="allowFullScreen" value="false" />
<param name="movie" value="sound.swf" />
<param name="quality" value="high" />
<param name="bgcolor" value="#000000" />
<embed src="sound.swf" quality="low" bgcolor="#000000" width="1" height="1" name="sound"
align="middle" allowScriptAccess="sameDomain" allowFullScreen="false"
type="application/x-shockwave-flash"
pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>

<div id="screen">
     <img src="star1.png" class="star" id="star1">
     <img src="star2.png" class="star" id="star2">
     <!-- <img src="star3.png" class="star" id="star3"> -->
     <div id="logo1"></div>
     <div id="logo2"></div>
     <img src="bar-mid.png" id="logoblind">
     <img src="bar-top.png" id="bartop">
     <img src="bar-bot.png" id="barbot">
     <div id="scrollwrap"><div id="scroll"><?
$lines = array(
       'das freak team aus 1.24','proudly presents:','','mmogame v0.8','',
       'in','#dolby.jpg','surround','',
       'backend code:','christian & joachim','',
       'frontend code:','joachim & martin','',
       'weiterer code:','simon','',
       'gfx,html and css:','evgenij & jose','',
       
       'intro:','mnt',
);

foreach ($lines as $line) {
     if (substr($line,0,1) == '#') {
        $img=getimagesize(substr($line,1));
        echo '<div style="width:'.((300-$img[0])/2).'px; float:left; height:1px;"></div>';
        echo '<img src="'.substr($line,1).'">';

     } else {
     echo '<div style="width:'.((300-(strlen($line)*8))/2).'px; float:left; height:24px;"></div>';

     for ($i=0; $i<strlen($line); $i++) {
          $x = strpos($font1,substr($line,$i,1))*-8;
          echo '<div clasS="let" style="background-position:'.$x.'px 0;"></div>';
     }
     }
     echo '<br />';
}
?></div></div>

</div>

</body>
</html>
