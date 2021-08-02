

$.fn.serializeObject = function() {
    var o = {};
//    var a = this.serializeArray();
    $(this).find('input[type="hidden"], input[type="text"], input[type="password"], input[type="checkbox"]:checked, input[type="radio"]:checked, select').each(function() {
        if ($(this).attr('type') == 'hidden') { //if checkbox is checked do not take the hidden field
            var $parent = $(this).parent();
            var $chb = $parent.find('input[type="checkbox"][name="' + this.name.replace(/\[/g, '\[').replace(/\]/g, '\]') + '"]');
            if ($chb != null) {
                if ($chb.prop('checked')) return;
            }
        }
        if (this.name === null || this.name === undefined || this.name === '') return;
        var elemValue = null;
        if ($(this).is('select')) elemValue = $(this).find('option:selected').val();
        else elemValue = this.value;
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(elemValue || '');
        } else {
            o[this.name] = elemValue || '';
        }
    });
    return o;
}

String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};

String.prototype.ltrim=function(){return this.replace(/^\s+/,'');};

String.prototype.rtrim=function(){return this.replace(/\s+$/,'');};

String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');};

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

function updateScrollHash(margin)
{
    margin = -115 + (margin)*1;
    $("*[scroll-type='hash']").css({
        position:'absolute',
        visibility:'hidden',
        marginTop:margin+'px'
    })


}

function getFileIcon(file)
{

    exts = {
        'zip' : ['zip','rar'],
        'doc' : ['docx'],
        'flp' : ['flp']
    }

    var icon_types = {
        'image/*' : "img",
        'application/vnd.ms-excel' : 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'xls',
        'application/vnd.ms-powerpoint' : 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' : 'ppt',
        'application/msword' : 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' : 'doc',
        'video/*' : 'video',
        'text/*' : 'txt',
        'audio/*' : 'audio',
        'application/pdf' : 'pdf',
        'application/x-rar-compressed' : 'zip',
        'application/zip' : 'zip'
    }

    var i = 'file';

    if (file.type == "") {
        var chunks = String(file.name).split(".");
        var ext = chunks[chunks.length - 1].toLowerCase();
        _(exts).each(function(t, k) {
            if (t.indexOf(ext) !== -1) {
                i = k;
            }
        });
        return i;
    }

    _(icon_types).each(function(t, k) {
        if (file.type == k || file.type.match(k)) {
            i = t;
        }
    });
    return i;


}

