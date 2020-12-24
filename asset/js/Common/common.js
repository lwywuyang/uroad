function JAjax(ajaxType,url, paramObj, successFunc, isShowLoading,failFunc) {   
    if (isShowLoading)
        showLoading();
    var postjson;
    var dataType='';
    if(ajaxType=='base64'){
        postjson={};
        postjson.fun=paramObj.func;  
        postjson.data=paramObj.param;    
        postjson=JSON.stringify(postjson);
        var b = new Base64();
        postjson=b.encode(postjson);  
        dataType='html';
    }else if(ajaxType=='base64Md5'){
        postjson={};
        postjson.func=paramObj.func;  
        postjson.sign=hex_md5(postjson.func);
        postjson.data=paramObj.param;  
        postjson=JSON.stringify(postjson);
        var b = new Base64();
        postjson=b.encode(postjson);     
        dataType='html';
    }else{
        postjson=paramObj;
    }
    $.ajax({
         type: "POST",
         url: url,
         data: postjson,
         dataType: "html",
         success: function(result){
            if(ajaxType=='base64Md5'){
               result=b.decode(result); 
            }
            if (successFunc) {
                if (typeof(result) != 'object') {
                    var data = result ? eval("(" + result + ")") : null;
                } else {
                    var data = result;
                }
                successFunc(data);
            }
        },
        error:function(error){
            //
            if (failFunc) {
                failFunc(error);
            }else{
                showToptips('获取信息失败');   
            }
        },
        complete:function(data){  
            if (isShowLoading)
                hideLoading();
        }
    });    
} 

function formsublimt(obj, url) {
    var f = document.createElement("form");
    document.body.appendChild(f);
    for (var i in obj) {
        var k;
        k = document.createElement("input");
        k.type = "hidden";
        f.appendChild(k);
        k.value = obj[i];
        k.name = i;
    }
    f.action = url;
    f.method = "post";
    f.submit();
}
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg); //匹配目标参数
    if (r != null) return decodeURIComponent(r[2]);
    return null; //返回参数值
}

