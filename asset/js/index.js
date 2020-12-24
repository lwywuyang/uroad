      function ReSizeiFrame(id) {
        setTimeout(function() {
          $("#pagecontent").height(document.documentElement.clientHeight - 50);
          ReSizePage();
        },
        100);
      }
      $(window).resize(function() {
        //process here
        ReSizePage();
      });

      var last_parent_id = "";
      var last_sub_id = "";
      function gotoURL(parentId, subId, url, isParent, hasChildren) {

        if (isParent == 0) {
          if (last_parent_id != "") {
            $("#" + last_parent_id).removeClass("active") ;
            $("#" + last_parent_id + " a").removeClass("rootactive");
          }
          $("#" + parentId).addClass("active");
          if (last_sub_id != "") {
            $("#" + last_sub_id).removeClass("active");
            $("#" + last_sub_id + " a").removeClass("rootactive");
          }
          $("#" + subId).addClass("active");
          last_parent_id = parentId;
          last_sub_id = subId;
          $("#iframeContent").attr("src", url);
          // showwaiting();
        } else {
          if (hasChildren == 0) {
            if (url && url != "") {
              if (last_parent_id != "") {
                $("#" + last_parent_id).removeClass("active") ;
                $("#" + last_parent_id + " a").removeClass("rootactive");
              }
              if (last_sub_id != "") {
                $("#" + last_sub_id).removeClass("active");
                $("#" + last_sub_id + " a").removeClass("rootactive");
              }

              $("#" + parentId).addClass("active");
              $("#" + subId).addClass("active");
              if ($("#" + subId).attr("isRoot") == "true") 
                $("#" + subId + " a").addClass("rootactive");
              last_parent_id = subId;
              $("#iframeContent").attr("src", url);
            }
          }
        }

      }
      function setFullScreen() {
        var iframe = document.getElementById("iframeContent");
        iframe.height = $("#pagecontent").height() - 20;
      }
      function ReSizePage() {
        return; 
        var FFextraHeight = 0;
        if (window.navigator.userAgent.indexOf("Firefox") >= 1) {
          FFextraHeight = 16;
        }
        var iframe = document.getElementById("iframeContent");

        if (iframe && !window.opera) {
          iframe.style.display = "block";
          if (iframe.Document && iframe.Document.body && iframe.Document.body.scrollHeight) {
            if (iframe.Document.body.scrollHeight < 400) iframe.height = 400;
            else iframe.height = iframe.Document.body.scrollHeight;

            //alert(iframe.height + "   " + iframe.Document.body.scrollHeight);
          } else if (iframe.contentDocument && iframe.contentDocument.body && iframe.contentDocument.body.scrollHeight > 0) {
            if (iframe.contentDocument.body.scrollHeight != 0) iframe.height = iframe.contentDocument.body.scrollHeight + FFextraHeight;
            else iframe.height = $("#pagecontent").height();
            //alert(iframe.height + "   " + iframe.contentDocument.body.offsetHeight);
          } else {
            iframe.height = $("#pagecontent").height() - 30;
          }

        }
      }

      var layerwin;
      var FunctionName;
      function showNoModelLayerPage(url, title, w, h, func, arg) {
        FunctionName = null;
        var width, height;
        if (w) width = w;
        else width = 1000;
        if (h) height = h;
        else height = $(window).height() - 50;
        layerwin = $.layer({
          type: 2,
          maxmin: true,
          shadeClose: true,
          title: title,
          shade: [0, '#000'],

          offset: ['100px', ''],
          moveType: 1,
          shadeClose: false,
          area: [width + 'px', height + 'px'],
          iframe: {
            src: url
          },
          close: function(index) {

},
          end: function(index) {

            if (func) {
              func(arg);
            }
          }
        });

      }
      function showEditPass() {
        var w = document.body.scrollWidth;
        var layer_w = (document.body.scrollWidth / 2 - 233) + "px";
        //$("body").addClass("hidescroll");
        layerwin = $.layer({
          type: 2,
          title: '修改密码',
          moveType: 1,
          iframe: {
            src: "login/editpassword/"+empid
          },
          area: ['450px', '330px'],
          offset: ['50px', layer_w],
          close: function(index) {
            layer.close(index);
          }
        });
      }
      //跳转
      var oldlocationurl='';
      function showLayerFullPage(locationurl,url){
          // alert(locationurl+'-----'+url);
          // if(oldlocationurl==''){
          //   oldlocationurl=locationurl;
          // }
          $("#iframeContent").attr("src", url);
      }
      //跳转返回
      function showLayerFullPagecomeback(){
          // if(oldlocationurl!=''){
            $("#iframeContent").attr("src", oldlocationurl);
            // oldlocationurl='';
          // }
      }
      
      function showLayerPage(url, title, w, h, func, arg) {
        FunctionName = null;
        var width, height, offset_y = '100px';
        if (w) width = w;
        else width = $(window).width()-100;
        if (h || h != 0) height = h;
        else {
          height = $(window).height() - 100;
          offset_y = "50px";
        }
        layerwin = $.layer({
          type: 2,
          maxmin: true,
          shadeClose: true,
          title: title,
          shade: [0.5, '#000'],

          offset: [offset_y, ''],
          moveType: 1,
          shadeClose: false,
          area: [width + 'px', height + 'px'],
          iframe: {
            src: url,
            scrolling: 'yes'
          },
          close: function(index) {

},
          end: function(index) {

            if (func) {
              func(arg);
            }
          }
        });

      }
      function showLayerPageCallBack(url, title, w, h, id, func) {
        FunctionName = func;
        var width, height;
        if (w) width = w;
        else width = 1000;
        if (h) height = h;
        else height = $(window).height() - 50;
        layerwin = $.layer({
          type: 2,
          maxmin: true,
          shadeClose: true,
          title: title,
          shade: [0.5, '#000'],

          offset: ['100px', ''],
          moveType: 1,
          shadeClose: false,
          area: [width + 'px', height + 'px'],
          iframe: {
            src: url
          },
          close: function(index) {
            if (func) {
              var val = layer.getChildFrame(id, index).val();
              func(val);
            }
          },
          end: function(index) {

}
        });

      }
      function closeLayerPage() {
        layer.close(layerwin);
      }
      function closeLayerPage(id) {
        if (FunctionName) {
          var val = layer.getChildFrame(id, layerwin).val();
          FunctionName(val);
        }

        layer.close(layerwin);
      }
   
