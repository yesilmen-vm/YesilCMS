function $PS(c) {
    if (arguments.length > 1) {
        var b = [];
        var a;
        for (var d = 0, a = arguments.length; d < a; ++d) {
            b.push($(arguments[d]))
        }
        return b
    }
    if (typeof c == "string") {
        c = ge(c)
    }
    return c
}
function $E(a) {
    if (!a) {
        if (typeof event != "undefined") {
            a = event
        } else {
            return null
        }
    }
    if (a.which) {
        a._button = a.which
    } else {
        a._button = a.button;
        if (Browser.ie) {
            if (a._button & 4) {
                a._button = 2
            } else {
                if (a._button & 2) {
                    a._button = 3
                }
            }
        } else {
            a._button = a.button + 1
        }
    }
    a._target = a.target ? a.target: a.srcElement;
    a._wheelDelta = a.wheelDelta ? a.wheelDelta: -a.detail;
    return a
}
function $A(c) {
    var e = [];
    for (var d = 0, b = c.length; d < b; ++d) {
        e.push(c[d])
    }
    return e
}
Function.prototype.bind = function () {
    var c = this,
        a = $A(arguments),
        b = a.shift();
    return function () {
        return c.apply(b, a.concat($A(arguments)))
    }
};
function strcmp(d, c) {
    if (d == c) {
        return 0
    }
    if (d == null) {
        return - 1
    }
    if (c == null) {
        return 1
    }
    return d < c ? -1 : 1
}
function trim(a) {
    return a.replace(/(^\s*|\s*$)/g, "")
}
function rtrim(c, d) {
    var b = c.length;
    while (--b > 0 && c.charAt(b) == d) {}
    c = c.substring(0, b + 1);
    if (c == d) {
        c = ""
    }
    return c
}
function sprintf(b) {
    var a;
    for (a = 1, len = arguments.length; a < len; ++a) {
        b = b.replace("$" + a, arguments[a])
    }
    return b
}
function sprintfa(b) {
    var a;
    for (a = 1, len = arguments.length; a < len; ++a) {
        b = b.replace(new RegExp("\\$" + a, "g"), arguments[a])
    }
    return b
}
function sprintfo(c) {
    if (typeof c == "object" && c.length) {
        var a = c;
        c = a[0];
        var b;
        for (b = 1; b < a.length; ++b) {
            c = c.replace("$" + b, a[b])
        }
        return c
    }
}
function str_replace(e, d, c) {
    while (e.indexOf(d) != -1) {
        e = e.replace(d, c)
    }
    return e
}
function urlencode(a) {
    a = encodeURIComponent(a);
    a = str_replace(a, "+", "%2B");
    return a
}
function urlencode2(a) {
    a = encodeURIComponent(a);
    a = str_replace(a, "%20", "+");
    return a
}
function number_format(a) {
    a = "" + parseInt(a);
    if (a.length <= 3) {
        return a
    }
    return number_format(a.substr(0, a.length - 3)) + "," + a.substr(a.length - 3)
}
function in_array(c, g, h, e) {
    if (c == null) {
        return - 1
    }
    if (h) {
        return in_arrayf(c, g, h, e)
    }
    for (var d = e || 0, b = c.length; d < b; ++d) {
        if (c[d] == g) {
            return d
        }
    }
    return - 1
}
function in_arrayf(c, g, h, e) {
    for (var d = e || 0, b = c.length; d < b; ++d) {
        if (h(c[d]) == g) {
            return d
        }
    }
    return - 1
}
function array_walk(d, h, c) {
    var g;
    for (var e = 0, b = d.length; e < b; ++e) {
        g = h(d[e], c, d, e);
        if (g != null) {
            d[e] = g
        }
    }
}
function array_apply(d, h, c) {
    var g;
    for (var e = 0, b = d.length; e < b; ++e) {
        h(d[e], c, d, e)
    }
}
function ge(a) {
    return document.getElementById(a)
}
function gE(a, b) {
    return a.getElementsByTagName(b)
}
function ce(c, b) {
    var a = document.createElement(c);
    if (b) {
        cOr(a, b)
    }
    return a
}
function de(a) {
    a.parentNode.removeChild(a)
}
function ae(a, b) {
    return a.appendChild(b)
}
function aef(a, b) {
    return a.insertBefore(b, a.firstChild)
}
function ee(a, b) {
    if (!b) {
        b = 0
    }
    while (a.childNodes[b]) {
        a.removeChild(a.childNodes[b])
    }
}
function ct(a) {
    return document.createTextNode(a)
}
function st(a, b) {
    if (a.firstChild && a.firstChild.nodeType == 3) {
        a.firstChild.nodeValue = b
    } else {
        aef(a, ct(b))
    }
}
function nw(a) {
    a.style.whiteSpace = "nowrap"
}
function rf() {
    return false
}
function rf2(a) {
    a = $E(a);
    if (a.ctrlKey || a.shiftKey || a.altKey || a.metaKey) {
        return
    }
    return false
}
function tb() {
    this.blur()
}
function ac(c, d) {
    var a = 0,
        g = 0,
        b;
    while (c) {
        a += c.offsetLeft;
        g += c.offsetTop;
        b = c.parentNode;
        while (b && b != c.offsetParent && b.offsetParent) {
            if (b.scrollLeft || b.scrollTop) {
                a -= (b.scrollLeft | 0);
                g -= (b.scrollTop | 0);
                break
            }
            b = b.parentNode
        }
        c = c.offsetParent
    }
    if (Lightbox.isVisible()) {
        d = true
    }
    if (d && !Browser.ie6) {
        var f = g_getScroll();
        a += f.x;
        g += f.y
    }
    var e = [a, g];
    e.x = a;
    e.y = g;
    return e
}
function aE(b, c, a) {
    if (Browser.ie) {
        b.attachEvent("on" + c, a)
    } else {
        b.addEventListener(c, a, false)
    }
}
function dE(b, c, a) {
    if (Browser.ie) {
        b.detachEvent("on" + c, a)
    } else {
        b.removeEventListener(c, a, false)
    }
}
function sp(a) {
    if (!a) {
        a = event
    }
    if (Browser.ie) {
        a.cancelBubble = true
    } else {
        a.stopPropagation()
    }
}
function sc(h, i, d, f, g) {
    var e = new Date();
    var c = h + "=" + escape(d) + "; ";
    e.setDate(e.getDate() + i);
    c += "expires=" + e.toUTCString() + "; ";
    if (f) {
        c += "path=" + f + "; "
    }
    if (g) {
        c += "domain=" + g + "; "
    }
    document.cookie = c;
    gc.C[h] = d
}
function dc(a) {
    sc(a, -1);
    gc.C[a] = null
}
function gc(f) {
    if (gc.I == null) {
        var e = unescape(document.cookie).split("; ");
        gc.C = {};
        for (var c = 0, a = e.length; c < a; ++c) {
            var g = e[c].indexOf("="),
                b,
                d;
            if (g != -1) {
                b = e[c].substr(0, g);
                d = e[c].substr(g + 1)
            } else {
                b = e[c];
                d = ""
            }
            gc.C[b] = d
        }
        gc.I = 1
    }
    if (!f) {
        return gc.C
    } else {
        return gc.C[f]
    }
}
function ns(a) {
    if (Browser.ie) {
        a.onfocus = tb;
        a.onmousedown = a.onselectstart = a.ondragstart = rf
    }
}
function eO(b) {
    for (var a in b) {
        delete b[a]
    }
}
function cO(f, c, b) {
    for (var e in c) {
        if (b && typeof c[e] == "object" && c[e].length) {
            f[e] = c[e].slice(0)
        } else {
            f[e] = c[e]
        }
    }
}
function cOr(f, c, b) {
    for (var e in c) {
        if (typeof c[e] == "object") {
            if (b && c[e].length) {
                f[e] = c[e].slice(0)
            } else {
                if (!f[e]) {
                    f[e] = {}
                }
                cOr(f[e], c[e], b)
            }
        } else {
            f[e] = c[e]
        }
    }
}
var Browser = {
    ie: !!(window.attachEvent && !window.opera),
    opera: !!window.opera,
    safari: navigator.userAgent.indexOf("Safari") != -1,
    gecko: navigator.userAgent.indexOf("Gecko") != -1 && navigator.userAgent.indexOf("KHTML") == -1
};
Browser.ie8 = Browser.ie && navigator.userAgent.indexOf("MSIE 8.0") != -1;
Browser.ie7 = Browser.ie && navigator.userAgent.indexOf("MSIE 7.0") != -1 && !Browser.ie8;
Browser.ie6 = Browser.ie && navigator.userAgent.indexOf("MSIE 6.0") != -1 && !Browser.ie7;
Browser.ie67 = Browser.ie6 || Browser.ie7;
navigator.userAgent.match(/Gecko\/([0-9]+)/);
Browser.geckoVersion = parseInt(RegExp.$1) | 0;
var OS = {
    windows: navigator.appVersion.indexOf("Windows") != -1,
    mac: navigator.appVersion.indexOf("Macintosh") != -1,
    linux: navigator.appVersion.indexOf("Linux") != -1
};
var DomContentLoaded = new
    function () {
        var a = [];
        this.now = function () {
            array_apply(a, function (b) {
                b()
            });
            DomContentLoaded = null
        };
        this.addEvent = function (b) {
            a.push(b)
        }
    };