/*topTIps-顶部提示*/
var topTipTimeout='';
function showToptips(content){
    hideToptips();
    var callback='';
    var className = '';
    var duration=3000;
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 3000;
    if (typeof options === 'number') {  duration=options;}
    if (typeof options === 'function') {callback= options;}
    if (typeof options === 'object') { duration=options.duration; className=options.className; callback= options.callback; }  
    var html='<div class="weui-toptips weui-toptips_warn '+className+'" style="display:block">'+content+'</div>';
    $('body').append(html);
    topTipTimeout=setTimeout(function(){ 
        hideToptips(callback)
    },duration);
}
function hideToptips(callback){   
    if(topTipTimeout){
        clearTimeout(topTipTimeout);
        topTipTimeout='';    
    }
    if($('.weui-toptips').length!=0){
        $('.weui-toptips').remove();
    }
    if (typeof callback === 'function') {
        callback();    
    }
}  
/*actionsheet 弹出式菜单*/
var _sington=false;
var isAndroid=false;
function showActionSheet() {
    hideActionSheet();       
    var menus = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : [];
    var actions = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    var menustr='<div class="weui-actionsheet__menu"> ';
    for (var i = 0; i < menus.length; i++) {
        menustr+='<div class="weui-actionsheet__cell">'+menus[i].label+'</div>';
    };
    menustr+='</div>';
    var actionstr='<div class=weui-actionsheet__action> ';
    for (var i = 0; i < actions.length; i++) {
        actionstr+='<div class=weui-actionsheet__cell>'+actions[i].label+'</div>';
    };
    actionstr+='</div>';
    var html='<div class="custom-classname actionSheetWrap"><div class="weui-mask weui-animate-fade-in"></div><div class="weui-actionsheet">'+menustr+actionstr+'</div></div>';
    $('body').append(html);
    $('.weui-actionsheet').addClass(isAndroid ? 'weui-animate-fade-in' : 'weui-animate-slide-up');
    $('.weui-mask').addClass('weui-animate-fade-in').on('click', function () {
        hideActionSheet();
    });
    $('.weui-actionsheet__menu').on('click', '.weui-actionsheet__cell', function (evt) {
        var index = $('.weui-actionsheet__menu .weui-actionsheet__cell').index(this);
        menus[index].onClick.call(this, evt);
        hideActionSheet(); 
    });  
    $('.weui-actionsheet__action').on('click', '.weui-actionsheet__cell', function (evt) {
        var index = $('.weui-actionsheet__action .weui-actionsheet__cell').index(this);
        actions[index].onClick.call(this, evt);
        hideActionSheet(); 
    });  
}  
function hideActionSheet(callback) {
    if($('.actionSheetWrap').lenght!=0){
        $('.weui-actionsheet').addClass(isAndroid ? 'weui-animate-fade-out' : 'weui-animate-slide-down');
        $('.weui-mask').addClass('weui-animate-fade-out').on('animationend webkitAnimationEnd', function () {
            $('.actionSheetWrap').remove();
            _sington = false;
            callback && callback();
        });
    }  
}
/*生成alert*/
function myalert() {
    var anum = arguments.length;
    if (anum < 1) {
        return;
    }
    if (anum == 3) {
        var text = arguments[0] ? arguments[0] : '';
        var title = arguments[1] ? arguments[1] : '温馨提示';
        var sureFun = arguments[2] ? arguments[2] : '';
        var surebtntext = '确定';
    } else {
        var text = arguments[0] ? arguments[0] : '';
        var title = arguments[1] ? arguments[1] : '温馨提示';
        var surebtntext = arguments[2] ? arguments[2] : '确定';
        var sureFun = arguments[3] ? arguments[3] : '';
    }
    var alertstr = '<div  class="alert-div" id="alertDialog">' +
        '<div class="alert-box">' +
        '<h5 class="alert-title">' + title + '</h5> ' +
        '<p class="alert-content">' + text + '</p>' +
        '<button class="alert-sure">' + surebtntext + '</button>' +
        '</div>' +
        '</div>';
    $(document.body).append(alertstr);
    $('#alertDialog .alert-sure').unbind('click').bind('click', function() {
        $('#alertDialog').remove();
        if (sureFun) {
            sureFun();
        }
    });
}
/*生成Confirm*/
function myconfirm(title, text, canceltext, surebtntext, cancelFun, sureFun) {
    if (title != '') {
        var confirmTile = title;
    } else {
        var confirmTile = '温馨提示';
    }
    if (canceltext == '') {
        canceltext = '取消';
    }
    if (surebtntext == '') {
        surebtntext = '确定';
    }
    var alertstr = '<div  class="confirm-div" id="confirmDialog">' +
        '<div class="confirm-box">' +
        '<h5 class="confirm-title">' + confirmTile + '</h5> ' +
        '<p class="confirm-content">' + text + '</p>' +
        '<div class="weui_dialog_ft">' +
        '<button class="weui_btn_dialog comfirm-cancel">' + canceltext + '</button>' +
        '<button  class="weui_btn_dialog confirm-sure">' + surebtntext + '</button> ' +
        '</div>' +
        '</div>' +
        '</div>';
    $(document.body).append(alertstr);
    $('#confirmDialog .comfirm-cancel').unbind('click').bind('click', function() {
        $('#confirmDialog').remove();
        if (cancelFun) {
            cancelFun();
        }
    });
    $('#confirmDialog .confirm-sure').unbind('click').bind('click', function() {
        $('#confirmDialog').remove();
        if (sureFun) {
            sureFun();
        }
    });
}
/*生成loading*/
function showLoading(text){
if(!!text){
  var loadingtext=text;
}else{
  var loadingtext='数据加载中';
}
var loadingstr='<div id="loadingToast" class="weui_loading_toast" style="display:none;">'+
    '<div class="weui_mask_transparent"></div>'+
    '<div class="weui_toast">'+
        '<div class="weui_loading">'+
            '<div class="weui_loading_leaf weui_loading_leaf_0"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_1"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_2"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_3"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_4"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_5"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_6"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_7"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_8"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_9"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_10"></div>'+
            '<div class="weui_loading_leaf weui_loading_leaf_11"></div>'+
        '</div>'+
        '<p class="weui_toast_content">'+loadingtext+'</p>'+
    '</div>'+
'</div>';
if ($("#loadingToast").length == 0) {
    $(document.body).append(loadingstr);
}
var loading = $("#loadingToast");
loading.show();
}

/*去除loading*/
function hideLoading(){
  if ($("#loadingToast").length != 0) {
    $('#loadingToast').remove();
  }
}
function locationhref(url) {
    var wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" + appid + "&redirect_uri=" + encodeURIComponent(url) + "&response_type=code&scope=snsapi_base&state=0&connect_redirect=1#wechat_redirect";
    console.log(wxurl);
    window.location.href = wxurl;
}