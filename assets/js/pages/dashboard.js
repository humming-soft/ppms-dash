'use strict';
//alert(window.orientation);
var cwidth = 1280; //content width
var swidth = window.screen.width;
var sheight = window.screen.height;

updateOrientation();

window.addEventListener('orientationchange', updateOrientation, false);

function updateOrientation() {

    var viewport = document.querySelector("meta[name=viewport]");

    switch (window.orientation) {
        case 0: case 180: //portrait
        var scale = swidth / cwidth;
        viewport.setAttribute('content', 'width=device-width, initial-scale='+ scale +', maximum-scale=1.0, user-scalable=yes;')
        break;
        case 90: case -90: //landscape
        var scale = sheight / cwidth;
        viewport.setAttribute('content', 'width=device-width, initial-scale='+ scale +', maximum-scale=1.0, user-scalable=yes;')
        break;
        default:
            var scale = swidth / cwidth;
            viewport.setAttribute('content', 'width=device-width, initial-scale='+ scale +', maximum-scale=1.0, user-scalable=yes;')
            break;
    }
    //alert(swidth + ' lead to an initial width of ' + vpwidth + ' and a rotate width of ' + vlwidth);
}
// main visibility API function
// use visibility API to check if current tab is active or not
var vis = (function(){
    var stateKey,
        eventKey,
        keys = {
            hidden: "visibilitychange",
            webkitHidden: "webkitvisibilitychange",
            mozHidden: "mozvisibilitychange",
            msHidden: "msvisibilitychange"
        };
    for (stateKey in keys) {
        if (stateKey in document) {
            eventKey = keys[stateKey];
            break;
        }
    }
    return function(c) {
        if (c) document.addEventListener(eventKey, c);
        return !document[stateKey];
    }
})();
// ##################
// check if current tab is active or not

vis(function(){

    if(vis()){
        setTimeout(function(){
            console.log("tab is visible - has focus");
            refreshme();
        },300);
    } else {
        console.log("tab is invisible - has blur");
    }
});

// check if browser window has focus
var notIE = (document.documentMode === undefined),
    isChromium = window.chrome;

if (notIE && !isChromium) {

    // checks for Firefox and other  NON IE Chrome versions
    $(window).on("focusin", function () {
        setTimeout(function(){
            console.log("focus");
        },300);

    }).on("focusout", function () {
        console.log("blur");
    });

} else {

    // checks for IE and Chromium versions
    if (window.addEventListener) {
        window.addEventListener("focus", function (event) {
            setTimeout(function(){
                console.log("focus");
            },300);

        }, false);
        window.addEventListener("blur", function (event) {
            console.log("blur");
        }, false);

    } else {
        window.attachEvent("focus", function (event) {
            setTimeout(function(){
                console.log("focus");
            },300);

        });
        window.attachEvent("blur", function (event) {
            console.log("blur");
        });
    }
}

// #####################################################

function prettyDate(d) {
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var date = (typeof d == "undefined") ? new Date() : new Date(d);
    return date.getDate() + " " + monthNames[date.getMonth()] + " " + date.getFullYear();
}

function groupAddClass(g, c) {
    $('.group-'+g).addClassSVG(c);
}

function groupRemoveClass(g, c) {
    $('.group-'+g).removeClassSVG(c);
}

function groupGoGrey(g) {
    groupRemoveClass(g, 'glow-grey glow-green glow-yellow glow-red glow-red-blinking on');
    groupAddClass(g, 'glow-grey on');
}

function groupGoGreen(g) {
    groupRemoveClass(g, 'glow-grey glow-green glow-yellow glow-red glow-red-blinking on');
    groupAddClass(g, 'glow-green on');
}

function groupGoYellow(g) {
    groupRemoveClass(g, 'glow-grey glow-green glow-yellow glow-red glow-red-blinking on');
    groupAddClass(g, 'glow-yellow on');
}

function groupGoRed(g) {
    groupRemoveClass(g, 'glow-grey glow-green glow-yellow glow-red glow-red-blinking on');
    groupAddClass(g, 'glow-red on');
}

