//==========websocket
//系统设置读
var READ_SYSTEMSET = "00400";
//系统设置写
var CHANGE_SYSTEMSET = "10400";
//时段读
var READ_TIMING = "00200";
//时段写
var CHANGE_TIMING = "10200";
//工作表读
var READ_WORTAB2 = "00300";
//工作表写
var CHANGE_WORTAB2 = "10300";
//方案写
var CHANGE_PLAN = "10100";
//方案读
var READ_PLAN = "00100";
//实时数据读
var REAL_STATE = "00000";
//手动控制写
var CHANGE_MANUAL = "10500";
(function ($) {
    $.websocket = function (options) {
        var defaults = {
            domain: "120.236.139.234",
            port: 10002,
            protocol: ""
        };
        var opts = $.extend(defaults, options);
        var szServer = "ws://" + opts.domain + ":" + opts.port + "/" + opts.protocol;
        var socket = null;
        var bOpen = false;
        var t1 = 0;
        var t2 = 0;
        var messageevent = {
            onInit: function () {
                if (!("WebSocket" in window) && !("MozWebSocket" in window)) {
                    return false;
                }
                if (("MozWebSocket" in window)) {
                    socket = new MozWebSocket(szServer);
                } else {
                    socket = new WebSocket(szServer);
                }
                if (opts.onInit) {
                    opts.onInit();
                }
            },
            onOpen: function (event) {
                bOpen = true;
                if (opts.onOpen) {
                    opts.onOpen(event);
                }
            },
            onSend: function (msg) {
                t1 = new Date().getTime();
                if (opts.onSend) {
                    opts.onSend(msg);
                }
                socket.send(msg);
            },
            onMessage: function (msg) {
                t2 = new Date().getTime();
                if (opts.onMessage) {
                    opts.onMessage(msg.data, t2 - t1);
                }
            },
            onError: function (event) {
                if (opts.onError) {
                    opts.onError(event);
                }
            },
            onClose: function (event) {
                if (opts.onclose) {
                    opts.onclose(event);
                }
                if (socket.close() != null) {
                    socket = null;
                }
            }
        }

        messageevent.onInit();
        socket.onopen = messageevent.onOpen;
        socket.onmessage = messageevent.onMessage;
        socket.onerror = messageevent.onError;
        socket.onclose = messageevent.onClose;
        $(window).unload(function () {
            if (socket!=null&&socket.close() != null) {
                socket = null;
            }
        });
        this.send = function (pData) {
            if (bOpen == false) {
                return false;
            }
            messageevent.onSend(pData);
            return true;
        }
        this.close = function () {
            messageevent.onClose();
        }
        return this;
    };
})(jQuery);