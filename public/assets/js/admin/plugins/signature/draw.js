var drawing = false;
var mousePos = { x:0, y:0 };
var lastPos = mousePos;
var canvas; 
var ctx;
var hasSigture = false;
// window.onload = function () {
//     init_draw();
// };

function init_draw() {
    // Set up the canvas
    canvas = document.getElementById("myCanvas");
    drawing = false;
    hasSigture = false;
    ctx = canvas.getContext("2d");
    canvas.addEventListener("mousedown", mouseDownListner, false);
    canvas.addEventListener("mousemove", mouseMoveListner, false);
    canvas.addEventListener("mouseup", mouseUpListner, false);
    canvas.addEventListener("mouseout", mouseOutListner, false);

    canvas.addEventListener("touchstart", touchStartListner, false);
    canvas.addEventListener("touchmove", touchMoveListner, false);
    canvas.addEventListener("touchend", touchEndListner, false);
    canvas.addEventListener("touchcancel", touchCancelListner, false);
    drawLoop();
}


// Get a regular interval for drawing to the screen
window.requestAnimFrame = (function (callback) {
    return window.requestAnimationFrame || 
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                window.msRequestAnimaitonFrame ||
                function (callback) {
                    window.setTimeout(callback, 1000/60);
    console.log('sdfsd');
                };
})();
// Set up mouse events for drawing
function mouseDownListner(e) {
    drawing = true;
    lastPos = getMousePos(canvas, e);
}
function mouseUpListner(e) {
    drawing = false;
}
function mouseOutListner(e) {
    drawing = false;
}
function mouseMoveListner(e) {
    hasSigture = true;
    mousePos = getMousePos(canvas, e);
}

function touchStartListner(e) {
    mousePos = getTouchPos(canvas, e);
    var touch = e.touches[0];
    var mouseEvent = new MouseEvent("mousedown", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
} 
function touchMoveListner(e) {
    hasSigture = true;
    var touch = e.touches[0];
    var mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
} 
function touchEndListner(e) {
    var mouseEvent = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(mouseEvent);
} 
function touchCancelListner(e) {
    drawing = false;
} 

// Get the position of the mouse relative to the canvas
function getMousePos(canvasDom, mouseEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
        x: mouseEvent.clientX - rect.left,
        y: mouseEvent.clientY - rect.top
    };
}

// Get the position of a touch relative to the canvas
function getTouchPos(canvasDom, touchEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
        x: touchEvent.touches[0].clientX - rect.left,
        y: touchEvent.touches[0].clientY - rect.top
    };
}

// Draw to the canvas
function renderCanvas() {
    if (drawing) {
        ctx.lineWidth = 5;
        ctx.strokeStyle = "black";
        ctx.beginPath();
        ctx.moveTo(lastPos.x, lastPos.y);
        ctx.lineTo(mousePos.x, mousePos.y);
        ctx.stroke();
        ctx.closePath();
        lastPos = mousePos;
    }
}


// Allow for animation
function drawLoop () {
    requestAnimFrame(drawLoop);
    renderCanvas();
}

// 保存処理
function saveData() {
    if (!hasSigture) {
        $(".error-signature").show();
        drawing = false;
    } else {
        $(".error-signature").hide();
        var sig = canvas.toDataURL("image/png");
        document.form.signature.value = sig;
        drawing = false;
    }
}
// クリア
function clearCanvas() {
    ctx.closePath();
    // ctx.clearRect(0, 0, 1980, 1980);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasSigture = false;
    document.form.signature.value = '';
    $("#showconfirm").hide();
    drawing = false;
    // canvas.width = canvas.width;
}


document.body.addEventListener("touchstart", function (e) {
    if (e.target == canvas) {
        e.preventDefault();
    }
}, false);
document.body.addEventListener("touchend", function (e) {
    if (e.target == canvas) {
        e.preventDefault();
    }
}, false);
document.body.addEventListener("touchmove", function (e) {
    if (e.target == canvas) {
        e.preventDefault();
    }
}, false);