function parseVideo(url) {
    // - Supported YouTube URL formats:
    //   - http://www.youtube.com/watch?v=My2FRPA3Gf8
    //   - http://youtu.be/My2FRPA3Gf8
    //   - https://youtube.googleapis.com/v/My2FRPA3Gf8
    // - Supported Vimeo URL formats:
    //   - http://vimeo.com/25451551
    //   - http://player.vimeo.com/video/25451551
    // - Also supports relative URLs:
    //   - //player.vimeo.com/video/25451551


    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return {
            "type" : "youtube",
            "id" : match[7]
        };
    }else{

        var m = url.match(/^.+vimeo.com\/(.*\/)?([^#\?]*)/);
        return m ? {
            "type" :"vimeo",
            "id" : m[2] || m[1]
        } : null;

    }

}

function multiplier(count, words)
{
    if (count % 10 == 1 && (count<10 || count>20)) return words[0];
    else if (count % 10 > 1 && count % 10 < 5 && (count<10 || count>20)) return words[1];
    else return words[2];
}
function time() {
    return Math.round((new Date()).getTime() / 1000)
}
function date(format, timestamp) {
    var that = this;
    var jsdate, f;
    // Keep this here (works, but for code commented-out below for file size reasons)
    // var tal= [];
    var txt_words = [
        'Sun', 'Mon', 'Tues', 'Wednes', 'Thurs', 'Fri', 'Satur',
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    // trailing backslash -> (dropped)
    // a backslash followed by any character (including backslash) -> the character
    // empty string -> empty string
    var formatChr = /\\?(.?)/gi;
    var formatChrCb = function(t, s) {
        return f[t] ? f[t]() : s;
    };
    var _pad = function(n, c) {
        n = String(n);
        while (n.length < c) {
            n = '0' + n;
        }
        return n;
    };
    f = {
        // Day
        d: function() { // Day of month w/leading 0; 01..31
            return _pad(f.j(), 2);
        },
        D: function() { // Shorthand day name; Mon...Sun
            return f.l()
                .slice(0, 3);
        },
        j: function() { // Day of month; 1..31
            return jsdate.getDate();
        },
        l: function() { // Full day name; Monday...Sunday
            return txt_words[f.w()] + 'day';
        },
        N: function() { // ISO-8601 day of week; 1[Mon]..7[Sun]
            return f.w() || 7;
        },
        S: function() { // Ordinal suffix for day of month; st, nd, rd, th
            var j = f.j();
            var i = j % 10;
            if (i <= 3 && parseInt((j % 100) / 10, 10) == 1) {
                i = 0;
            }
            return ['st', 'nd', 'rd'][i - 1] || 'th';
        },
        w: function() { // Day of week; 0[Sun]..6[Sat]
            return jsdate.getDay();
        },
        z: function() { // Day of year; 0..365
            var a = new Date(f.Y(), f.n() - 1, f.j());
            var b = new Date(f.Y(), 0, 1);
            return Math.round((a - b) / 864e5);
        },

        // Week
        W: function() { // ISO-8601 week number
            var a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
            var b = new Date(a.getFullYear(), 0, 4);
            return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
        },

        // Month
        F: function() { // Full month name; January...December
            return txt_words[6 + f.n()];
        },
        m: function() { // Month w/leading 0; 01...12
            return _pad(f.n(), 2);
        },
        M: function() { // Shorthand month name; Jan...Dec
            return f.F()
                .slice(0, 3);
        },
        n: function() { // Month; 1...12
            return jsdate.getMonth() + 1;
        },
        t: function() { // Days in month; 28...31
            return (new Date(f.Y(), f.n(), 0))
                .getDate();
        },

        // Year
        L: function() { // Is leap year?; 0 or 1
            var j = f.Y();
            return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
        },
        o: function() { // ISO-8601 year
            var n = f.n();
            var W = f.W();
            var Y = f.Y();
            return Y + (n === 12 && W < 9 ? 1 : n === 1 && W > 9 ? -1 : 0);
        },
        Y: function() { // Full year; e.g. 1980...2010
            return jsdate.getFullYear();
        },
        y: function() { // Last two digits of year; 00...99
            return f.Y()
                .toString()
                .slice(-2);
        },

        // Time
        a: function() { // am or pm
            return jsdate.getHours() > 11 ? 'pm' : 'am';
        },
        A: function() { // AM or PM
            return f.a()
                .toUpperCase();
        },
        B: function() { // Swatch Internet time; 000..999
            var H = jsdate.getUTCHours() * 36e2;
            // Hours
            var i = jsdate.getUTCMinutes() * 60;
            // Minutes
            var s = jsdate.getUTCSeconds(); // Seconds
            return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
        },
        g: function() { // 12-Hours; 1..12
            return f.G() % 12 || 12;
        },
        G: function() { // 24-Hours; 0..23
            return jsdate.getHours();
        },
        h: function() { // 12-Hours w/leading 0; 01..12
            return _pad(f.g(), 2);
        },
        H: function() { // 24-Hours w/leading 0; 00..23
            return _pad(f.G(), 2);
        },
        i: function() { // Minutes w/leading 0; 00..59
            return _pad(jsdate.getMinutes(), 2);
        },
        s: function() { // Seconds w/leading 0; 00..59
            return _pad(jsdate.getSeconds(), 2);
        },
        u: function() { // Microseconds; 000000-999000
            return _pad(jsdate.getMilliseconds() * 1000, 6);
        },

        // Timezone
        e: function() { // Timezone identifier; e.g. Atlantic/Azores, ...
            // The following works, but requires inclusion of the very large
            // timezone_abbreviations_list() function.
            /*              return that.date_default_timezone_get();
             */
            throw 'Not supported (see source code of date() for timezone on how to add support)';
        },
        I: function() { // DST observed?; 0 or 1
            // Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
            // If they are not equal, then DST is observed.
            var a = new Date(f.Y(), 0);
            // Jan 1
            var c = Date.UTC(f.Y(), 0);
            // Jan 1 UTC
            var b = new Date(f.Y(), 6);
            // Jul 1
            var d = Date.UTC(f.Y(), 6); // Jul 1 UTC
            return ((a - c) !== (b - d)) ? 1 : 0;
        },
        O: function() { // Difference to GMT in hour format; e.g. +0200
            var tzo = jsdate.getTimezoneOffset();
            var a = Math.abs(tzo);
            return (tzo > 0 ? '-' : '+') + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
        },
        P: function() { // Difference to GMT w/colon; e.g. +02:00
            var O = f.O();
            return (O.substr(0, 3) + ':' + O.substr(3, 2));
        },
        T: function() {
            return 'UTC';
        },
        Z: function() { // Timezone offset in seconds (-43200...50400)
            return -jsdate.getTimezoneOffset() * 60;
        },

        // Full Date/Time
        c: function() { // ISO-8601 date.
            return 'Y-m-d\\TH:i:sP'.replace(formatChr, formatChrCb);
        },
        r: function() { // RFC 2822
            return 'D, d M Y H:i:s O'.replace(formatChr, formatChrCb);
        },
        U: function() { // Seconds since UNIX epoch
            return jsdate / 1000 | 0;
        }
    };
    this.date = function(format, timestamp) {
        that = this;
        jsdate = (timestamp === undefined ? new Date() : // Not provided
            (timestamp instanceof Date) ? new Date(timestamp) : // JS Date()
                new Date(timestamp * 1000) // UNIX timestamp (auto-convert to int)
            );
        return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
}

function daysInYear(year) {
    if(year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0)) {
        // Leap year
        return 366;
    } else {
        // Not a leap year
        return 365;
    }
}

function switchSides(platforms, model, platforms_select, sides_select)
{
    var sides = new BaseCollection(platforms).findWhere({
        id : parseInt($(platforms_select).val())
    });

    if (sides) {
        sides = sides.get("sidesA");
    }

    if (sides) {
        $(sides_select).find("option").hide();
        _(sides).each(function (s) {
            $(sides_select).find("option[value='" + s + "']").show();
        });

        $(sides_select).val(model.get("side") != null ? model.get("side") : sides[0]);
    }

}

jQuery.fn.extend({
    everyTime: function(interval, label, fn, times, belay) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, times, belay);
        });
    },
    oneTime: function(interval, label, fn) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, 1);
        });
    },
    stopTime: function(label, fn) {
        return this.each(function() {
            jQuery.timer.remove(this, label, fn);
        });
    }
});

