﻿!function (a, b) {
    "use strict";
    var e, f, h, i, j,
    c = !0, //是否采用自动获取绝对路径。!1：将采用下述变量中的配置
    d = "/lily/lib/layer/", //上述变量为!1才有效，当前layerjs所在目录(不用填写host，相对站点的根目录即可)。
    g = { host: "http://" + location.host, getPath: function () { var a = document.scripts, b = a[a.length - 1].src; return c ? b.substring(0, b.lastIndexOf("/") + 1) : this.host + d }, type: ["dialog", "page", "iframe", "loading", "tips"] }; a.layer = { v: "1.8.3", ie6: !-[1, ] && !a.XMLHttpRequest, index: 0, path: g.getPath(), use: function (a, b) { var f, g, h, d = e("head")[0]; a = a.replace(/\s/g, ""), f = /\.css$/.test(a), g = document.createElement(f ? "link" : "script"), h = a.replace(/\.|\//g, ""), f && (g.type = "text/css", g.rel = "stylesheet"), g[f ? "href" : "src"] = /^http:\/\//.test(a) ? a : layer.path + a, g.id = h, e("#" + h)[0] || d.appendChild(g), b && (document.all ? e(g).ready(b) : e(g).load(b)) }, ready: function (a) { layer.use("skin/layer.css", a) }, alert: function (a, b, c, d) { var f = "function" == typeof c, g = { dialog: { msg: a, type: b, yes: f ? c : d }, area: ["auto", "auto"] }; return f || (g.title = c), e.layer(g) }, confirm: function (a, b, c, d) { var f = "function" == typeof c, g = { dialog: { msg: a, type: 4, btns: 2, yes: b, no: f ? c : d } }; return f || (g.title = c), e.layer(g) }, msg: function (a, c, d, f) { var g = { title: !1, closeBtn: !1, time: c === b ? 2 : c, dialog: { msg: "" === a || a === b ? "&nbsp;" : a }, end: f }; return "object" == typeof d ? (g.dialog.type = d.type, g.shade = d.shade, g.success = function () { layer.shift(d.rate) }) : "function" == typeof d ? g.end = d : g.dialog.type = d, e.layer(g) }, load: function (a, b) { return "string" == typeof a ? layer.msg(a, b || 0, 16) : e.layer({ time: a, loading: { type: b }, bgcolor: b ? "#fff" : "", shade: b ? [.1, "#000"] : [0], border: 3 !== b && b ? [6, .3, "#000"] : !1, type: 3, title: ["", !1], closeBtn: [0, !1] }) }, tips: function (a, b, c, d, f, g) { var h = { type: 4, shade: !1, success: function (a) { this.closeBtn || a.find(".xubox_tips").css({ "padding-right": 10 }) }, bgcolor: "", tips: { msg: a, follow: b } }; return c = c || {}, h.time = c.time || c, h.closeBtn = c.closeBtn || !1, h.maxWidth = c.maxWidth || d, h.tips.guide = c.guide || f, h.tips.style = c.style || g, e.layer(h) } }, h = { lay: "xubox_layer", ifr: "xubox_iframe", title: ".xubox_title", text: ".xubox_text", page: ".xubox_page" }, i = function (a) { var b = this, c = b.config; layer.index++, b.index = layer.index, b.config = e.extend({}, c, a), b.config.dialog = e.extend({}, c.dialog, a.dialog), b.config.page = e.extend({}, c.page, a.page), b.config.iframe = e.extend({}, c.iframe, a.iframe), b.config.loading = e.extend({}, c.loading, a.loading), b.config.tips = e.extend({}, c.tips, a.tips), b.creat() }, i.pt = i.prototype, i.pt.config = layer.config || { type: 0, shade: [.3, "#000"], shadeClose: !1, fix: !0, move: ".xubox_title", moveOut: !1, title: ["信息", !0], offset: ["200px", "50%"], area: ["310px", "auto"], closeBtn: [0, !0], time: 0, bgcolor: "#fff", border: [6, .3, "#000"], zIndex: 19891014, maxWidth: 400, dialog: { btns: 1, btn: ["确定", "取消"], type: 8, msg: "", yes: function (a) { layer.close(a) }, no: function (a) { layer.close(a) } }, page: { dom: "#xulayer", html: "", url: "" }, iframe: { src: "http://sentsin.com", scrolling: "auto" }, loading: { type: 0 }, tips: { msg: "", follow: "", guide: 0, isGuide: !0, style: ["background-color:#FF9900; color:#fff;", "#FF9900"] }, success: function () { }, close: function (a) { layer.close(a) }, end: function () { } }, i.pt.space = function (a) { var c, d, e, f, g, i, j, k, l, m, n, o, b = this; return a = a || "", c = b.index, d = b.config, e = d.dialog, f = -1 === e.type ? "" : '<span class="xubox_msg xulayer_png32 xubox_msgico xubox_msgtype' + e.type + '"></span>', g = ['<div class="xubox_dialog">' + f + '<span class="xubox_msg xubox_text" style="' + (f ? "" : "padding-left:20px") + '">' + e.msg + "</span></div>", '<div class="xubox_page">' + a + "</div>", '<iframe scrolling="' + d.iframe.scrolling + '" allowtransparency="true" id="' + h.ifr + c + '" name="' + h.ifr + c + '" onload="this.className=\'' + h.ifr + '\'" class="' + h.ifr + '" frameborder="0" src="' + d.iframe.src + '"></iframe>', '<span class="xubox_loading xubox_loading_' + d.loading.type + '"></span>', '<div class="xubox_tips" style="' + d.tips.style[0] + '"><div class="xubox_tipsMsg">' + d.tips.msg + '</div><i class="layerTipsG"></i></div>'], i = "", j = "", k = d.zIndex + c, l = "z-index:" + k + "; background-color:" + d.shade[1] + "; opacity:" + d.shade[0] + "; filter:alpha(opacity=" + 100 * d.shade[0] + ");", d.shade[0] && (i = '<div times="' + c + '" id="xubox_shade' + c + '" class="xubox_shade" style="' + l + '"></div>'), d.zIndex = k, m = "", n = "", o = "z-index:" + (k - 1) + ";  background-color: " + d.border[2] + "; opacity:" + d.border[1] + "; filter:alpha(opacity=" + 100 * d.border[1] + "); top:-" + d.border[0] + "px; left:-" + d.border[0] + "px;", d.border[0] && (j = '<div id="xubox_border' + c + '" class="xubox_border" style="' + o + '"></div>'), !d.maxmin || 1 !== d.type && 2 !== d.type || /^\d+%$/.test(d.area[0]) && /^\d+%$/.test(d.area[1]) || (n = '<a class="xubox_min" href="javascript:;"><cite></cite></a><a class="xubox_max xulayer_png32" href="javascript:;"></a>'), d.closeBtn[1] && (n += '<a class="xubox_close xulayer_png32 xubox_close' + d.closeBtn[0] + '" href="javascript:;" style="' + (4 === d.type ? "position:absolute; right:-3px; _right:7px; top:-4px;" : "") + '"></a>'), d.title[1] && (m = '<div class="xubox_title"><em>' + d.title[0] + "</em></div>"), [i, '<div times="' + c + '" showtime="' + d.time + '" style="z-index:' + k + '" id="' + h.lay + c + '" class="' + h.lay + '">' + '<div style="background-color:' + d.bgcolor + "; z-index:" + k + '" class="xubox_main">' + g[d.type] + m + '<span class="xubox_setwin">' + n + "</span>" + '<span class="xubox_botton"></span>' + "</div>" + j + "</div>"] }, i.pt.creat = function () { var l, m, n, a = this, b = "", c = a.config, d = c.dialog, g = a.index, i = c.page, j = e("body"), k = function (c) { var c = c || ""; b = a.space(c), j.append(e(b[0])) }; switch (c.title === !1 ? c.title = [] : "string" == typeof c.title && (c.title = [c.title, !0]), c.type) { case 0: c.title[1] || (c.area = ["auto", "auto"]), e(".xubox_dialog")[0] && layer.close(e(".xubox_dialog").parents("." + h.lay).attr("times")); break; case 1: if ("" !== i.html) k('<div class="xuboxPageHtml">' + i.html + "</div>"), j.append(e(b[1])); else if ("" !== i.url) k('<div class="xuboxPageHtml" id="xuboxPageHtml' + g + '">' + i.html + "</div>"), j.append(e(b[1])), e.get(i.url, function (a) { e("#xuboxPageHtml" + g).html(a.toString()), i.ok && i.ok(a) }); else { if (0 != e(i.dom).parents(h.page).length) return; k(), e(i.dom).show().wrap(e(b[1])) } break; case 3: c.title = [], c.area = ["auto", "auto"], c.closeBtn = ["", !1], e(".xubox_loading")[0] && layer.close(e(".xubox_loading").parents("." + h.lay).attr("times")); break; case 4: c.title = [], c.area = ["auto", "auto"], c.fix = !1, c.border = [0], e(".xubox_tips")[0] && layer.close(e(".xubox_tips").parents("." + h.lay).attr("times")) } if (1 !== c.type && (k(), j.append(e(b[1]))), l = a.layerE = e("#" + h.lay + g), a.offsetTop = -1 != c.offset[0].indexOf("px") ? parseFloat(c.offset[0]) : parseFloat(c.offset[0]) / 100 * f.height(), a.offsetTop = a.offsetTop + c.border[0] + (c.fix ? 0 : f.scrollTop()), -1 != c.offset[1].indexOf("px") ? a.offsetLeft = parseFloat(c.offset[1]) + c.border[0] : (c.offset[1] = "" === c.offset[1] ? "50%" : c.offset[1], a.offsetLeft = "50%" === c.offset[1] ? c.offset[1] : parseFloat(c.offset[1]) / 100 * f.width() + c.border[0]), l.css({ left: a.offsetLeft, top: a.offsetTop, width: c.area[0], height: c.area[1] }), c.fix || l.css({ position: "absolute" }), c.title[1] && (3 !== c.type || 4 !== c.type)) switch (m = 0 === c.type ? d : c, n = l.find(".xubox_botton"), m.btn = c.btn || d.btn, m.btns) { case 0: n.html("").hide(); break; case 1: n.html('<a href="javascript:;" class="xubox_yes xubox_botton1">' + m.btn[0] + "</a>"); break; case 2: n.html('<a href="javascript:;" class="xubox_yes xubox_botton2">' + m.btn[0] + "</a>" + '<a href="javascript:;" class="xubox_no xubox_botton3">' + m.btn[1] + "</a>") } "auto" === l.css("left") ? (l.hide(), setTimeout(function () { l.show(), a.set(g) }, 500)) : a.set(g), c.time <= 0 || a.autoclose(), a.callback() }, g.fade = function (a, b, c) { a.css({ opacity: 0 }).animate({ opacity: c }, b) }, i.pt.set = function (a) { var m, n, o, p, q, r, b = this, c = b.layerE, d = c.find(h.title), i = b.config, k = (i.dialog, i.page); switch (i.loading, b.autoArea(a), i.title[1] ? 0 === i.type && layer.ie6 && d.css({ width: c.outerWidth() }) : 4 != i.type && c.find(".xubox_close").addClass("xubox_close1"), c.attr({ type: g.type[i.type] }), i.type) { case 0: c.find(".xubox_main").css({ "background-color": "#fff" }), i.title[1] ? c.find(h.text).css({ paddingTop: 18 + d.outerHeight() }) : (c.find(".xubox_msgico").css({ top: 8 }), c.find(h.text).css({ marginTop: 11 })); break; case 1: c.find(k.dom).addClass("layer_pageContent"), i.shade[0] && c.css({ zIndex: i.zIndex + 1 }), i.title[1] && c.find(h.page).css({ top: d.outerHeight() }); break; case 2: m = c.find("." + h.ifr), n = c.height(), m.addClass("xubox_load").css({ width: c.width() }), i.title[1] ? m.css({ top: d.height(), height: n - d.height() }) : m.css({ top: 0, height: n }), layer.ie6 && m.attr("src", i.iframe.src); break; case 4: o = [0, c.outerHeight()], p = e(i.tips.follow), q = { width: p.outerWidth(), height: p.outerHeight(), top: p.offset().top, left: p.offset().left }, r = c.find(".layerTipsG"), i.tips.isGuide || r.remove(), c.outerWidth() > i.maxWidth && c.width(i.maxWidth), q.tipColor = i.tips.style[1], o[0] = c.outerWidth(), q.where = [function () { q.tipLeft = q.left, q.tipTop = q.top - o[1] - 10, r.removeClass("layerTipsB").addClass("layerTipsT").css({ "border-right-color": q.tipColor }) }, function () { q.tipLeft = q.left + q.width + 10, q.tipTop = q.top, r.removeClass("layerTipsL").addClass("layerTipsR").css({ "border-bottom-color": q.tipColor }) }, function () { q.tipLeft = q.left, q.tipTop = q.top + q.height + 10, r.removeClass("layerTipsT").addClass("layerTipsB").css({ "border-right-color": q.tipColor }) }, function () { q.tipLeft = q.left - o[0] + 10, q.tipTop = q.top, r.removeClass("layerTipsR").addClass("layerTipsL").css({ "border-bottom-color": q.tipColor }) }], q.where[i.tips.guide](), 0 === i.tips.guide ? q.top - (f.scrollTop() + o[1] + 16) < 0 && q.where[2]() : 1 === i.tips.guide ? f.width() - (q.left + q.width + o[0] + 16) > 0 || q.where[3]() : 2 === i.tips.guide ? q.top - f.scrollTop() + q.height + o[1] + 16 - f.height() > 0 && q.where[0]() : 3 === i.tips.guide && o[0] + 16 - q.left > 0 && q.where[1](), c.css({ left: q.tipLeft, top: q.tipTop }) } i.fadeIn && (g.fade(c, i.fadeIn, 1), g.fade(e("#xubox_shade" + a), i.fadeIn, i.shade[0])), b.move() }, i.pt.autoArea = function (a) { var c, d, f, g, i, k, j, l, m, n, o, b = this; switch (a = a || b.index, c = b.config, d = c.page, f = e("#" + h.lay + a), g = f.find(h.title), i = f.find(".xubox_main"), j = c.title[1] ? g.innerHeight() : 0, l = 0, "auto" === c.area[0] && i.outerWidth() >= c.maxWidth && f.css({ width: c.maxWidth }), c.type) { case 0: m = f.find(".xubox_botton>a"), k = f.find(h.text).outerHeight() + 20, m.length > 0 && (l = m.outerHeight() + 20); break; case 1: n = f.find(h.page), k = e(d.dom).outerHeight(), "auto" === c.area[0] && f.css({ width: n.outerWidth() }), ("" !== d.html || "" !== d.url) && (k = n.outerHeight()); break; case 2: f.find("iframe").css({ width: f.outerWidth(), height: f.outerHeight() - (c.title[1] ? g.innerHeight() : 0) }); break; case 3: o = f.find(".xubox_loading"), k = o.outerHeight(), i.css({ width: o.width() }) } "auto" === c.area[1] && i.css({ height: j + k + l }), e("#xubox_border" + a).css({ width: f.outerWidth() + 2 * c.border[0], height: f.outerHeight() + 2 * c.border[0] }), layer.ie6 && "auto" !== c.area[0] && i.css({ width: f.outerWidth() }), "50%" !== c.offset[1] && "" != c.offset[1] || 4 === c.type ? f.css({ marginLeft: 0 }) : f.css({ marginLeft: -f.outerWidth() / 2 }) }, i.pt.move = function () { var a = this, b = a.config, c = { setY: 0, moveLayer: function () { var a; a = 0 == parseInt(c.layerE.css("margin-left")) ? parseInt(c.move.css("left")) : parseInt(c.move.css("left")) + -parseInt(c.layerE.css("margin-left")), "fixed" !== c.layerE.css("position") && (a -= c.layerE.parent().offset().left, c.setY = 0), c.layerE.css({ left: a, top: parseInt(c.move.css("top")) - c.setY }) } }; b.move && a.layerE.find(b.move).attr("move", "ok"), b.move ? a.layerE.find(b.move).css({ cursor: "move" }) : a.layerE.find(b.move).css({ cursor: "auto" }), e(b.move).on("mousedown", function (a) { if (a.preventDefault(), "ok" === e(this).attr("move")) { c.ismove = !0, c.layerE = e(this).parents("." + h.lay); var d = c.layerE.offset().left, g = c.layerE.offset().top, i = c.layerE.width() - 6, j = c.layerE.height() - 6; e("#xubox_moves")[0] || e("body").append('<div id="xubox_moves" class="xubox_moves" style="left:' + d + "px; top:" + g + "px; width:" + i + "px; height:" + j + 'px; z-index:2147483584"></div>'), c.move = e("#xubox_moves"), b.moveType && c.move.css({ opacity: 0 }), c.moveX = a.pageX - c.move.position().left, c.moveY = a.pageY - c.move.position().top, "fixed" !== c.layerE.css("position") || (c.setY = f.scrollTop()) } }), e(document).mousemove(function (a) { var d, e, g, h; c.ismove && (d = a.pageX - c.moveX, e = a.pageY - c.moveY, a.preventDefault(), b.moveOut || (c.setY = f.scrollTop(), g = f.width() - c.move.outerWidth() - b.border[0], h = b.border[0] + c.setY, d < b.border[0] && (d = b.border[0]), d > g && (d = g), h > e && (e = h), e > f.height() - c.move.outerHeight() - b.border[0] + c.setY && (e = f.height() - c.move.outerHeight() - b.border[0] + c.setY)), c.move.css({ left: d, top: e }), b.moveType && c.moveLayer(), d = null, e = null, g = null, h = null) }).mouseup(function () { try { c.ismove && (c.moveLayer(), c.move.remove()), c.ismove = !1 } catch (a) { c.ismove = !1 } b.moveEnd && b.moveEnd() }) }, i.pt.autoclose = function () { var a = this, b = a.config.time, c = function () { b--, 0 === b && (layer.close(a.index), clearInterval(a.autotime)) }; a.autotime = setInterval(c, 1e3) }, g.config = { end: {} }, i.pt.callback = function () { var a = this, b = a.layerE, c = a.config, d = c.dialog; a.openLayer(), a.config.success(b), layer.ie6 && a.IE6(b), b.find(".xubox_close").on("click", function () { c.close(a.index), layer.close(a.index) }), b.find(".xubox_yes").on("click", function () { c.yes ? c.yes(a.index) : d.yes(a.index) }), b.find(".xubox_no").on("click", function () { c.no ? c.no(a.index) : d.no(a.index), layer.close(a.index) }), a.config.shadeClose && e("#xubox_shade" + a.index).on("click", function () { layer.close(a.index) }), b.find(".xubox_min").on("click", function () { layer.min(a.index, c), c.min && c.min(b) }), b.find(".xubox_max").on("click", function () { e(this).hasClass("xubox_maxmin") ? (layer.restore(a.index), c.restore && c.restore(b)) : (layer.full(a.index, c), c.full && c.full(b)) }), g.config.end[a.index] = c.end }, g.reselect = function () { e.each(e("select"), function () { var c = e(this); c.parents("." + h.lay)[0] || 1 == c.attr("layer") && e("." + h.lay).length < 1 && c.removeAttr("layer").show(), c = null }) }, i.pt.IE6 = function (a) { var d, b = this, c = a.offset().top; d = b.config.fix ? function () { a.css({ top: f.scrollTop() + c }) } : function () { a.css({ top: c }) }, d(), f.scroll(d), e.each(e("select"), function () { var c = e(this); c.parents("." + h.lay)[0] || "none" == c.css("display") || c.attr({ layer: "1" }).hide(), c = null }) }, i.pt.openLayer = function () { var a = this; layer.autoArea = function (b) { return a.autoArea(b) }, layer.shift = function (b, c, d) { var e, g, h, i, j, k; if (!layer.ie6) switch (e = a.config, g = a.layerE, h = 0, i = f.width(), j = f.height() + (e.fix ? 0 : f.scrollTop()), h = "50%" == e.offset[1] || "" == e.offset[1] ? g.outerWidth() / 2 : g.outerWidth(), k = { t: { top: a.offsetTop }, b: { top: j - g.outerHeight() - e.border[0] }, cl: h + e.border[0], ct: -g.outerHeight(), cr: i - h - e.border[0] }, b) { case "left-top": g.css({ left: k.cl, top: k.ct }).animate(k.t, c); break; case "top": g.css({ top: k.ct }).animate(k.t, c); break; case "right-top": g.css({ left: k.cr, top: k.ct }).animate(k.t, c); break; case "right-bottom": g.css({ left: k.cr, top: j }).animate(d ? k.t : k.b, c); break; case "bottom": g.css({ top: j }).animate(d ? k.t : k.b, c); break; case "left-bottom": g.css({ left: k.cl, top: j }).animate(d ? k.t : k.b, c); break; case "left": g.css({ left: -g.outerWidth() }).animate({ left: a.offsetLeft }, c) } }, layer.setMove = function () { return a.move() }, layer.zIndex = a.config.zIndex, layer.setTop = function (a) { var b = function () { layer.zIndex++, a.css("z-index", layer.zIndex + 1) }; return layer.zIndex = parseInt(a[0].style.zIndex), a.on("mousedown", b), layer.zIndex } }, g.isauto = function (a, b, c) { "auto" === b.area[0] && (b.area[0] = a.outerWidth()), "auto" === b.area[1] && (b.area[1] = a.outerHeight()), a.attr({ area: b.area + "," + c }), a.find(".xubox_max").addClass("xubox_maxmin") }, g.rescollbar = function (a) { h.html.attr("layer-full") == a && (h.html[0].style.removeProperty ? h.html[0].style.removeProperty("overflow") : h.html[0].style.removeAttribute("overflow"), h.html.removeAttr("layer-full")) }, layer.getIndex = function (a) { return e(a).parents("." + h.lay).attr("times") }, layer.getChildFrame = function (a, b) { return b = b || e("." + h.ifr).parents("." + h.lay).attr("times"), e("#" + h.lay + b).find("." + h.ifr).contents().find(a) }, layer.getFrameIndex = function (a) { return e(a ? "#" + a : "." + h.ifr).parents("." + h.lay).attr("times") }, layer.iframeAuto = function (a) { var b, c, d, f, g; a = a || e("." + h.ifr).parents("." + h.lay).attr("times"), b = layer.getChildFrame("body", a).outerHeight(), c = e("#" + h.lay + a), d = c.find(h.title), f = 0, d && (f = d.height()), c.css({ height: b + f }), g = -parseInt(e("#xubox_border" + a).css("top")), e("#xubox_border" + a).css({ height: b + 2 * g + f }), e("#" + h.ifr + a).css({ height: b }) }, layer.iframeSrc = function (a, b) { e("#" + h.lay + a).find("iframe").attr("src", b) }, layer.area = function (a, b) { var j, c = [e("#" + h.lay + a), e("#xubox_border" + a)], d = c[0].attr("type"), f = c[0].find(".xubox_main"), i = c[0].find(h.title); (d === g.type[1] || d === g.type[2]) && (c[0].css(b), f.css({ width: b.width, height: b.height }), d === g.type[2] && (j = c[0].find("iframe"), j.css({ width: b.width, height: i ? b.height - i.innerHeight() : b.height })), "0px" !== c[0].css("margin-left") && (b.hasOwnProperty("top") && c[0].css({ top: b.top - (c[1][0] ? parseFloat(c[1].css("top")) : 0) }), b.hasOwnProperty("left") && c[0].css({ left: b.left + c[0].outerWidth() / 2 - (c[1][0] ? parseFloat(c[1].css("left")) : 0) }), c[0].css({ marginLeft: -c[0].outerWidth() / 2 })), c[1][0] && c[1].css({ width: parseFloat(b.width) - 2 * parseFloat(c[1].css("left")), height: parseFloat(b.height) - 2 * parseFloat(c[1].css("top")) })) }, layer.min = function (a, b) { var c = e("#" + h.lay + a), d = [c.position().top, c.position().left + parseFloat(c.css("margin-left"))]; g.isauto(c, b, d), layer.area(a, { width: 180, height: 35 }), c.find(".xubox_min").hide(), "page" === c.attr("type") && c.find(h.page).hide(), g.rescollbar(a) }, layer.restore = function (a) { var b = e("#" + h.lay + a), c = b.attr("area").split(","); b.attr("type"), layer.area(a, { width: parseFloat(c[0]), height: parseFloat(c[1]), top: parseFloat(c[2]), left: parseFloat(c[3]) }), b.find(".xubox_max").removeClass("xubox_maxmin"), b.find(".xubox_min").show(), "page" === b.attr("type") && b.find(h.page).show(), g.rescollbar(a) }, layer.full = function (a, b) { var i, c = e("#" + h.lay + a), d = 2 * b.border[0], j = [c.position().top, c.position().left + parseFloat(c.css("margin-left"))]; g.isauto(c, b, j), h.html.attr("layer-full") || h.html.css("overflow", "hidden").attr("layer-full", a), clearTimeout(i), i = setTimeout(function () { layer.area(a, { top: "fixed" === c.css("position") ? 0 : f.scrollTop(), left: "fixed" === c.css("position") ? 0 : f.scrollLeft(), width: f.width() - d, height: f.height() - d }) }, 100) }, layer.close = function (a) { var f, b = e("#" + h.lay + a), c = b.attr("type"), d = e("#xubox_moves, #xubox_shade" + a); if (b[0]) { if (c == g.type[1]) if (b.find(".xuboxPageHtml")[0]) b[0].innerHTML = "", b.remove(); else for (b.find(".xubox_setwin,.xubox_close,.xubox_botton,.xubox_title,.xubox_border").remove(), f = 0; 3 > f; f++) b.find(".layer_pageContent").unwrap().hide(); else b[0].innerHTML = "", b.remove(); d.remove(), layer.ie6 && g.reselect(), g.rescollbar(a), "function" == typeof g.config.end[a] && g.config.end[a](), delete g.config.end[a] } }, layer.closeLoad = function () { layer.close(e(".xubox_loading").parents("." + h.lay).attr("times")) }, layer.closeTips = function () { layer.close(e(".xubox_tips").parents("." + h.lay).attr("times")) }, layer.closeAll = function () { e.each(e("." + h.lay), function () { layer.close(e(this).attr("times")) }) }, g.run = function () { e = jQuery, f = e(a), h.html = e("html"), layer.use("skin/layer.css"), e.layer = function (a) { var b = new i(a); return b.index }, (new Image).src = layer.path + "skin/default/xubox_ico0.png" }, j = "../../init/jquery", a.seajs ? define([j], function (b, c) { g.run(), c.layer = [a.layer, a.$.layer] }) : g.run()
}(window);

//兼容ie6的fixed代码 
//jQuery(function($j){
//	$j('#pop').positionFixed()
//})
(function ($j) {
    $j.positionFixed = function (el) {
        $j(el).each(function () {
            new fixed(this)
        })
        return el;
    }
    $j.fn.positionFixed = function () {
        return $j.positionFixed(this)
    }
    var fixed = $j.positionFixed.impl = function (el) {
        var o = this;
        o.sts = {
            target: $j(el).css('position', 'fixed'),
            container: $j(window)
        }
        o.sts.currentCss = {
            top: o.sts.target.css('top'),
            right: o.sts.target.css('right'),
            bottom: o.sts.target.css('bottom'),
            left: o.sts.target.css('left')
        }
        if (!o.ie6) return;
        o.bindEvent();
    }
    $j.extend(fixed.prototype, {
        ie6: $.support.msie && $.support.version < 7.0,
        bindEvent: function () {
            var o = this;
            o.sts.target.css('position', 'absolute')
            o.overRelative().initBasePos();
            o.sts.target.css(o.sts.basePos)
            o.sts.container.scroll(o.scrollEvent()).resize(o.resizeEvent());
            o.setPos();
        },
        overRelative: function () {
            var o = this;
            var relative = o.sts.target.parents().filter(function () {
                if ($j(this).css('position') == 'relative') return this;
            })
            if (relative.size() > 0) relative.after(o.sts.target)
            return o;
        },
        initBasePos: function () {
            var o = this;
            o.sts.basePos = {
                top: o.sts.target.offset().top - (o.sts.currentCss.top == 'auto' ? o.sts.container.scrollTop() : 0),
                left: o.sts.target.offset().left - (o.sts.currentCss.left == 'auto' ? o.sts.container.scrollLeft() : 0)
            }
            return o;
        },
        setPos: function () {
            var o = this;
            o.sts.target.css({
                top: o.sts.container.scrollTop() + o.sts.basePos.top,
                left: o.sts.container.scrollLeft() + o.sts.basePos.left
            })
        },
        scrollEvent: function () {
            var o = this;
            return function () {
                o.setPos();
            }
        },
        resizeEvent: function () {
            var o = this;
            return function () {
                setTimeout(function () {
                    o.sts.target.css(o.sts.currentCss)
                    o.initBasePos();
                    o.setPos()
                }, 1)
            }
        }
    })
})(jQuery)

jQuery(function ($j) {
    $j('#footer').positionFixed()
})

//pop右下角弹窗函数
//作者：yanue
function Pop(title, url, intro) {
    this.title = title;
    this.url = url;
    this.intro = intro;
    this.apearTime = 1000;
    this.hideTime = 500;
    this.delay = 10000;
    //alert(this.intro);
    //添加信息
    this.addInfo();
    //显示
    this.showDiv();
    //关闭
    this.closeDiv();
}

Pop.prototype = {
    addInfo: function () {
        $("#popTitle span").html(this.title);
        $("#popIntro").html(this.intro);
        $("#popMore a").attr('href', this.url);
    },
    addcontent: function (content) {

        $("#popIntro").html(content);

    },
    addclick: function (content) {

        $("#popIntro").click(function () {
            content();
        });

    },
    showDiv: function (time) {
        if (!($.support.msie && ($.support.version == "6.0") && !$.support.style)) {
            $('#pop').slideDown(this.apearTime).delay(this.delay).fadeOut(400);;
        } else {//调用jquery.fixed.js,解决ie6不能用fixed
            $('#pop').show();
            jQuery(function ($j) {
                $j('#pop').positionFixed()
            })
        }
    },
    closeDiv: function () {
        $("#popClose").click(function () {
            $('#pop').hide();
        }
        );
    },
    closeDivCustom: function (fuc) {
        $("#popClose").click(function () {
            (fuc)();
            $('#pop').hide();
        }
        );
    },
    close: function () {

        $('#pop').hide();

    }

}

