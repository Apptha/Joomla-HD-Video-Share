/**
 * Tool tip JS for HD Video Share
 *
 * This file is to append tool tip on video thumb images
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.8
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2015 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

function htmltooltipCallback(e, t, n) {
    var r = {
        tipclass: e,
        fadeeffect: [false, 500],
        anchors: [],
        tooltips: [],
        positiontip: function(e, i, s) {
            var o = this.anchors[i];
            var u = this.tooltips[i];
            var a;
            a = window.pageXOffset ? window.pageXOffset : this.iebody.scrollLeft;
            var f = window.pageYOffset ? window.pageYOffset : this.iebody.scrollTop;
            var l = window.innerWidth ? window.innerWidth - 15 : r.iebody.clientWidth - 15;
            var c = window.innerHeight ? window.innerHeight - 18 : r.iebody.clientHeight - 15;
            var h = o.dimensions.offsetx;
            var p = o.dimensions.offsety + o.dimensions.h;
            h = h + u.dimensions.w - a > l ? h - u.dimensions.w : h;
            if (p + u.dimensions.h - f > c) {
                p = p - u.dimensions.h - o.dimensions.h;
                e("#htmltooltipwrapper" + t + i).html(' <div class="chat-bubble-arrow-border1 "></div><div class="chat-bubble-arrow1"></div>');
            } else {
                e("#htmltooltipwrapper" + t + i).html(' <div class="chat-bubble-arrow-border "></div><div class="chat-bubble-arrow"></div>');
            }
            if (!n) {
                e(u).css({
                    left: h,
                    top: p
                });
            } else {
                e(u).css({
                    left: h,
                    top: p
                });
            }
        },
        showtip: function(e, t, n) {
            var r = this.tooltips[t];
            if (this.fadeeffect[0]) e(r).hide().fadeIn(this.fadeeffect[1]);
            else e(r).show();
        },
        hidetip: function(e, t, n) {
            var r = this.tooltips[t];
            if (this.fadeeffect[0]) e(r).fadeOut(this.fadeeffect[1]);
            else e(r).hide();
        },
        updateanchordimensions: function(e) {
            var t = e('a[rel="' + r.tipclass + '"]');
            t.each(function(t) {
                this.dimensions = {
                    w: this.offsetWidth,
                    h: this.offsetHeight,
                    offsetx: e(this).offset().left,
                    offsety: e(this).offset().top
                };
            });
        },
        render: function() {
            jQuery(document).ready(function(e) {
                r.iebody = document.compatMode && document.compatMode != "BackCompat" ? document.documentElement : document.body;
                var t = e('a[rel="' + r.tipclass + '"]');
                var n = e('div[class="' + r.tipclass + '"]');
                t.each(function(t) {
                    this.dimensions = {
                        w: this.offsetWidth,
                        h: this.offsetHeight,
                        offsetx: e(this).offset().left,
                        offsety: e(this).offset().top
                    };
                    this.tippos = t + " pos";
                    var i = n.eq(t).get(0);
                    if (i == null) return;
                    i.dimensions = {
                        w: i.offsetWidth,
                        h: i.offsetHeight
                    };
                    e(i).remove().appendTo("body");
                    r.tooltips.push(i);
                    r.anchors.push(this);
                    var s = e(this);
                    s.hover(function(t) {
                        r.positiontip(e, parseInt(this.tippos), t);
                        r.showtip(e, parseInt(this.tippos), t);
                    }, function(t) {
                        r.hidetip(e, parseInt(this.tippos), t);
                    });
                    e(window).bind("resize", function() {
                        r.updateanchordimensions(e);
                    });
                });
            });
        }
    };
    r.render();
}
jQuery.noConflict();
var tipCount;
var langFlag;
var setFlag;