jQuery.extend({
    timer: {
        guid: 1,
        global: {},
        regex: /^([0-9]+)\s*(.*s)?$/,
        powers: {
            // Yeah this is major overkill...
            'ms': 1,
            'cs': 10,
            'ds': 100,
            's': 1000,
            'das': 10000,
            'hs': 100000,
            'ks': 1000000
        },
        timeParse: function(value) {
            if (value == undefined || value == null)
                return null;
            var result = this.regex.exec(jQuery.trim(value.toString()));
            if (result[2]) {
                var num = parseInt(result[1], 10);
                var mult = this.powers[result[2]] || 1;
                return num * mult;
            } else {
                return value;
            }
        },
        add: function(element, interval, label, fn, times, belay) {
            var counter = 0;

            if (jQuery.isFunction(label)) {
                if (!times)
                    times = fn;
                fn = label;
                label = interval;
            }

            interval = jQuery.timer.timeParse(interval);

            if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
                return;

            if (times && times.constructor != Number) {
                belay = !!times;
                times = 0;
            }

            times = times || 0;
            belay = belay || false;

            if (!element.$timers)
                element.$timers = {};

            if (!element.$timers[label])
                element.$timers[label] = {};

            fn.$timerID = fn.$timerID || this.guid++;

            var handler = function() {
                if (belay && this.inProgress)
                    return;
                this.inProgress = true;
                if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
                    jQuery.timer.remove(element, label, fn);
                this.inProgress = false;
            };

            handler.$timerID = fn.$timerID;

            if (!element.$timers[label][fn.$timerID])
                element.$timers[label][fn.$timerID] = window.setInterval(handler,interval);

            if ( !this.global[label] )
                this.global[label] = [];
            this.global[label].push( element );

        },
        remove: function(element, label, fn) {
            var timers = element.$timers, ret;

            if ( timers ) {

                if (!label) {
                    for ( label in timers )
                        this.remove(element, label, fn);
                } else if ( timers[label] ) {
                    if ( fn ) {
                        if ( fn.$timerID ) {
                            window.clearInterval(timers[label][fn.$timerID]);
                            delete timers[label][fn.$timerID];
                        }
                    } else {
                        for ( var fn in timers[label] ) {
                            window.clearInterval(timers[label][fn]);
                            delete timers[label][fn];
                        }
                    }

                    for ( ret in timers[label] ) break;
                    if ( !ret ) {
                        ret = null;
                        delete timers[label];
                    }
                }

                for ( ret in timers ) break;
                if ( !ret )
                    element.$timers = null;
            }
        }
    }
});

