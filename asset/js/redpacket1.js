var base_url='http://www.02712122.com/HBGSWechatAPIServer/'; 
// var getredpacketurl=base_url+'third_version/Activity/hubeigetpackage';
// var countViewNumurl=base_url+'third_version/Activity/hubeireadpackage';
var getredpacketurl='http://www.02712122.com/hubeigetpackage';
var countViewNumurl='http://www.02712122.com/hubeireadpackage'; 
// var getopenidurl='http://www.02712122.com/getopenid'; 
var getjssdkdataurl='http://www.02712122.com/getjssdk'; 
var click=false;
var nickname='';
var openid='';
var answerArr1='';
var answerArr2='';
var haswrite=0;
var uuid='';
var uuidInterval='';

String.prototype.getParameter = function (key) {  
    var re = new RegExp(key + '=([^&]*)(?:&)?');  
    return this.match(re) && this.match(re)[1];  
}; 

function closeWindow(){  
    if(WeixinJSBridge){
       WeixinJSBridge.call('closeWindow'); 
    }
}

/*根据code获取openid*/
// function getopenidbycode(wechatcode){
//     //$('#openid').html(wechatcode);
//     $('#loadingToast').css('display','block');
//     $.post(getopenidurl,{'code':wechatcode},function(data,status){
//         if(status=='success'){
//             data=JSON.parse(data);
//             if(data.status&&data.status.toUpperCase()=='OK'){
//                 openid=data.openid;
//                 nickname=data.nickname;
//                 //$('#openidinfo').html(openid+','+nickname); 
//                 countViewNum();
//                 var jsurl=window.location.href;
//                 $.post(getjssdkdataurl, {"jsurl": jsurl}, function(data) {
//                    data = JSON.parse(data);
//                    if(data.status=="OK"){
//                        var jsdata=data.data;
//                        wx.config({
//                            debug: false, 
//                            appId: jsdata.appid, 
//                            timestamp: jsdata.timestamp, 
//                            nonceStr: jsdata.noncestr, 
//                            signature: jsdata.signature, 
//                            jsApiList: [
//                                "onMenuShareTimeline",
//                                "onMenuShareAppMessage",
//                                "onMenuShareQQ",
//                                "onMenuShareWeibo",
//                                "onMenuShareQZone"
//                            ] 
//                         });
//                     }

//                 });
//             }else{ 
//                 if(data.msg=='no subscribe'){  //未关注
//                     var nowhref = 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzA3MTU4MTMzMw==&scene=123&from=singlemessage&isappinstalled=0#wechat_redirect';   
//                     window.location.href=nowhref;
//                 }else{  
//                     var nowhref = 'http://www.02712122.com/HBGSWechatAPIServer/html/luckyMoney/grabLuckyMoney.html';    
//                     window.location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx479a07a59a0ceb42&redirect_uri='+nowhref+'&response_type=code&scope=snsapi_base&state=123&from=singlemessage&isappinstalled=0&connect_redirect=1#wechat_redirect';
//                 }  
//             }
//         }else{ 
            
//             var nowhref = 'http://www.02712122.com/HBGSWechatAPIServer/html/luckyMoney/grabLuckyMoney.html';    
//             window.location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx479a07a59a0ceb42&redirect_uri='+nowhref+'&response_type=code&scope=snsapi_base&state=123&from=singlemessage&isappinstalled=0&connect_redirect=1#wechat_redirect';
//         }  
//     });
// }   

wx.ready(function(){
    var wxtitle="湖北高速ETC现金红包抢不停";
    var wxsubtitle="庆祝ETC联网一周年";
    var wxsharepicurl="http://gst.oss-cn-hangzhou.aliyuncs.com/hubei%2Fgrabluckymoney%2Fshareimg3.jpg";   
    var wxshareurl='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx479a07a59a0ceb42&redirect_uri=http://www.02712122.com/HBGSWechatAPIServer/html/luckyMoney/grabLuckyMoney.html&response_type=code&scope=snsapi_base&state=123&from=singlemessage&isappinstalled=0&connect_redirect=1#wechat_redirect';  
    wx.onMenuShareTimeline({
        title: wxtitle, // 分享标题
        link: wxshareurl, // 分享链接
        imgUrl: wxsharepicurl
    }); 
    wx.onMenuShareAppMessage({
        title: wxtitle, // 分享标题
        desc: wxsubtitle, // 分享描述
        link: wxshareurl, // 分享链接
        imgUrl: wxsharepicurl// 分享图标
    });
    wx.onMenuShareQQ({
        title: wxtitle, // 分享标题
        desc: wxsubtitle, // 分享描述
        link: wxshareurl, // 分享链接
        imgUrl: wxsharepicurl// 分享图标
    });
    wx.onMenuShareWeibo({
        title: wxtitle, // 分享标题
        desc: wxsubtitle, // 分享描述
        link: wxshareurl, // 分享链接
        imgUrl: wxsharepicurl // 分享图标
    });
    wx.onMenuShareQZone({
        title: wxtitle, // 分享标题
        desc: wxsubtitle, // 分享描述
        link: wxshareurl, // 分享链接
        imgUrl: wxsharepicurl // 分享图标
    });
});

