<html>
	<head>
		<title>titanic - the game</title>
		<style type="text/css">
			body {
				background-color:black;
				color:#353535;
			}
		</style>
		<script type="text/javascript">
var c; //canvas
var b = 'rgb(0,0,0)'; //black
var w = 'rgb(255,255,255)'; //white
var mode = 0; //statemachine for intro,play,gameover
var tick = 0; //to animate stuff
var shipx = 0; //...

function init() {
    var cnv = document.getElementById('c');
    c = cnv.getContext('2d');
    c.font = "30px Courier";
    draw()
}

function draw() {
    c.fillStyle = b;
    c.fillRect(0, 0, 640, 480);
    c.beginPath();
    c.strokeStyle = w;
    c.moveTo(-1, 350);
    var ampli = Math.sin(tick / 15) * 10;
    for (var i = 0; i < 640; i++) {
        y = 350 + (Math.sin((i + tick) / 15) * ampli);
        c.lineTo(i, y)
    }
    c.stroke();

    if (mode == 0) {
        c.fillStyle = w;
        c.font = "40px Courier";
        c.fillText('TITANIC - THE GAME', 110, 150);
        c.font = "20px Courier";
        if (tick % 10 < 7) {
            c.fillText('PRESS SPACE TO PLAY', 210, 250)
        }
    }

    if (mode > 0) {
        c.font = "20px Courier";
        c.fillStyle = w;
        c.fillText('Score:0', 10, 20);
        c.fillText('Lives:' + (mode == 1 ? 1 : 0), 550, 20);
        if (shipx == 0) {
            c.font = "20px Courier";
            c.fillStyle = w;
            c.fillText('PRESS SPACE TO MOVE >', 0, 450)
        }
        drawShip(shipx, 15);
        c.strokeStyle = w;
        c.beginPath();
        c.moveTo(640, 370);
        c.lineTo(620, 320);
        c.lineTo(610, 330);
        c.lineTo(550, 200);
        c.lineTo(500, 260);
        c.lineTo(490, 250);
        c.lineTo(470, 370);
        c.stroke()
    }

    if (mode == 2) {
        c.font = "40px Courier";
        c.fillStyle = w;
        c.fillText('GAME OVER', 210 + 5 * Math.random(), 150 + 5 * Math.random());
        c.font = "15px Courier";
        c.fillText("and btw... i'm leaving the company", 160, 200)
    }

    tick++;
    window.setTimeout('draw()', 50)
}

function drawShip(x, s) { //s=scale
    y = 290;
    c.strokeStyle = w;
    c.beginPath();
    c.moveTo(x + 0 * s, y + 2 * s);
    c.lineTo(x + 3 * s, y + 2 * s);
    c.lineTo(x + 3 * s, y + 0 * s);
    c.lineTo(x + 4 * s, y + 0 * s);
    c.lineTo(x + 4 * s, y + 2 * s);
    c.lineTo(x + 5 * s, y + 2 * s);
    c.lineTo(x + 5 * s, y + 0 * s);
    c.lineTo(x + 6 * s, y + 0 * s);
    c.lineTo(x + 6 * s, y + 2 * s);
    c.lineTo(x + 7 * s, y + 2 * s);
    c.lineTo(x + 7 * s, y + 0 * s);
    c.lineTo(x + 8 * s, y + 0 * s);
    c.lineTo(x + 8 * s, y + 2 * s);
    c.lineTo(x + 9 * s, y + 2 * s);
    c.lineTo(x + 9 * s, y + 0 * s);
    c.lineTo(x + 10 * s, y + 0 * s);
    c.lineTo(x + 10 * s, y + 2 * s);
    c.lineTo(x + 13 * s, y + 2 * s);
    c.lineTo(x + 12 * s, y + 5 * s);
    c.lineTo(x + 1 * s, y + 5 * s);
    c.lineTo(x + 0 * s, y + 2 * s);
    c.moveTo(x + 12 * s, y + 2 * s);
    c.lineTo(x + 11 * s, y + 5 * s);
    c.stroke()
}

function key(evt) {
    var evt = (evt) ? evt : (window.event) ? event : null;
    if (evt) {
        var k = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
        if (k == 32) {
            if (mode == 0) {
                mode = 1;
                return
            }
            if (mode == 1) {
                shipx += 5;
                if (shipx > 280) {
                    mode = 2;
                    return
                }
            }
        }
    }
}
</script>
	</head>
	<body onload="init();" onkeypress="key(event)">
		<table width="100%" height="100%">
			<tr><td align="center" valign="middle">
					<canvas id="c" width="640" height="480">your browser does not support the canvas tag</canvas>
					<br /><br />i will miss u guys :(
				</td></tr>
		</table>

	</body>
</html>
