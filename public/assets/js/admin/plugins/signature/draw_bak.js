var canvas;	//キャンバス
var ctx;	//キャンバスコンテキスト
var oldX = 0;
var oldY = 0;
var cW = 900;	//キャンバス横サイズ
var cH = 600;	//キャンバス縦サイズ
var mouseX = new Array();
var mouseY = new Array();
var mouseDownFlag = false;	//マウスダウンフラグ
var lineL = 5;	//線の太さ
var lineColor = "black";
var touchCount = 0;
var hasSigture = false;
var allowDraw = true;
window.onload = function () {
    init_draw();
};
function init_draw() {
    //キャンバスの初期処理
    canvas = document.getElementById('myCanvas');
    allowDraw = true;
    if (!canvas || !canvas.getContext)
        return false;
    //2Dコンテキスト
    ctx = canvas.getContext('2d');
    //マウスイベント
    canvas.addEventListener("mousedown", mouseDownListner, false);
    canvas.addEventListener("mousemove", mouseMoveListner, false);
    canvas.addEventListener("mouseup", mouseUpListner, false);
    canvas.addEventListener("mouseout", mouseOutListner, false);
    canvas.addEventListener("touchstart", mouseDownListner, false);
    canvas.addEventListener("touchmove", mouseMoveListner, false);
    canvas.addEventListener("touchend", mouseUpListner, false);
    canvas.addEventListener("touchcancel", mouseOutListner, false);
}

//マウスイベント
function mouseDownListner(e) {
    var scroll = getScrollXY();
    mouseDownFlag = true;
    oldX = e.clientX - canvas.offsetLeft;
    oldY = (e.clientY - canvas.offsetTop)+scroll[1];
}

function getScrollXY() {
  var scrOfX = 0, scrOfY = 0;
  if( typeof( window.pageYOffset ) == 'number' ) {
    //Netscape compliant
    scrOfY = window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
    //DOM compliant
    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
    //IE6 standards compliant mode
    scrOfY = document.documentElement.scrollTop;
    scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}


function mouseMoveListner(e) {
    if (mouseDownFlag && allowDraw) {
        hasSigture = true;
        //縦スクロールをしない（iPad & iPhone）
        e.preventDefault();
        //座標調整
        adjustXY(e);
        //線を描画
        ctx.strokeStyle = lineColor;
        ctx.lineWidth = lineL;
        ctx.beginPath();
        ctx.moveTo(oldX, oldY);
        ctx.lineTo(mouseX[0], mouseY[0]);
        ctx.stroke();
        ctx.closePath();
        oldX = mouseX[0];
        oldY = mouseY[0];
    }
}
function mouseUpListner(e) {
    mouseDownFlag = false;
}
function mouseOutListner(e) {
    mouseDownFlag = false;
}
//座標調整
function adjustXY(e) {
    var rect = e.target.getBoundingClientRect();
    //配列クリア
    mouseX.splice(0, mouseX.length);
    mouseY.splice(0, mouseY.length);
    //ユーザーエージェント
    var isiPad = navigator.userAgent.match(/iPad/i) != null;
    var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
    //座標取得
    if (isiPad || isiPhone) {
        //iPad & iPhone用処理
        for (i = 0; i < 5; i++) {
            if (event.touches[i]) {
                mouseX[i] = e.touches[i].pageX - rect.left;
                mouseY[i] = e.touches[i].pageY - rect.top;
            }
        }
    } else {
        //PC用処理
        mouseX[0] = e.clientX - rect.left;
        mouseY[0] = e.clientY - rect.top;
    }
}

// 保存処理
function saveData() {

    if (!hasSigture) {
        $(".error-signature").show();
        allowDraw = true;
    } else {
        $(".error-signature").hide();
        var sig = canvas.toDataURL("image/png");
        document.form.signature.value = sig;
        allowDraw = false;
    }
}
// クリア
function clearCanvas() {
    hasSigture = false;
    allowDraw = true;
    document.form.signature.value = '';
    $("#showconfirm").hide();
    ctx.clearRect(0, 0, cW, cH);
}