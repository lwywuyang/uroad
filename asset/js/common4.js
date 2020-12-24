var common = {};
common.loadingHtml = '<div class="load1">'
      +'<div class="rect1"></div>'
      +'<div class="rect2"></div>'
      +'<div class="rect3"></div>'
      +'<div class="rect4"></div>'
      +'<div class="rect5"></div>'
      +'<p>正在加载中</p>'
      +'</div>'
    ;

common.showDialog = function (mode, strtitle, msg, onokclick, oncancelclick) {
    if ($(".ui-popup-screen").length == 0) {
        $(document.body).append(common.dialogHtml);
    }
    var screen = $(".ui-popup-screen"), dialog = $(".ui-popup-container"),
      title = $("#dialogtitle"), content = $("#dialogcontent"), btncancel = $("#dialogcancel")
      , btnconfirm = $("#dialogconfirm");
    if (window.screen.height > ($(document.body).height() + $(window).scrollTop())) {
        screen.height(window.screen.height);
    } else {
        screen.height($(document.body).height() + $(window).scrollTop());
    }
    screen.show();
    dialog.show();
    dialog.css("left", $(document.body).width() * 0.25);
    dialog.css("top", $(window).scrollTop() + $(window).height() / 2 - dialog.height() / 2);
    title.text(strtitle);
    content.text(msg);
    if (mode == 0) {
        btncancel.hide();
        btnconfirm.unbind("click");
        btnconfirm.click(function () {
            screen.hide();
            dialog.hide();
        })
    } else {
        btncancel.show();
        btnconfirm.unbind("click");
        btncal.unbind("click");
        btnconfirm.click(onokclick);
        btncancel.click(oncancelclick);
    }
}


common.showLoading = function (msg) {
    if ($(".load1").length == 0) {
        $(document.body).append(common.loadingHtml);
    }
    var loading = $(".load1");
    loading.show();
}
   
common.hideLoading = function () {
    $(".load1").hide();

}


