var comLoading = {};
common.dialogHtml =   '<section style="width: 100%;height: 100%;display: none" id="ODialog_Cus" >'
                    + '<div style="position: fixed;top: 0px !important; z-index: 99999; width: 100%; height: 100%; background: none repeat scroll 0% 0% black; opacity: 0.5;"></div> '
                    + '<div class="ui-popup-container ui-popup-dialog">'
                    + '<div class="ui-popup-title" id="dialogtitle">'
                    + '</div>'
                    + '<div class="ui-popup-content">'
                    + '<p style="margin: 1em;padding: 0px;" id="dialogcontent">'
                    + '</p>'
                    + '<p style="text-align: center;margin: 0px;padding: 0px">'
                    + '<a style="margin-right:0.5em" id="dialogconfirm" class="ui-popup-btn">确定</a>'
                    + '<a id="dialogcancel" class="ui-popup-btn">取消</a>'
                    + '</p></div></div>'
                    + ' </section>'

common.showDialog = function (mode, strtitle, msg, onokclick, oncancelclick,okmsg,cancelmsg,longer) {
    if ($("#ODialog_Cus").length == 0) {
        $(document.body).append(common.dialogHtml);
    }
    var dialog = $("#ODialog_Cus"),
        _dialog = $(".ui-popup-container"),
        title = $("#dialogtitle"),
        content = $("#dialogcontent"),
        btncancel = $("#dialogcancel"),
        btnconfirm = $("#dialogconfirm");
    dialog.fadeIn();
    _dialog.css("left", $(document.body).width() * 0.25);
    _dialog.css("top",$(document.body).scrollTop() + $(window).height() / 2 - _dialog.height() / 2);
    title.text(strtitle);
    content.text(msg);
    if(longer){
        _dialog.css("width","90%");
        _dialog.css("left", $(document.body).width() * 0.05);
    }
    if(okmsg){
        btnconfirm.text(okmsg);
    }
    if(cancelmsg){
        btncancel.text(cancelmsg);
    }
    if (mode == 0) {
        btncancel.hide();
        btnconfirm.unbind("click");
        btnconfirm.click(function (e) {
            dialog.hide();
            dialog.remove();
            if(onokclick){
                onokclick();
            }
        })
    } else {
        btncancel.show();
        btnconfirm.unbind("click");
        btncancel.unbind("click");
        btnconfirm.click(function(e){
            dialog.hide();
            dialog.remove();
            if(onokclick){
                onokclick();
            }
        });
        btncancel.click(function () {
            dialog.hide();
            dialog.remove();
            if(oncancelclick){
                oncancelclick();
            }
        });
    }
}

common.loadingHtml = '<section style="width: 100%;height: 100%;display: none" id="ODialog_loading" >'
    + '<div style="position: fixed;top: 0px !important; z-index: 99999; width: 100%; height: 100%; background: none repeat scroll 0% 0% black; opacity: 0.5;"></div> '
    + '<div class="ui-dialog-loading">'
    + '<img alt="loading"  src="http://wx.11185gz.com.cn/gzyztest/assets/js/ajax-loader.gif"/>'
    + '<div id="loadingmsg"></div>'
    + '</div>'
    + ' </section>'

common.showLoading = function (msg) {
    if ($("#ODialog_loading").length == 0) {
        $(document.body).append(common.loadingHtml);
    }
    var loading = $("#ODialog_loading"),
        container = $(".ui-dialog-loading");
    loading.fadeIn();
    container.css("left", $(document.body).width() * 0.25);
    container.css("top", $(window).height() / 2 - container.height() / 2);
    $("#loadingmsg").text(msg);
}

common.hideLoading = function () {
    $("#ODialog_loading").fadeOut();
}

common.tipHtml = '<div style="max-width:300px ;background: none repeat scroll 0 0 #000000;text-align: center;border-radius: 5px;box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);z-index: 200;padding: 0.5em;position: fixed;color: white;" id="tipmsg"></div>';

common.showTip = function (msg) {
    if ($("#tipmsg").length == 0) {
        $(document.body).append(common.tipHtml);
    }
    var tip = $("#tipmsg");
    tip.text(msg);
    tip.fadeIn();
    tip.css("left", $(document.body).width() * 0.5-tip.width()/2);
    tip.css("top", $(window).height() * 0.4 - tip.height() / 2);
    setTimeout(function(){
        tip.fadeOut();
    },2000);
}


common.verifyPhone=function(phone){
    var pattern=/^1[3|4|5|8][0-9]\d{4,8}$/;
    if(pattern.test(phone)) {
        return true;
    }else{
        return false;
    }
}

common.authPostNo= function (s){
    var pattern =/^[0-9]{6}$/;
    var blnRet=false;
    if(pattern.exec(s))
    {
        blnRet=true;
    }
    return blnRet;
}