/*获取中奖金额*/
function getPrize(){
    if(openid!=''){
        $('#loadingToast').css('display','block');
        // var param={};
        // param.openid=openid;
        // param.nickname=nickname;
        var myDate = new Date();
        var time=myDate.getTime();
        //var param={"openid":openid,"nickname":nickname,"time":time};
        var param={"openid":openid,"nickname":nickname,"time":time,"uuid":uuid};
        param=JSON.stringify(param);
        $.post(getredpacketurl,{'param':param},function(data,status){
       // $.post(getredpacketurl,{'openid':openid,'nickname':nickname},function(data,status){
            if(status=='success'){ 
                var isjson=Isjson(data);
                var isobject=Isobject(data); 
                if(isjson!=true&&isobject!=true){ 
                    //alert('当前网络繁忙，请稍后重试');
                    click=false;
                    $('.alert-content').html('当前网络繁忙，请稍后重试');
                    $('.alert-div').show();

                }else{ 
                   if(isjson==true){ 
                      data=JSON.parse(data);     
                    } 
                    if(data.status&&data.status.toUpperCase()=='OK'){
                        $('#loadingToast').hide();
                        click=false;
                        /*code   1  表示已经抽中，2 表示没有抽中，3表示已经中过奖，4活动未开始，5活动已经结束*/
                        if(data.code=='1'){
                            $('#nowgetprize').show();
                        }else if(data.code=='2'){
                            $('#nogetprize').show();
                        }else if(data.code=='3'){
                            $('#hasgetprize').show();
                        }else if(data.code=='4'){  
                            $('#unstart').show();
                        }else if(data.code=='5'){
                            $('#activitystop').show();
                        }else{
                             $('#activitystop').show();
                        }
                        
                    }else{ 
                        $('#loadingToast').hide();  
                        click=false;
                       // $('#nogetprize').show(); 
                        $('.alert-content').html(data.msg);
                        $('.alert-div').show();

                    }
                }
            }else{
                $('#loadingToast').hide();
            }  
        });
    }
    
    //click=true; 
}

/*5分钟刷新一次uuid*/
function reflashuuid(){
    if(uuidInterval!=''){
        clearInterval(uuidInterval);
    }
    setTimeout(function(){
        countViewNum();
    },240000);
}

/*红包记录数*/
function countViewNum(){
    if(openid!=''){
        //$('#loadingToast').css('display','block');
        var myDate = new Date();
        var time=myDate.getTime();
        var param={"openid":openid,"nickname":nickname,"time":time};
        param=JSON.stringify(param);
        $.post(countViewNumurl,{'param':param},function(data,status){
            if(status=='success'){ 
               //uuid 
                var isjson=Isjson(data);
                var isobject=Isobject(data); 
                if(isjson!=true&&isobject!=true){ 
                   window.location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx479a07a59a0ceb42&redirect_uri=http://www.02712122.com/HBGSWechatAPIServer/html/luckyMoney/grabLuckyMoney.html&response_type=code&scope=snsapi_base&state=123&from=singlemessage&isappinstalled=0&connect_redirect=1#wechat_redirect';
                }else{ 
                   if(isjson==true){ 
                      data=JSON.parse(data);     
                    } 
                    if(data.status&&data.status.toUpperCase()=='OK'){
                        $('#loadingToast').hide(); 
                        uuid=data.data;
                        reflashuuid();  
                    }
                }
            }else{
                $('#loadingToast').hide();     
            } 
        });
    }else{
        window.location.href='https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx479a07a59a0ceb42&redirect_uri=http://www.02712122.com/HBGSWechatAPIServer/html/luckyMoney/grabLuckyMoney.html&response_type=code&scope=snsapi_base&state=123&from=singlemessage&isappinstalled=0&connect_redirect=1#wechat_redirect';
    }
    //click=true; 
}

/*判断字符串是否为json格式*/
function Isjson(value){
  try{
    con = JSON.parse(value);
    return true;
  }catch(e){
    return false;
  }
}
/*判断字符串是否为json对象*/
function Isobject(data){
  if(typeof(data) == "object" && Object.prototype.toString.call(data).toLowerCase() == "[object object]" && !data.length){
      return true;
  }else{
    return false;
  }
}

/*校验当前时间是否已经到达10点*/
function laterThan10clock(){
    var nowtime=new Date();
    var nowhour=nowtime.getHours();       //获取当前小时数(0-23)
    if(parseInt(nowhour)<10){
        return false;
    }else{
        return true;
    }
}


$(document).ready(function(){
    /* 获取url值  */
    var nowhref = location.href;  
    wechatcode=nowhref.getParameter("code"); 
    //countViewNum();
    $('.redEnvelope').unbind('click').bind('click',function(){
        var isStart=laterThan10clock();
        if(isStart){
            if(haswrite==0){
                $('#questionBox').show();
            }else{
               if(click){
                    return;
                }
                click=true;
                getPrize(); 
            }
        }else{
            $('.alert-content').html('活动未开始');
            $('.alert-div').show();
        }
        // $('#activitystop').show(); 
    });
    $('.closebox').unbind('click').bind('click',function(){
        $('.showBox').hide();
    });
    $('.answer-tagpic').unbind('click').bind('click',function(){
        var rownum=$(this).attr('data-row');
        $('.qestion-item').eq(rownum).find('.identifyicon').hide();
        if(rownum==0){
            answerArr1=$(this).html();
        }else{
            answerArr2=$(this).html();
        }
        $(this).parent().find('.identifyicon').show();
    });
    $('.submitAnswer').unbind('click').bind('click',function(){
        console.log('haswrite:'+haswrite);
        console.log('click:'+click);
        if(answerArr1!='D'||answerArr2!='C'){
            $('.alert-content').html('好可惜，答错了，再来一次吧！');
            $('.alert-div').show();
        }else{
            $('.showBox').hide();
            if(click){
                return;
            }
            click=true;
            haswrite=1;
            getPrize();
        }
    });
});