function groupGoRedBlink(g) {
    groupRemoveClass(g, 'glow-grey glow-green glow-yellow glow-red glow-red-blinking on');
    groupAddClass(g, 'glow-red-blinking on');
    if (detectIE()) repeatBlink();
}

function repeatBlink() {
    d3.selectAll(".glow-red-blinking.on").transition().duration(1000).ease("ease-in-out").style("fill", "hsl(0, 100%, 10%)").transition().duration(1000).ease("ease-in-out").style("fill", "hsl(0, 100%, 63%)").each("end",repeatBlink);
}

function stopBlink() {
    d3.selectAll(".glow-red-blinking").transition().duration(0).style('fill', 'hsl(0, 3%, 30%)');
}

function processVariance(g, v) {
    if (v <= -8) groupGoRedBlink(g);
    else if (v < -4) groupGoRed(g);
    else if (v < 0) groupGoYellow(g);
    else if (v >= 0) groupGoGreen(g);
    else groupGoGrey(g);
}

/*
 * .addClassSVG(className)
 * Adds the specified class(es) to each of the set of matched SVG elements.
 */
$.fn.addClassSVG = function(className){
    $(this).attr('class', function(index, existingClassNames) {
        return existingClassNames + ' ' + className;
    });
    return this;
};

/*
 * .removeClassSVG(className)
 * Removes the specified class to each of the set of matched SVG elements.
 */
$.fn.removeClassSVG = function(className){
    $(this).attr('class', function(index, existingClassNames) {
        var re = new RegExp(className, 'g');
        return existingClassNames.replace(re, '');
    });
    return this;
};


$('#reset').on('click', function() {
    //$('#reset').slideToggle("up");
    $('#reset').slideUp();
    turnonall();
});

function turnoffall() {
    $('.bigcircle, .bigparking, .viaduct, .bigdepot').removeClassSVG('on');
    $('#reset').slideDown();
    if (detectIE()) stopBlink();
}

function turnonall() {
    $('.bigcircle, .bigparking, .viaduct, .bigdepot').removeClassSVG('on');
    //$('.bigcircle, .bigparking, .viaduct, .bigdepot').addClassSVG('on');
    $('.bigcircle, .bigparking, .viaduct, .bigdepot, .legend').addClassSVG('on');
    if (detectIE()) repeatBlink();
    //$('.togglebutton').addClass('on');
}

/*
 function colorit(i){
 var $p = $("#path2");
 $p.removeClassSVG("glow-green");
 $p.removeClassSVG("glow-red");
 $p.removeClassSVG("glow-yellow");
 $p.attr('style','');
 if (i == 0) $p.addClassSVG("glow-green");
 if (i == 1) $p.addClassSVG("glow-yellow");
 if (i == 2) { $p.addClassSVG("glow-red"); $p.attr('style','filter: url(#drop-shadow)'); }
 };
 */

function focus(light) {
    var glow = "";
    if (light == 0) {
        //On schedule
        glow = "glow-green";
    } else if (light == 1) {
        //Critical
        glow = "glow-yellow";
    } else if (light == 2) {
        //Delayed
        glow = "glow-red, glow-red-blinking";
    }
    turnoffall();
    if (light == 2) {
        $('.bigcircle.glow-red, .bigcircle.glow-red-blinking, .bigparking.glow-red, .bigparking.glow-red-blinking, .viaduct.glow-red, .viaduct.glow-red-blinking, .bigdepot.glow-red, .bigdepot.glow-red-blinking').addClassSVG('on');
        if (detectIE()) repeatBlink();
    }
    else $('.bigcircle.'+glow+', .bigparking.'+glow+', .viaduct.'+glow+', .bigdepot.'+glow+'').addClassSVG('on');
}

function detectIE() {
    if (typeof detectIE.isIE == "undefined") {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf('MSIE ');
        var trident = ua.indexOf('Trident/');

        if (msie > 0) {
            // IE 10 or older => return version number
            detectIE.isIE = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
            return detectIE.isIE;
        }

        if (trident > 0) {
            // IE 11 (or newer) => return version number
            var rv = ua.indexOf('rv:');
            detectIE.isIE =  parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
            return detectIE.isIE;
        }

        // other browser
        detectIE.isIE = false;
        return false;
    } else {
        return detectIE.isIE;
    }
}