/*common.showStation = function (data, road) {
  
    var remark = data.remark ? data.remark : "{}";
    var shortname = road ? road.shortname : "";
    //remark = eval("(" + remark + ")");
    var stationHtml = '  <div id="stationbg" class="ui-popup-screen">'
      + ' </div>'
      + '<div class="station">'
      + '   <div>'
      + '      <img style="float: left;" alt="" src="../images/ic_liststation.jpg" /><div style="float: left;">'
      + '          <span>名称:' + data.name + '</span><br />'
      + '          <span>道路:' + shortname + '</span></div>'
      + '</div>'
      + ' <div style="clear: both;">'
      + '    地址:' + remark.address
      + ' </div>'
      + ' <div>'
      + '桩号:K' + data.miles
      + '</div>'
      + '<div>'
      + '出口车首数:' + remark.exportlanes + '        出口ETC车首数:' + remark.entralanes
      + ' </div>'
      + '<div>'
      + '入口车首数:' + remark.exportetclanes + '     入口ETC车首数:' + remark.entraetclanes
      + '</div>'
      + '<div>'
      + '出口通达地点:' + remark.exportnames
       + '</div>'
       + '<div>'
        + '入口通达地点:' + remark.entranames
       + '</div>'
        + '<div>'
        + '连接外部通道:' + remark.externalroad
        + '</div>'
     + '</div>'
    stationHtml = stationHtml.replace(/undefined/g, "暂无");
    $(document.body).append(stationHtml);
    var stationbg = $("#stationbg");
    stationbg.height($(document.body).height() + $(window).scrollTop());
    stationbg.show();
    var station = $(".station");
    station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2);
    stationbg.click(function () {
        stationbg.remove();
        station.remove();
    })
}*/
common.showStation = function (data, road) {
    //var remark = data.remark ? data.remark : "{}";
    var remark = data ? data : "{}";
    var shortname = road ? road : "";
    //remark = eval("(" + remark + ")");
   
    var stationHtml = '  <div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
      + '<div style="background: none repeat scroll 0% 0% white; color: rgb(0, 0, 0); top: -12px; border: 1px solid rgb(204, 204, 204);" class="station">'
      + '<div style="height: 2em;background: #2f6cc5;margin: -10px -10px 10px -10px;border-radius: 0.6em 0.6em 0 0;"></div>'
      + '   <div class="clearfix">'
      + '      <img style="float: left;width: 80px;margin: 10px;" alt="" src="assets/images/ic_liststation.jpg" /><div style="margin: 0.5em;margin-top:1.5em;">'
      + '          <span>名称:</span><span class="genText">' + data.name + '</span><br />'
      + '          <span>道路:</span><span class="genText">' + shortname + '</span></div>'
      + '</div>'
      + ' <div style="clear: both;">'
      + '    <span>电话:</span><span class="genText"><a href="tel:' + data.phone
      + '">'+data.phone+'</a> </span></div>'
      + ' <div>'
      + '<span>桩号:</span><span class="genText">K' + data.miles
      + '</span></div>'
      + '<div>'
      + '<span>出口车道数:</span><span class="genText">' + data.exportlanes + '</span><span style="margin-left: 10px;">出口ETC车道数:</span><span class="genText">' + data.exportetclanes
      + '</span></div>'
      + '<div>'
      + '<span>入口车道数:</span><span class="genText">' + data.entralanes + '</span><span style="margin-left: 10px;">入口ETC车道数:</span><span class="genText">' + data.entraetclanes
      + '</span></div>'
      + '<div>'
      + '<span>标识地点:</span><span class="genText">' + data.signplace
       + '</span></div>'
       + '<div>'
        + '<span>相邻道路:</span><span class="genText">' + data.nearroad
       + '</span></div>'
        + '<div>'
        + '<span>可到达城市:</span><span class="genText">' + data.avrride
        + '</span></div>'
        + '<div>'
        + '<span>下站直行:</span><span class="genText">' + data.leadcenter
        + '</span></div>'
        + '<div>'
        + '<span>下站左转:</span><span class="genText">' + data.leadleft

        + '</span></div>'
        + '<div>'
        + '<span>下站右转:</span><span class="genText">' + data.leadright
        + '</span></div>'
        + '<div>'
        + '<span>景区、企业、单位:</span><span class="genText">' + data.scenery
        + '</span></div>'
     + '</div>'
    stationHtml = stationHtml.replace(/undefined/g, "");
    $(document.body).append(stationHtml);
    var stationbg = $("#stationbg");
    stationbg.height($(document.body).height() + $(window).scrollTop());
    stationbg.show();
    var station = $(".station");
    station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2);
    stationbg.on("click",function () {
        stationbg.remove();
        station.remove();

    })
}


common.showVms = function (data) {
    var vmsHtml = '  <div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
      + '<div class="station">';
    for (var i = 0; i < data.length; i++) {
        var miles = data[i].vms ? data[i].vms.miles : "";
        if (miles) {
            miles += "桩号:K";
        } else {
            miles = "";
        }
        if (data[i].remark != "") {
            var html = '<div style="width: 100%; text-align: right;color:white">'
            html += '<span style="float:left">' + miles + '</span>'
            html += '<span>' + data[i].time + '</span></div>'
            html += '  <div style="width: 100%; color: White; text-align: left;">'
            html += data[i].remark
            vmsHtml += html;
        }
    }
    vmsHtml += ' </div>';
    vmsHtml += '</div>';
    $(document.body).append(vmsHtml);
    var stationbg = $("#stationbg");
    stationbg.height($(document.body).height() + $(window).scrollTop());
    stationbg.show();
    var station = $(".station");
    var swidth = $(window).scrollTop() + $(window).height() / 2 - station.height() / 2;
    if (swidth < 0) {
        swidth = 10;
    }
    station.css("top", swidth);
   stationbg.on("click",function () {
        stationbg.remove();
        station.remove();
		
    })
}

       

// common.showCCTV = function (data) {
//     var vmsHtml = '  <div id="stationbg" style="background: none;" class="ui-popup-screen">'
//       + ' </div>'
//       + '<div style="text-align:center" class="cctv">'
//       + '<div style="color:white">' + data.name + '</div>'
//       + '<img style="width:100%" alt="" src=' + data.picturefile + '>';
//     vmsHtml += ' </div>';
//     vmsHtml += '</div>';
//     $(document.body).append(vmsHtml);
//     var stationbg = $("#stationbg");
//     stationbg.height($(document.body).height() + $(window).scrollTop());
//     stationbg.show();
//     var station = $(".cctv");
//     station.css("top",'30%');
//     // station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2);
//     stationbg.on("click",function () {
//         stationbg.remove();
//         station.remove();

