/**
 * Google ad JS for HD Video Share
 *
 * This file is to append Google adsense on the player
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
var pagearray = new Array;
var timerout1;
var timerout;
var timerout2;
var timerout3;
pageno = 0;
setTimeout("onplayerloaded()", 100);
pagearray[0] = "index.php?option=com_contushdvideoshare&view=googlead";
function getFlashMovie(e) {
    var t = navigator.appName.indexOf("Microsoft") != -1;
    return t ? window[e] : document[e];
}
function googleclose() {
    if (document.all) {
        document.all.IFrameName.src = "";
    } else {
        document.getElementById("IFrameName").src = ""
    }
    document.getElementById("lightm").style.display = "none";
    clearTimeout();
}
function onplayerloaded() {
    pageno = 1;
    timerout1 = window.setTimeout("bindpage(0)", 1e3);
}
function findPosX(e) {
    var t = 0;
    if (e.offsetParent)
        while (1) {
            t += e.offsetLeft;
            if (!e.offsetParent) break;
            e = e.offsetParent;
        } else if (e.x) t += e.x;
    return t;
}
function findPosY(e) {
    var t = 0;
    if (e.offsetParent)
        while (1) {
            t += e.offsetTop;
            if (!e.offsetParent) break;
            e = e.offsetParent;
        } else if (e.y) t += e.y;
    return t;
}
function closediv() {
    document.getElementById("lightm").style.display = "none";
    clearTimeout();
    if (ropen != "") {
        setTimeout("bindpage(0)", ropen);
    }
}
function bindpage(e) {
    if (document.all) {
        document.all.IFrameName.src = pagearray[0];
    } else {
        document.getElementById("IFrameName").src = pagearray[e];
    }
    document.getElementById("closeimgm").style.display = "block";
    document.getElementById("lightm").style.display = "block";
    if (closeadd != "") setTimeout("closediv()", closeadd);
}