//if (jQuery.browser.msie)
//    jQuery(window).one("unload", function() {
//        var global = jQuery.timer.global;
//        for ( var label in global ) {
//            var els = global[label], i = els.length;
//            while ( --i )
//                jQuery.timer.remove(els[i], label);
//        }
//    });


//$(document).ready(function() {
//    if (($.browser.msie && parseInt($.browser.version) < 10)
//            || ($.browser.opera && parseInt($.browser.version) < 13)) {
//        $("body").hide();
//        window.location.href = "https://browser-update.org/update.html";
//    }
//});

$(function() {

    $(".scroll-to").on("click", function() {
        $('body, html').animate({
            'scrollTop':   $($(this).attr("scroll")).offset().top
        }, 600);
        return false;
    })

    if (typeof hashMargin == 'undefined') {
        hashMargin = 0;
    }
    updateScrollHash(hashMargin);

    $('.scroll-top').on("click",function() {
        $('html, body').animate({scrollTop: 0}, 1000);
        return false;
    });

})

var parts = window.location.search.substr(1).split("&");
var $_GET = {};
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

$(function () {



    $(".btn-group[data-toggle='buttons']:not(.group-static)").find("input[type='checkbox']").live("change",function() {
        var type = $(this).parents(".btn-group").attr("type");
        if ($(this).attr("checked")) {
            $(this).parent().removeClass("btn-"+type).addClass("btn-success");
            $(this).parent().find("input[type='text']").prop("disabled", false);
        } else {
            $(this).parent().removeClass("btn-success").addClass("btn-"+type);
            $(this).parent().find("input[type='text']").prop("disabled", true);
        }
    });

    $(".btn-group[data-toggle='buttons']:not(.group-static)").find("input[type='radio']").live("change",function() {
        var type = $(this).parents(".btn-group").attr("type");
        $(this).parents(".btn-group").find("input").parent().removeClass("btn-success").addClass("btn-"+type);
        if ($(this).attr("checked")) {
            $(this).parent().removeClass("btn-"+type).addClass("btn-success");

        }
    });

});