function g_getWindowSize() {
    var a = 0,
        b = 0;
    if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
        a = document.documentElement.clientWidth;
        b = document.documentElement.clientHeight
    } else {
        if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
            a = document.body.clientWidth;
            b = document.body.clientHeight
        } else {
            if (typeof window.innerWidth == "number") {
                a = window.innerWidth;
                b = window.innerHeight
            }
        }
    }
    return {
        w: a,
        h: b
    }
}
function g_getScroll() {
    var a = 0,
        b = 0;
    if (typeof(window.pageYOffset) == "number") {
        a = window.pageXOffset;
        b = window.pageYOffset
    } else {
        if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
            a = document.body.scrollLeft;
            b = document.body.scrollTop
        } else {
            if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
                a = document.documentElement.scrollLeft;
                b = document.documentElement.scrollTop
            }
        }
    }
    return {
        x: a,
        y: b
    }
}
function g_getCursorPos(c) {
    var a, d;
    if (window.innerHeight) {
        a = c.pageX;
        d = c.pageY
    } else {
        var b = g_getScroll();
        a = c.clientX + b.x;
        d = c.clientY + b.y
    }
    return {
        x: a,
        y: d
    }
}
function g_scrollTo(c, b) {
    var l, k = g_getWindowSize(),
        m = g_getScroll(),
        i = k.w,
        e = k.h,
        g = m.x,
        d = m.y;
    c = $PS(c);
    if (b == null) {
        b = []
    } else {
        if (typeof b == "number") {
            b = [b]
        }
    }
    l = b.length;
    if (l == 0) {
        b[0] = b[1] = b[2] = b[3] = 0
    } else {
        if (l == 1) {
            b[1] = b[2] = b[3] = b[0]
        } else {
            if (l == 2) {
                b[2] = b[0];
                b[3] = b[1]
            } else {
                if (l == 3) {
                    b[3] = b[1]
                }
            }
        }
    }
    l = ac(c);
    var a = l[0] - b[3],
        h = l[1] - b[0],
        j = l[0] + c.offsetWidth + b[1],
        f = l[1] + c.offsetHeight + b[2];
    if (j - a > i || a < g) {
        g = a
    } else {
        if (j - i > g) {
            g = j - i
        }
    }
    if (f - h > e || h < d) {
        d = h
    } else {
        if (f - e > d) {
            d = f - e
        }
    }
    scrollTo(g, d)
}
function g_setTextNodes(c, b) {
    if (c.nodeType == 3) {
        c.nodeValue = b
    } else {
        for (var a = 0; a < c.childNodes.length; ++a) {
            g_setTextNodes(c.childNodes[a], b)
        }
    }
}
function g_getTextContent(c) {
    var a = "";
    for (var b = 0; b < c.childNodes.length; ++b) {
        if (c.childNodes[b].nodeValue) {
            a += c.childNodes[b].nodeValue
        } else {
            if (c.childNodes[b].nodeName == "BR") {
                if (Browser.ie) {
                    a += "\r"
                } else {
                    a += "\n"
                }
            }
        }
        a += g_getTextContent(c.childNodes[b])
    }
    return a
}
function g_setSelectedLink(c, b) {
    if (!g_setSelectedLink.groups) {
        g_setSelectedLink.groups = {}
    }
    var a = g_setSelectedLink.groups;
    if (a[b]) {
        a[b].className = a[b].className.replace("selected", "")
    }
    c.className += " selected";
    a[b] = c
}
function g_toggleDisplay(a) {
    if (a.style.display == "none") {
        a.style.display = "";
        return true
    } else {
        a.style.display = "none";
        return false
    }
}
function g_enableScroll(a) {
    if (!a) {
        aE(document, "mousewheel", g_enableScroll.F);
        aE(window, "DOMMouseScroll", g_enableScroll.F)
    } else {
        dE(document, "mousewheel", g_enableScroll.F);
        dE(window, "DOMMouseScroll", g_enableScroll.F)
    }
}
g_enableScroll.F = function (a) {
    if (a.stopPropagation) {
        a.stopPropagation()
    }
    if (a.preventDefault) {
        a.preventDefault()
    }
    a.returnValue = false;
    a.cancelBubble = true;
    return false
};
function g_getGets() {
    if (g_getGets.C != null) {
        return g_getGets.C
    }
    var e = {};
    if (location.search) {
        var f = decodeURIComponent(location.search.substr(1)).split("&");
        for (var c = 0, a = f.length; c < a; ++c) {
            var g = f[c].indexOf("="),
                b,
                d;
            if (g != -1) {
                b = f[c].substr(0, g);
                d = f[c].substr(g + 1)
            } else {
                b = f[c];
                d = ""
            }
            e[b] = d
        }
    }
    g_getGets.C = e;
    return e
}
function g_createRect(d, c, a, b) {
    return {
        l: d,
        t: c,
        r: d + a,
        b: c + b
    }
}
function g_intersectRect(d, c) {
    return ! (d.l >= c.r || c.l >= d.r || d.t >= c.b || c.t >= d.b)
}
function g_createRange(c, a) {
    range = {};
    for (var b = c; b <= a; ++b) {
        range[b] = b
    }
    return range
}
function g_sortIdArray(a, b, c) {
    a.sort(c ?
        function (e, d) {
            return strcmp(b[e][c], b[d][c])
        }: function (e, d) {
        return strcmp(b[e], b[d])
    })
}
function g_sortJsonArray(e, d, f, a) {
    var c = [];
    for (var b in e) {
        if (d[b] && (a == null || a(d[b]))) {
            c.push(b)
        }
    }
    if (f != null) {
        c.sort(f)
    } else {
        g_sortIdArray(c, d)
    }
    return c
}
function g_urlize(a, b) {
    a = str_replace(a, "'", "");
    a = trim(a);
    if (b) {
        a = str_replace(a, " ", "-")
    } else {
        a = a.replace(/[^a-z0-9]/i, "-")
    }
    a = str_replace(a, "--", "-");
    a = str_replace(a, "--", "-");
    a = rtrim(a, "-");
    a = a.toLowerCase();
    return a
}
function g_getLocale(a) {
    if (a && g_locale.id == 25) {
        return 0
    }
    return g_locale.id
}
function g_createReverseLookupJson(b) {
    var c = {};
    for (var a in b) {
        c[b[a]] = a
    }
    return c
}
function g_formatTimeElapsed(e) {
    function c(m, l, i) {
        if (i && LANG.timeunitsab[l] == "") {
            i = 0
        }
        if (i) {
            return m + " " + LANG.timeunitsab[l]
        } else {
            return m + " " + (m == 1 ? LANG.timeunitssg[l] : LANG.timeunitspl[l])
        }
    }
    var g = [31557600, 2629800, 604800, 86400, 3600, 60, 1];
    var a = [1, 3, 3, -1, 5, -1, -1];
    e = Math.max(e, 1);
    for (var f = 3, h = g.length; f < h; ++f) {
        if (e >= g[f]) {
            var d = f;
            var k = Math.floor(e / g[d]);
            if (a[d] != -1) {
                var b = a[d];
                e %= g[d];
                var j = Math.floor(e / g[b]);
                if (j > 0) {
                    return c(k, d, 1) + " " + c(j, b, 1)
                }
            }
            return c(k, d, 0)
        }
    }
    return "(n/a)"
}
function g_formatDateSimple(g, c) {
    function a(b) {
        return (b < 10 ? "0" + b: b)
    }
    var i = "",
        j = g.getDate(),
        f = g.getMonth() + 1,
        h = g.getFullYear();
    i += sprintf(LANG.date_simple, a(j), a(f), h);
    if (c == 1) {
        var k = g.getHours() + 1,
            e = g.getMinutes() + 1;
        i += LANG.date_at + a(k) + ":" + a(e)
    }
    return i
}
function g_cleanCharacterName(e) {
    var d = "";
    for (var c = 0, a = e.length; c < a; ++c) {
        var b = e.charAt(c).toLowerCase();
        if (b >= "a" && b <= "z") {
            d += b
        } else {
            d += e.charAt(c)
        }
    }
    return d
}
function g_createGlow(a, h) {
    var e = ce("span");
    for (var c = -1; c <= 1; ++c) {
        for (var b = -1; b <= 1; ++b) {
            var g = ce("div");
            g.style.position = "absolute";
            g.style.whiteSpace = "nowrap";
            g.style.left = c + "px";
            g.style.top = b + "px";
            if (c == 0 && b == 0) {
                g.style.zIndex = 4
            } else {
                g.style.color = "black";
                g.style.zIndex = 2
            }
            ae(g, ct(a));
            ae(e, g)
        }
    }
    e.style.position = "relative";
    e.className = "glow" + (h != null ? " " + h: "");
    var f = ce("span");
    f.style.visibility = "hidden";
    ae(f, ct(a));
    ae(e, f);
    return e
}
function g_createProgressBar(c) {
    if (c == null) {
        c = {}
    }
    if (!c.text) {
        c.text = " "
    }
    if (c.color == null) {
        c.color = "rep0"
    }
    if (c.width == null) {
        c.width = 100
    }
    var d, e;
    if (c.hoverText) {
        d = ce("a");
        d.href = "javascript:;"
    } else {
        d = ce("span")
    }
    d.className = "progressbar";
    if (c.text || c.hoverText) {
        e = ce("div");
        e.className = "progressbar-text";
        if (c.text) {
            var a = ce("del");
            ae(a, ct(c.text));
            ae(e, a)
        }
        if (c.hoverText) {
            var b = ce("ins");
            ae(b, ct(c.hoverText));
            ae(e, b)
        }
        ae(d, e)
    }
    e = ce("div");
    e.className = "progressbar-" + c.color;
    e.style.width = c.width + "%";
    ae(e, ct(String.fromCharCode(160)));
    ae(d, e);
    return d
}
function g_createReputationBar(g) {
    var f = g_createReputationBar.P;
    if (!g) {
        g = 0
    }
    g += 42000;
    if (g < 0) {
        g = 0
    } else {
        if (g > 84999) {
            g = 84999
        }
    }
    var e = g,
        h, b = 0;
    for (var d = 0, a = f.length; d < a; ++d) {
        if (f[d] > e) {
            break
        }
        if (d < a - 1) {
            e -= f[d];
            b = d + 1
        }
    }
    h = f[b];
    var c = {
        text: g_reputation_standings[b],
        hoverText: e + " / " + h,
        color: "rep" + b,
        width: parseInt(e / h * 100)
    };
    return g_createProgressBar(c)
}
g_createReputationBar.P = [36000, 3000, 3000, 3000, 6000, 12000, 21000, 999];
function g_createAchievementBar(a, c) {
    if (!a) {
        a = 0
    }
    var b = {
        text: a + (c > 0 ? " / " + c: ""),
        color: (c > 700 ? "rep7": "ach" + (c > 0 ? 0 : 1)),
        width: (c > 0 ? parseInt(a / c * 100) : 100)
    };
    return g_createProgressBar(b)
}
function g_convertRatingToPercent(g, b, f, d) {
    var e = {
        12 : 1.5,
        13 : 12,
        14 : 15,
        15 : 5,
        16 : 10,
        17 : 10,
        18 : 8,
        19 : 14,
        20 : 14,
        21 : 14,
        22 : 10,
        23 : 10,
        24 : 0,
        25 : 0,
        26 : 0,
        27 : 0,
        28 : 10,
        29 : 10,
        30 : 10,
        31 : 10,
        32 : 14,
        33 : 0,
        34 : 0,
        35 : 25,
        36 : 10,
        37 : 2.5,
        44 : 3.756097412109376
    };
    if (g < 0) {
        g = 1
    } else {
        if (g > 80) {
            g = 80
        }
    }
    if ((b == 14 || b == 12 || b == 15) && g < 34) {
        g = 34
    }
    if ((b == 28 || b == 36) && (d == 2 || d == 6 || d == 7 || d == 11)) {
        e[b] /= 1.3
    }
    if (f < 0) {
        f = 0
    }
    var a;
    if (e[b] == null) {
        a = 0
    } else {
        var c;
        if (g > 70) {
            c = (82 / 52) * Math.pow((131 / 63), ((g - 70) / 10))
        } else {
            if (g > 60) {
                c = (82 / (262 - 3 * g))
            } else {
                if (g > 10) {
                    c = ((g - 8) / 52)
                } else {
                    c = 2 / 52
                }
            }
        }
        a = f / e[b] / c
    }
    return a
}
function g_setRatingLevel(f, e, b, c) {
    var d = prompt(sprintf(LANG.prompt_ratinglevel, 1, 80), e);
    if (d != null) {
        d |= 0;
        if (d != e && d >= 1 && d <= 80) {
            e = d;
            var a = g_convertRatingToPercent(e, b, c);
            a = (Math.round(a * 100) / 100);
            if (b != 12 && b != 37) {
                a += "%"
            }
            f.innerHTML = sprintf(LANG.tooltip_combatrating, a, e);
            f.onclick = g_setRatingLevel.bind(0, f, e, b, c)
        }
    }
}
function g_getMoneyHtml(c) {
    var b = 0,
        a = "";
    if (c >= 10000) {
        b = 1;
        a += '<span class="moneygold">' + Math.floor(c / 10000) + "</span>";
        c %= 10000
    }
    if (c >= 100) {
        if (b) {
            a += " "
        } else {
            b = 1
        }
        a += '<span class="moneysilver">' + Math.floor(c / 100) + "</span>";
        c %= 100
    }
    if (c >= 1) {
        if (b) {
            a += " "
        } else {
            b = 1
        }
        a += '<span class="moneycopper">' + c + "</span>"
    }
    return a
}
function g_getLocaleFromDomain(a) {
    var c = g_getLocaleFromDomain.L;
    if (a) {
        var b = a.indexOf(".");
        if (b != -1) {
            a = a.substring(0, b)
        }
    }
    return (c[a] ? c[a] : 0)
}
g_getLocaleFromDomain.L = {
    fr: 2,
    de: 3,
    es: 6,
    ru: 7,
    ptr: 25
};
function g_getDomainFromLocale(a) {
    var b;
    if (g_getDomainFromLocale.L) {
        b = g_getDomainFromLocale.L
    } else {
        b = g_getDomainFromLocale.L = g_createReverseLookupJson(g_getLocaleFromDomain.L)
    }
    return (b[a] ? b[a] : "www")
}
function g_getIdFromTypeName(a) {
    var b = g_getIdFromTypeName.L;
    return (b[a] ? b[a] : -1)
}
g_getIdFromTypeName.L = {
    npc: 1,
    object: 2,
    item: 3,
    itemset: 4,
    quest: 5,
    spell: 6,
    zone: 7,
    faction: 8,
    pet: 9,
    achievement: 10,
    profile: 100
};
function g_onClick(c, d) {
    var b = 0;
    function a(e) {
        if (b) {
            if (b != e) {
                return
            }
        } else {
            b = e
        }
        d(true)
    }
    c.oncontextmenu = function () {
        a(1);
        return false
    };
    c.onmouseup = function (f) {
        f = $E(f);
        if (f._button == 3 || f.shiftKey || f.ctrlKey) {
            a(2)
        } else {
            if (f._button == 1) {
                d(false)
            }
        }
        return false
    }
}
function g_createOrRegex(c) {
    var e = c.split(" "),
        d = "";
    for (var b = 0, a = e.length; b < a; ++b) {
        if (b > 0) {
            d += "|"
        }
        d += e[b]
    }
    return new RegExp("(" + d + ")", "gi")
}
function Ajax(b, c) {
    if (!b) {
        return
    }
    var a;
    try {
        a = new XMLHttpRequest()
    } catch(d) {
        try {
            a = new ActiveXObject("Msxml2.XMLHTTP")
        } catch(d) {
            try {
                a = new ActiveXObject("Microsoft.XMLHTTP")
            } catch(d) {
                if (window.createRequest) {
                    a = window.createRequest()
                } else {
                    alert(LANG.message_ajaxnotsupported);
                    return
                }
            }
        }
    }
    this.request = a;
    cO(this, c);
    this.method = this.method || (this.params && "POST") || "GET";
    a.open(this.method, b, this.async == null ? true: this.async);
    a.onreadystatechange = Ajax.onReadyStateChange.bind(this);
    if (this.method.toUpperCase() == "POST") {
        a.setRequestHeader("Content-Type", (this.contentType || "application/x-www-form-urlencoded") + "; charset=" + (this.encoding || "UTF-8"))
    }
    a.send(this.params)
}
Ajax.onReadyStateChange = function () {
    if (this.request.readyState == 4) {
        if (this.request.status == 0 || (this.request.status >= 200 && this.request.status < 300)) {
            this.onSuccess != null && this.onSuccess(this.request, this)
        } else {
            this.onFailure != null && this.onFailure(this.request, this)
        }
        if (this.onComplete != null) {
            this.onComplete(this.request, this)
        }
    }
};
function g_ajaxIshRequest(b) {
    var c = document.getElementsByTagName("head")[0],
        a = g_getGets();
    if (a.refresh != null) {
        b += "&refresh"
    }
    ae(c, ce("script", {
        type: "text/javascript",
        src: b
    }))
}
var Icon = {
    sizes: ["small", "medium", "large"],
    sizes2: [18, 36, 56],
    create: function (c, k, h, b, e, j) {
        var g = ce("div"),
            d = ce("ins"),
            f = ce("del");
        if (k == null) {
            k = 1
        }
        g.className = "icon" + Icon.sizes[k];
        ae(g, d);
        ae(g, f);
        Icon.setTexture(g, k, c);
        if (b) {
            var i = ce("a");
            i.href = b;
            ae(g, i)
        } else {
            g.ondblclick = Icon.onDblClick
        }
        Icon.setNumQty(g, e, j);
        return g
    },
    setTexture: function (d, c, b) {
        if (!b) {
            return
        }
        var a = d.firstChild.style;
        if (b.indexOf("?") != -1) {
            a.backgroundImage = "url(" + b + ")"
        } else {
            a.backgroundImage = "url(https://classicdb.ch/" + Icon.sizes[c] + "/" + b.toLowerCase() + ".jpg)"
        }
        Icon.moveTexture(d, c, 0, 0)
    },
    moveTexture: function (d, c, a, e) {
        var b = d.firstChild.style;
        if (a || e) {
            b.backgroundPosition = ( - a * Icon.sizes2[c]) + "px " + ( - e * Icon.sizes2[c]) + "px"
        } else {
            if (b.backgroundPosition) {
                b.backgroundPosition = ""
            }
        }
    },
    setNumQty: function (e, c, f) {
        var b = gE(e, "span");
        for (var d = 0, a = b.length; d < a; ++d) {
            if (b[d]) {
                de(b[d])
            }
        }
        if (c != null && ((c > 1 && c < 2147483647) || c.length)) {
            b = g_createGlow(c, "q1");
            b.style.right = "0";
            b.style.bottom = "0";
            b.style.position = "absolute";
            ae(e, b)
        }
        if (f != null && f > 0) {
            b = g_createGlow("(" + f + ")", "q");
            b.style.left = "0";
            b.style.top = "0";
            b.style.position = "absolute";
            ae(e, b)
        }
    },
    getLink: function (a) {
        return gE(a, "a")[0]
    },
    onDblClick: function () {
        if (this.firstChild) {
            var b = this.firstChild.style;
            if (b.backgroundImage.length && b.backgroundImage.indexOf("url(http://static.wowhead.com") == 0) {
                var c = b.backgroundImage.lastIndexOf("/"),
                    a = b.backgroundImage.indexOf(".jpg");
                if (c != -1 && a != -1) {
                    prompt("", b.backgroundImage.substring(c + 1, a))
                }
            }
        }
    }
};
var Tooltip = {
    create: function (h) {
        var f = ce("div"),
            k = ce("table"),
            b = ce("tbody"),
            e = ce("tr"),
            c = ce("tr"),
            a = ce("td"),
            j = ce("th"),
            i = ce("th"),
            g = ce("th");
        f.className = "tooltip";
        j.style.backgroundPosition = "top right";
        i.style.backgroundPosition = "bottom left";
        g.style.backgroundPosition = "bottom right";
        if (h) {
            a.innerHTML = h
        }
        ae(e, a);
        ae(e, j);
        ae(b, e);
        ae(c, i);
        ae(c, g);
        ae(b, c);
        ae(k, b);
        Tooltip.icon = ce("p");
        Tooltip.icon.style.visibility = "hidden";
        ae(Tooltip.icon, ce("div"));
        ae(f, Tooltip.icon);
        ae(f, k);
        return f
    },
    fix: function (d, b, f) {
        var e = gE(d, "table")[0],
            h = gE(e, "td")[0],
            g = h.childNodes;
        if (g.length >= 2 && g[0].nodeName == "TABLE" && g[1].nodeName == "TABLE") {
            g[0].style.whiteSpace = "nowrap";
            var a;
            if (g[1].offsetWidth > 300) {
                a = Math.max(300, g[0].offsetWidth) + 20
            } else {
                a = Math.max(g[0].offsetWidth, g[1].offsetWidth) + 20
            }
            if (a > 20) {
                d.style.width = a + "px";
                g[0].style.width = g[1].style.width = "100%";
                if (!b && d.offsetHeight > document.body.clientHeight) {
                    e.className = "shrink"
                }
            }
        }
        if (f) {
            d.style.visibility = "visible"
        }
    },
    fixSafe: function (c, b, a) {
        if (Browser.ie) {
            setTimeout(Tooltip.fix.bind(this, c, b, a), 1)
        } else {
            Tooltip.fix(c, b, a)
        }
    },
    append: function (c, b) {
        var c = $PS(c);
        var a = Tooltip.create(b);
        ae(c, a);
        Tooltip.fixSafe(a, 1, 1)
    },
    prepare: function () {
        if (Tooltip.tooltip) {
            return
        }
        var b = Tooltip.create();
        b.style.position = "absolute";
        b.style.left = b.style.top = "-2323px";
        var a = ge("layers_0945757");
        if(a == null)
        {
            var _body = document.getElementsByTagName('body') [0];
            var _div = document.createElement('div');
            _div.id = "layers_0945757";
            _body.appendChild(_div);
            a = ge("layers_0945757");
        }
        ae(a, b);
        Tooltip.tooltip = b;
        Tooltip.tooltipTable = gE(b, "table")[0];
        Tooltip.tooltipTd = gE(b, "td")[0];
        if (Browser.ie6) {
            b = ce("iframe");
            b.src = "javascript:0;";
            b.frameBorder = 0;
            ae(a, b);
            Tooltip.iframe = b
        }
    },
    set: function (b) {
        var a = Tooltip.tooltip;
        a.style.width = "550px";
        a.style.left = "-2323px";
        a.style.top = "-2323px";
        Tooltip.tooltipTd.innerHTML = b;
        a.style.display = "";
        Tooltip.fix(a, 0, 0)
    },
    moveTests: [[null, null], [null, false], [false, null], [false, false]],
    move: function (m, l, d, n, c, a) {
        if (!Tooltip.tooltipTable) {
            return
        }
        var k = Tooltip.tooltip,
            g = Tooltip.tooltipTable.offsetWidth,
            b = Tooltip.tooltipTable.offsetHeight,
            o;
        k.style.width = g + "px";
        var j, e;
        for (var f = 0, h = Tooltip.moveTests.length; f < h; ++f) {
            o = Tooltip.moveTests[f];
            j = Tooltip.moveTest(m, l, d, n, c, a, o[0], o[1]);
            if (!Ads.intersect(j)) {
                e = true;
                break
            }
        }
        k.style.left = j.l + "px";
        k.style.top = j.t + "px";
        k.style.visibility = "visible";
        if (Browser.ie6 && Tooltip.iframe) {
            var o = Tooltip.iframe;
            o.style.left = j.l + "px";
            o.style.top = j.t + "px";
            o.style.width = g + "px";
            o.style.height = b + "px";
            o.style.display = "";
            o.style.visibility = "visible"
        }
    },
    moveTest: function (e, l, n, w, c, a, m, b) {
        var k = e,
            v = l,
            f = Tooltip.tooltip,
            i = Tooltip.tooltipTable.offsetWidth,
            p = Tooltip.tooltipTable.offsetHeight,
            g = g_getWindowSize(),
            j = g_getScroll(),
            h = g.w,
            o = g.h,
            d = j.x,
            u = j.y,
            t = d,
            s = u,
            r = d + h,
            q = u + o;
        if (m == null) {
            m = (e + n + i <= r)
        }
        if (b == null) {
            b = (l - p >= s)
        }
        if (m) {
            e += n + c
        } else {
            e = Math.max(e - i, t) - c
        }
        if (b) {
            l -= p + a
        } else {
            l += w + a
        }
        if (e < t) {
            e = t
        } else {
            if (e + i > r) {
                e = r - i
            }
        }
        if (l < s) {
            l = s
        } else {
            if (l + p > q) {
                l = Math.max(u, q - p)
            }
        }
        if (Tooltip.iconVisible) {
            if (k >= e - 48 && k <= e && v >= l - 4 && v <= l + 48) {
                l -= 48 - (v - l)
            }
        }
        return g_createRect(e, l, i, p)
    },
    show: function (f, e, d, b, c) {
        if (Tooltip.disabled) {
            return
        }
        if (!d || d < 1) {
            d = 1
        }
        if (!b || b < 1) {
            b = 1
        }
        if (c) {
            e = '<span class="' + c + '">' + e + "</span>"
        }
        var a = ac(f);
        Tooltip.prepare();
        Tooltip.set(e);
        Tooltip.move(a.x, a.y, f.offsetWidth, f.offsetHeight, d, b)
    },
    showAtCursor: function (d, f, c, a, b) {
        if (Tooltip.disabled) {
            return
        }
        if (!c || c < 10) {
            c = 10
        }
        if (!a || a < 10) {
            a = 10
        }
        if (b) {
            f = '<span class="' + b + '">' + f + "</span>"
        }
        d = $E(d);
        var g = g_getCursorPos(d);
        Tooltip.prepare();
        Tooltip.set(f);
        Tooltip.move(g.x, g.y, 0, 0, c, a)
    },
    showAtXY: function (d, a, e, c, b) {
        if (Tooltip.disabled) {
            return
        }
        Tooltip.prepare();
        Tooltip.set(d);
        Tooltip.move(a, e, 0, 0, c, b)
    },
    cursorUpdate: function (b, a, d) {
        if (Tooltip.disabled || !Tooltip.tooltip) {
            return
        }
        b = $E(b);
        if (!a || a < 10) {
            a = 10
        }
        if (!d || d < 10) {
            d = 10
        }
        var c = g_getCursorPos(b);
        Tooltip.move(c.x, c.y, 0, 0, a, d)
    },
    hide: function () {
        if (Tooltip.tooltip) {
            Tooltip.tooltip.style.display = "none";
            Tooltip.tooltip.visibility = "hidden";
            Tooltip.tooltipTable.className = "";
            if (Browser.ie6) {
                Tooltip.iframe.style.display = "none"
            }
            Tooltip.setIcon(null);
        }
    },
    setIcon: function (a) {
        Tooltip.prepare();
        if (a) {
            Tooltip.icon.style.backgroundImage = "url(https://classicdb.ch/images/icons/medium/" + a.toLowerCase() + ".jpg)";
            Tooltip.icon.style.visibility = "visible"
        } else {
            Tooltip.icon.style.backgroundImage = "none";
            Tooltip.icon.style.visibility = "hidden"
        }
        Tooltip.iconVisible = a ? 1 : 0
    }
};
var g_dev = false;
var g_locale = {
    id: 0,
    name: "enus"
};
var g_localTime = new Date();
var g_user = {
    id: 0,
    name: "",
    roles: 0
};
var g_items = {};
var g_quests = {};
var g_spells = {};
var g_achievements = {};
var g_users = {};
var g_types = {
    1 : "npc",
    2 : "object",
    3 : "item",
    4 : "itemset",
    5 : "quest",
    6 : "spell",
    7 : "zone",
    8 : "faction",
    9 : "pet",
    10 : "achievement"
};
var g_locales = {
    0 : "enus",
    2 : "frfr",
    3 : "dede",
    6 : "eses",
    8 : "ruru",
    25 : "ptr"
};
var g_file_races = {
    10 : "bloodelf",
    11 : "draenei",
    3 : "dwarf",
    7 : "gnome",
    1 : "human",
    4 : "nightelf",
    2 : "orc",
    6 : "tauren",
    8 : "troll",
    5 : "scourge"
};
var g_file_classes = {
    6 : "deathknight",
    11 : "druid",
    3 : "hunter",
    8 : "mage",
    2 : "paladin",
    5 : "priest",
    4 : "rogue",
    7 : "shaman",
    9 : "warlock",
    1 : "warrior"
};
var g_file_genders = {
    0 : "male",
    1 : "female"
};
var g_file_factions = {
    1 : "alliance",
    2 : "horde"
};
var g_file_gems = {
    1 : "meta",
    2 : "red",
    4 : "yellow",
    6 : "orange",
    8 : "blue",
    10 : "purple",
    12 : "green",
    14 : "prismatic"
};
var g_customColors = {
    Miyari: "pink"
};
g_items.add = function (b, a) {
    if (g_items[b] != null) {
        cO(g_items[b], a)
    } else {
        g_items[b] = a
    }
};
g_items.getIcon = function (a) {
    if (g_items[a] != null) {
        return g_items[a].icon
    } else {
        return "inv_misc_questionmark"
    }
};
g_items.createIcon = function (d, b, a, c) {
    return Icon.create(g_items.getIcon(d), b, null, "?item=" + d, a, c)
};
g_spells.getIcon = function (a) {
    if (g_spells[a] != null) {
        return g_spells[a].icon
    } else {
        return "inv_misc_questionmark"
    }
};
g_spells.createIcon = function (d, b, a, c) {
    return Icon.create(g_spells.getIcon(d), b, null, "?spell=" + d, a, c)
};
g_achievements.getIcon = function (a) {
    if (g_achievements[a] != null) {
        return g_achievements[a].icon
    } else {
        return "inv_misc_questionmark"
    }
};
g_achievements.createIcon = function (d, b, a, c) {
    return Icon.create(g_achievements.getIcon(d), b, null, "?achievement=" + d, a, c)
};
var $WowheadPower = new
    function () {
        var e, D, H, q, J, B, z, g = 0,
            C = {},
            f = {},
            c = {},
            G = 0,
            E = 1,
            h = 2,
            r = 3,
            F = 4,
            s = 1,
            j = 2,
            v = 3,
            y = 5,
            t = 6,
            m = 10,
            i = 100,
            o = 15,
            x = 15,
            p = {
                1 : [C, "npc"],
                2 : [f, "object"],
                3 : [g_items, "item"],
                5 : [g_quests, "quest"],
                6 : [g_spells, "spell"],
                10 : [g_achievements, "achievement"],
                100 : [c, "profile"]
            };
        function K() {
            aE(document, "mouseover", u)
        }
        function n(O) {
            var P = g_getCursorPos(O);
            B = P.x;
            z = P.y
        }
        function M(aa, W) {
            if (aa.nodeName != "A" && aa.nodeName != "AREA") {
                return - 2323
            }
            if (!aa.href.length) {
                return
            }
            if (aa.rel.indexOf("np") != -1) {
                return
            }
            var T, S, Q, P, U = {};
            q = U;
            var O = function (ab, af, ad) {
                if (af == "buff" || af == "sock") {
                    U[af] = true
                } else {
                    if (af == "rand" || af == "ench" || af == "lvl" || af == "c") {
                        U[af] = parseInt(ad)
                    } else {
                        if (af == "gems" || af == "pcs") {
                            U[af] = ad.split(":")
                        } else {
                            if (af == "who" || af == "domain") {
                                U[af] = ad
                            } else {
                                if (af == "when") {
                                    U[af] = new Date(parseInt(ad))
                                }
                            }
                        }
                    }
                }
            };
            S = 2;
            Q = 3;

            /*if (aa.href.indexOf("http://") == 0) {
             T = 1;
             P = aa.href.match(/http:\/\/(.+?)?\.?landoflegends\.de\/\?(item|quest|spell|achievement|npc|object|profile)=([^&#]+)/)*/
            //} else {
            P = aa.href.match(/()\?(item|quest|spell|achievement|npc|object|profile)=([^&#]+)/)
            //}

            // P =
            // aa.href.match(/()\?(item|quest|spell|achievement|npc|object|profile)=([^&#]+)/)
            if (P == null && aa.rel) {
                T = 0;
                S = 1;
                Q = 2;
                P = aa.rel.match(/(item|quest|spell|achievement|npc|object|profile).?([^&#]+)/)
            }
            if (aa.rel) {
                aa.rel.replace(/([a-zA-Z]+)=?([a-zA-Z0-9:-]*)/g, O);
                if (U.gems && U.gems.length > 0) {
                    var V;
                    for (V = Math.min(3, U.gems.length - 1); V >= 0; --V) {
                        if (parseInt(U.gems[V])) {
                            break
                        }
                    }++V;
                    if (V == 0) {
                        delete U.gems
                    } else {
                        if (V < U.gems.length) {
                            U.gems = U.gems.slice(0, V)
                        }
                    }
                }
            }
            if (P) {
                var Z, R = "www";
                J = aa;
                if (U.domain) {
                    R = U.domain
                } else {
                    if (T && P[T]) {
                        R = P[T]
                    }
                }
                Z = g_locale.id;// g_getLocaleFromDomain(R);
                if (aa.href.indexOf("#") != -1 && document.location.href.indexOf(P[S] + "=" + P[Q]) != -1) {
                    return
                }
                g = (aa.parentNode.className.indexOf("icon") == 0 ? 1 : 0);
                if (!aa.onmouseout) {
                    if (g == 0) {
                        aa.onmousemove = a
                    }
                    aa.onmouseout = L
                }
                n(W);
                var Y = g_getIdFromTypeName(P[S]),
                    X = P[Q];
                if (Y == i && !g_dev) {
                    Z = 0
                }
                w(Y, X, Z, U)
            }
        }
        function u(Q) {
            Q = $E(Q);
            var P = Q._target;
            var O = 0;
            while (P != null && O < 3 && M(P, Q) == -2323) {
                P = P.parentNode; ++O
            }
        }
        function a(O) {
            O = $E(O);
            n(O);
            Tooltip.move(B, z, 0, 0, o, x)
        }
        function L() {
            e = null;
            J = null;
            Tooltip.hide()
        }
        function I(O) {
            return (q.buff ? "buff_": "tooltip_") + g_locales[O]
        }
        function k(P, R, Q) {
            var O = p[P][0];
            if (O[R] == null) {
                O[R] = {}
            }
            if (O[R].status == null) {
                O[R].status = {}
            }
            if (O[R].status[Q] == null) {
                O[R].status[Q] = G
            }
        }
        function w(P, T, R, S) {
            if (!S) {
                S = {}
            }
            var Q = d(T, S);
            e = P;
            D = Q;
            H = R;
            q = S;
            k(P, Q, R);
            var O = p[P][0];
            if (O[Q].status[R] == F || O[Q].status[R] == r) {
                N(O[Q][I(R)], O[Q].icon)
            } else {
                if (O[Q].status[R] == E) {
                    N(LANG.tooltip_loading)
                } else {
                    b(P, T, R, null, S)
                }
            }
        }
        function b(W, S, X, Q, T) {
            var O = d(S, T);
            var V = p[W][0];
            if (V[O].status[X] != G && V[O].status[X] != h) {
                return
            }
            V[O].status[X] = E;
            if (!Q) {
                V[O].timer = setTimeout(function () {
                        l.apply(this, [W, O, X])
                    },
                    333)
            }
            var R = "";
            for (var U in T) {
                if (U != "rand" && U != "ench" && U != "gems" && U != "sock") {
                    continue
                }
                if (typeof T[U] == "object") {
                    R += "&" + U + "=" + T[U].join(":")
                } else {
                    if (U == "sock") {
                        R += "&sock"
                    } else {
                        R += "&" + U + "=" + T[U]
                    }
                }
            }

            var P = "https://classicdb.ch/ajax.php?" + p[W][1] + "=" + S + "&power" + R;
            
            g_ajaxIshRequest(P)
        }
        function N(R, S) {
            if (J._fixTooltip) {
                R = J._fixTooltip(R, e, D, J)
            }
            if (!R) {
                R = LANG["tooltip_" + g_types[e] + "notfound"];
                S = "inv_misc_questionmark"
            } else {
                if (q != null) {
                    if (q.pcs && q.pcs.length) {
                        var T = 0;
                        for (var Q = 0, P = q.pcs.length; Q < P; ++Q) {
                            var O;
                            if (O = R.match(new RegExp("<span><!--si([0-9]+:)*" + q.pcs[Q] + "(:[0-9]+)*-->"))) {
                                R = R.replace(O[0], '<span class="q8"><!--si' + q.pcs[Q] + "-->"); ++T
                            }
                        }
                        if (T > 0) {
                            R = R.replace("(0/", "(" + T + "/");
                            R = R.replace(new RegExp("<span>\\(([0-" + T + "])\\)", "g"), '<span class="q2">($1)')
                        }
                    }
                    if (q.c) {
                        R = R.replace(/<span class="c([0-9]+?)">(.+?)<\/span><br \/>/g, '<span class="c$1" style="display: none">$2</span>');
                        R = R.replace(new RegExp('<span class="c(' + q.c + ')" style="display: none">(.+?)</span>', "g"), '<span class="c$1">$2</span><br />')
                    }
                    if (q.lvl) {
                        R = R.replace(/\(<!--r([0-9]+):([0-9]+):([0-9]+)-->([0-9.%]+)(.+?)([0-9]+)\)/g, function (X, Z, Y, W, U, ab, V) {
                            var aa = g_convertRatingToPercent(q.lvl, Y, W);
                            aa = (Math.round(aa * 100) / 100);
                            if (Y != 12 && Y != 37) {
                                aa += "%"
                            }
                            return "(<!--r" + q.lvl + ":" + Y + ":" + W + "-->" + aa + ab + q.lvl + ")"
                        })
                    }
                    if (q.who && q.when) {
                        R = R.replace("<table><tr><td><br />", '<table><tr><td><br /><span class="q2">' + sprintf(LANG.tooltip_achievementcomplete, q.who, q.when.getMonth() + 1, q.when.getDate(), q.when.getFullYear()) + "</span><br /><br />");
                        R = R.replace(/class="q0"/g, 'class="r3"')
                    }
                }
            }
            if (g == 1) {
                Tooltip.setIcon(null);
                Tooltip.show(J, R)
            } else {
                Tooltip.setIcon(S);
                Tooltip.showAtXY(R, B, z, o, x)
            }
        }
        function l(P, R, Q) {
            if (e == P && D == R && H == Q) {
                N(LANG.tooltip_loading);
                var O = p[P][0];
                O[R].timer = setTimeout(function () {
                        A.apply(this, [P, R, Q])
                    },
                    3850)
            }
        }
        function A(P, R, Q) {
            var O = p[P][0];
            O[R].status[Q] = h;
            if (e == P && D == R && H == Q) {
                N(LANG.tooltip_noresponse)
            }
        }
        function d(P, O) {
            return P + (O.rand ? "r" + O.rand: "") + (O.ench ? "e" + O.ench: "") + (O.gems ? "g" + O.gems.join(",") : "") + (O.sock ? "s": "")
        }
        this.register = function (Q, S, R, P) {
            var O = p[Q][0];
            k(Q, S, R);
            if (O[S].timer) {
                clearTimeout(O[S].timer);
                O[S].timer = null
            }
            cO(O[S], P);
            if (O[S].status[R] == E) {
                if (O[S][I(R)]) {
                    O[S].status[R] = F
                } else {
                    O[S].status[R] = r
                }
            }
            if (e == Q && S == D && H == R) {
                N(O[S][I(R)], O[S].icon)
            }
        };
        this.registerNpc = function (Q, P, O) {
            this.register(s, Q, P, O)
        };
        this.registerObject = function (Q, P, O) {
            this.register(j, Q, P, O)
        };
        this.registerItem = function (Q, P, O) {
            this.register(v, Q, P, O)
        };
        this.registerQuest = function (Q, P, O) {
            this.register(y, Q, P, O)
        };
        this.registerSpell = function (Q, P, O) {
            this.register(t, Q, P, O)
        };
        this.registerAchievement = function (Q, P, O) {
            this.register(m, Q, P, O)
        };
        this.registerProfile = function (Q, P, O) {
            this.register(i, Q, P, O)
        };
        this.request = function (O, S, Q, R) {
            if (!R) {
                R = {}
            }
            var P = d(S, R);
            k(O, P, Q);
            b(O, S, Q, 1, R)
        };
        this.requestItem = function (P, O) {
            this.request(v, P, g_locale.id, O)
        };
        this.requestSpell = function (O) {
            this.request(t, O, g_locale.id)
        };
        this.getStatus = function (P, R, Q) {
            var O = p[P][0];
            if (O[R] != null) {
                return O[R].status[Q]
            } else {
                return G
            }
        };
        this.getItemStatus = function (P, O) {
            this.getStatus(v, P, O)
        };
        this.getSpellStatus = function (P, O) {
            this.getStatus(t, P, O)
        };
        K()

        var g_locale = { id: 0, name: 'enus' };
        var _head = document.getElementsByTagName('head') [0];
        var _link = document.createElement('link');
        var _script = document.createElement('script');
        _link.rel = "stylesheet";
        _link.setAttribute("type","text/css");
        _link.href = "https://classicdb.ch/templates/wowhead/css/power.css";
        _script.setAttribute("src","https://classicdb.ch/templates/wowhead/js/locale_enus.js");
        _script.setAttribute("type","text/javascript");

        _head.appendChild(_link);
        _head.appendChild(_script);
    };
var Ads = {
    dimensions: {
        leaderboard: [728, 90],
        skyscraper: [160, 600],
        medrect: [300, 250]
    },
    spots: {
        leaderboard: ["header-ad"],
        skyscraper: ["sidebar-ad"],
        medrect: ["infobox-ad", "blog-sidebar-medrect", "talentcalc-sidebar-ad", "pl-rightbar-ad", "contribute-ad", "profiler-inventory-medrect", "profiler-reputation-medrect", "profiler-achievements-medrect"]
    },
    hidden: [],
    hide: function (b) {
        var a = gE(b, "iframe")[0];
        if (a) {
            a.style.display = "none";
            Ads.hidden.push(b)
        }
    },
    isHidden: function (b) {
        var a = gE(b, "iframe")[0];
        if (a) {
            return a.style.display == "none"
        }
        return false
    },
    intersect: function (g, e) {
        var b;
        for (var h in Ads.dimensions) {
            var d = Ads.spots[h];
            for (var c = 0, a = d.length; c < a; ++c) {
                var f = ge(d[c]);
                if (f) {
                    if (!Ads.isHidden(f)) {
                        coords = ac(f);
                        b = g_createRect(coords.x, coords.y, f.offsetWidth, f.offsetHeight);
                        if (g_intersectRect(g, b)) {
                            if (e) {
                                Ads.hide(f)
                            }
                            return true
                        }
                    }
                }
            }
        }
        return false
    }
};