//查看图片
  function showLayerTopImage(url) {

            layerwin = $.layer({
                type: 1,
                shade: [0.5, '#000'],
                area: ['auto', 'auto'],
                title: false,
                border: [0],
                page: { html: '<div style="padding:3px" id="imgdiv"><img  width=500px  src=' + url + ' id="imgs"  /><a onclick="rotate(90)" >旋转</a></div>' }
            });
        }
       
  var rot=0;

  function rotate(r){
    rot=rot+r;
     $('#imgdiv').rotate({angle:rot});

  }

  //查看图片2 类型2 可以fa    放大缩小
   var h =window.screen.height;
        var w =window.screen.width;
        function showTopimgpage(url){
          var h1=h*0.6;
          var w1=w*0.6;
          var url1=encodeURIComponent(url);
          var showurl=InpageUrl+'admin/ShowimgLogic/index?imgurl='+url1;
          showLayerPage(showurl, '图片查看', w1, h1);
        }

//选择框
 function ConfirmTopLayer(w,h,title,msg,yesmsg,nomsg,func,arg){
       $.layer({
              shade: [1],
              area: [w,h],
              title: title,
              dialog: {
                  msg: msg,
                  btns: 2,                    
                  type: 0,
                  btn: [yesmsg,nomsg],
                  yes: function(index) {
                    if (func) {
                      func(arg);
                    }
                    layer.closeAll();
                }, no: function(){
                          layer.closeAll();
                           
                        }
                    }
          });
    }