//     })
// }
common.showCCTV = function (obj) {
  console.log(JSON.stringify(obj));
    var vmsHtml = '  <div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
      + '<div style="text-align:center;height:180px;padding:0.6em 0;" class="cctv swiper-container"><div class="swiper-wrapper" style="width:100%;height:100%;">';
    for (var i = 0; i < obj.length; i++) {
      var data=obj[i]; 
      vmsHtml +='<div class="swiper-slide" style="width:100%;display: block;height:100%;"><div style="color:white">' + data.name + '</div>'
    + '<img style="width:100%;height:130px;" alt="" src=' + data.picturefile + '></div>';
    };
      
   // vmsHtml += ' </div>';
    vmsHtml += '<div class="swiper-pagination"></div></div></div>';
    $(document.body).append(vmsHtml);
    var stationbg = $("#stationbg"); 
    stationbg.height($(document.body).height() + $(window).scrollTop());
    stationbg.show();
    var station = $(".cctv");
    // station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2);
    station.css("top", '30%');
    var ele = $(".cctv .swiper-slide");
        var len = ele.length;
        var cs;
        if(len > 1){
          cs = {
              loop : true,
              pagination : '.swiper-pagination',
              paginationClickable :true,
              autoplayDisableOnInteraction:false,
              grabCursor: true,
              autoplay:1000,
              speed:500,
            }
        }else{
          cs = {
              pagination : '.swiper-pagination',
              paginationClickable :true,
            }
        }
        var mySwiper = new Swiper ('.cctv', cs); 
    stationbg.on("click",function () {
        stationbg.remove();
        station.remove();

    })
}



common.showServer = function (data, road) {
    var remark = data.remark ? data.remark : "{}";
    var shortname = road;
	var address=data.address;
	if(null==address){
		address="暂无数据";
	}

    //remark = eval("(" + remark + ")");
	 var stationHtml = '  <div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
      + '<div style="background: none repeat scroll 0% 0% white; color: rgb(0, 0, 0); top: -12px; border: 1px solid rgb(204, 204, 204);" class="station">'
      + '<div style="height: 2em;background: #2f6cc5;margin: -10px -10px 10px -10px;border-radius: 0.6em 0.6em 0 0;"></div>'
      + '   <div class="clearfix">'
      + '      <img style="float: left;width: 80px;margin: 10px;" alt="" src="assets/images/ic_listservice_1.jpg" /><div style="margin: 0.5em;margin-top:1.5em;">'
      + '          <span>名称:</span><span class="genText">' + data.name + '</span><br />'
      + '          <span>道路:</span><span class="genText">' + shortname + '</span></div>'
      + '</div>'
      + ' <div style="clear: both;">'
      + '    <span>地址:</span><span class="genText">' + address
      + ' </span></div>'
      + ' <div>'
      + '<span>桩号:</span><span class="genText">K' + data.miles.replace(".","+")
      + '</span></div>'
      + '<div>'
      + '<span>服务内容:</span><span class="genText">' + remark + '</span></div>'
     + '</div>'
	
	
	
	
	/**
    var stationHtml = '<div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
      + '<div class="station">'
      + '   <div>'
      + '      <img style="float: left;" alt="" src="../images/ic_listservice_1.jpg" /><div style="float: left;">'
      + '          <span>名称:' + data.name + '</span><br />'
      + '          <span>道路:' + shortname + '</span></div>'
      + '</div>'
      + ' <div style="clear: both;">'
      + '    地址:' + data.address
      + ' </div>'
      + ' <div>'
      + '桩号:K' + data.miles.replace(".","+")
      + '</div>'
      + ' <div>'
      + '停车号:' + data.nowinwaynum
      + '</div>'
      + '<div>'
      + '服务内容(一)加油站:<br />' + remark
      + ' </div>'
      
     + '</div>'
	 **/
    stationHtml = stationHtml.replace(/undefined/g, "暂无");
    $(document.body).append(stationHtml);
	
    var stationbg = $("#stationbg");
    stationbg.height($(document.body).height() + $(window).scrollTop());
    var station = $(".station");
    stationbg.show();
	
    station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2); 
	
    stationbg.on("click",function () {
        stationbg.remove();
        station.remove();
		
    })
	
}




