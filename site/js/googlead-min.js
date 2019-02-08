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
function getFlashMovie(e){var t=-1!=navigator.appName.indexOf("Microsoft");return t?window[e]:document[e]}function googleclose(){document.all?document.all.IFrameName.src="":document.getElementById("IFrameName").src="",document.getElementById("lightm").style.display="none",clearTimeout()}function onplayerloaded(){pageno=1,timerout1=window.setTimeout("bindpage(0)",1e3)}function findPosX(e){var t=0;if(e.offsetParent)for(;;){if(t+=e.offsetLeft,!e.offsetParent)break;e=e.offsetParent}else e.x&&(t+=e.x);return t}function findPosY(e){var t=0;if(e.offsetParent)for(;;){if(t+=e.offsetTop,!e.offsetParent)break;e=e.offsetParent}else e.y&&(t+=e.y);return t}function closediv(){document.getElementById("lightm").style.display="none",clearTimeout(),""!=ropen&&setTimeout("bindpage(0)",ropen)}function bindpage(e){document.all?document.all.IFrameName.src=pagearray[0]:document.getElementById("IFrameName").src=pagearray[e],document.getElementById("closeimgm").style.display="block",document.getElementById("lightm").style.display="block",""!=closeadd&&setTimeout("closediv()",closeadd)}var pagearray=new Array,timerout1,timerout,timerout2,timerout3;pageno=0,setTimeout("onplayerloaded()",100),pagearray[0]="index.php?option=com_contushdvideoshare&view=googlead";