common.showEvent = function (data, road) {
  //console.log("data:"+JSON.stringify(data));
    var remark = data.remark;
    var shortname = road;
    if (shortname == undefined) {
        shortname = "";
    }
    var stationHtml = '<div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
      +'<div class="station">'
          + '<div style="width: 100%; text-align: left;">'
           +"所在高速："+ shortname
        + '</div>';
      if(data.eventtype!='1006005'){
        stationHtml += '<div style="width: 100%; text-align: left;">'
             + '桩号区间:' + data.startstake + '~' + data.endstake
           + '</div>'
          + '<div style="width: 100%; text-align: left;">'
            + '站点区间:' + data.startnodename + '~' + data.endnodename
           + '</div>';
      }
     
		  stationHtml +='<div style="width: 100%; text-align: left;">'
          + '发布时间:' + data.occtime
          + '</div>'
         + '<div style="width: 100%; text-align: left;">'
           + "事件描述:" + data.remark
         + '</div>'
     + '</div>'
    $(document.body).append(stationHtml);
    var station = $(".station");
    var stationbg = $("#stationbg");
    stationbg.height($(document.body).height() + $(window).scrollTop());
    stationbg.show();
    station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2);
   stationbg.on("click",function () {
        stationbg.remove();
        station.remove();
		
    })
}


common.showGZEvent = function (data, road) {
    var remark = data.remark;
    var shortname = road;
    if (shortname == undefined) {
        shortname = "";
    }
    var stationHtml = '<div id="stationbg" style="background: none;" class="ui-popup-screen">'
      + ' </div>'
     + '<div class="station">'
          + '<div style="width: 100%; text-align: left;">'
           +"所在高速："+ shortname
        + '</div>'
		  + '<div style="width: 100%; text-align: left;">'
          + '发布时间:' + data.occtime
          + '</div>'
         + '<div style="width: 100%; text-align: left;">'
           + "事件描述:" + data.remark
         + '</div>'
     + '</div>'
    $(document.body).append(stationHtml);
    var station = $(".station");
    var stationbg = $("#stationbg");
    stationbg.height($(document.body).height() + $(window).scrollTop());
    stationbg.show();
    station.css("top", $(window).scrollTop() + $(window).height() / 2 - station.height() / 2);
   stationbg.on("click",function () {
        stationbg.remove();
        station.remove();
		
    })
}







common.json2str = function (o) {
    var arr = [];
    var fmt = function (s) {
        if (typeof s == 'object' && s != null) return common.json2str(s);
        return /^(string|number)$/.test(typeof s) ? "'" + s + "'" : s;
    }
    for (var i in o) arr.push("'" + i + "':" + fmt(o[i]));
    return '{' + arr.join(',') + '}';
}


common.GetRequest = function () {
    var url = location.search; //获取url中"?"符后的字串
    url = decodeURI(url);
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}
common.showLoadingNoColor = function (msg) {
    if ($(".ui-loadingk-screen").length == 0) {
        $(document.body).append(common.loadingHtmlNoColor);
    }else{
        $(".ui-loadingk-screen").remove();
        $(document.body).append(common.loadingHtmlNoColor);
    }
    var screen = $(".ui-loadingk-screen"), loading = $(".loadingcontainer");
    if (window.screen.height > ($(document.body).height() + $(window).scrollTop())) {
        screen.height(window.screen.height);
    } else {
        screen.height($(document.body).height() + $(window).scrollTop());
    }
    screen.show();
    loading.show();
    loading.css("left", $(document.body).width() * 0.3);
    loading.css("top", $(window).scrollTop() + $(window).height() / 2 - loading.height() / 2);
    $("#loadingmsg").text(msg);
}
common.hideLoadingNoColor = function () {
    $(".ui-loadingk-screen").hide();
    $(".loadingcontainer").hide();
}



