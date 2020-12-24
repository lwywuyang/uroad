<?php $this->load->helper('url'); ?>
<base href="<?php echo base_url(); ?>" />
<!DOCTYPE html>
<html>  
  <head>
    <title></title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <link rel="stylesheet" type="text/css" href="assets/css/weui.css">
    <style>
        .weui-tab .weui-tab__panel{padding-top: 50px;padding-bottom: 0;}
    </style>
    <style type="text/css">
        body{text-align: left;margin: 0px;padding: 0px;border: 0;}
        .content{width: calc(100% - 30px);height:auto;padding:15px;}
        ol,li{list-style-type:none;}
    </style>

</head>
<body>
    <div class="content">
        <!-- <p style="font-weight: 400;">您可能关注以下内容，点击问题了解更多</p>
        <ol>
            <li><span>1.</span><a href ="#">点击第一个问题点击第一个问题点击第一个问题点击第一个问题点击第一个问题</a></li>
            <li><span>2.</span><a href ="#">点击第二个问题</a></li>
            <li><span>3.</span><a href ="#">点击第三个问题</a></li>
        </ol> -->
    </div>

      <!-- loading toast -->
    <div id="loadingToast" class="weui_loading_toast" style="display:none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content">数据加载中</p>
        </div>
    </div> 
    <div class="weui_dialog_confirm" id="dialog1" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">温馨提示</strong></div>
            <div class="weui_dialog_bd comfirm_content"></div>
            <div class="weui_dialog_ft">
                <a href="javascript:$('#dialog1').hide();" class="weui_btn_dialog default">取消</a>
                <a href="" id="comfirm_sure_btn" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">温馨提示</strong></div>
            <div class="weui_dialog_bd weui_dialog_content"></div>
            <div class="weui_dialog_ft"> 
                <a href="javascript:$('.weui_dialog_alert').hide();" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
<script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery-2.1.1.min.js') ?>"></script>
<script src="<?php $this->load->helper('url');echo base_url('/asset/js/base64.js') ?>"></script>
<script src="<?php $this->load->helper('url');echo base_url('/asset/js/md5.js') ?>"></script>
<script>
        var apiurl='http://hunangstapi.u-road.com/dkfapi/';
        var keyword = '<?php echo $keyword;?>';
        // var keyword = "哪些收费站是港口指定收费站";
        function showLoader() {
           $('#loadingToast').show();  
        }
        //隐藏加载器
        function hideLoader()
        {
            $('#loadingToast').hide();   
        }

        //给传参加密
        function ajaxpost(func,data){
            var func = func;
            var sign = hex_md5(func);
            b = new Base64();
            postdata='{"func":"'+func+'","sign":"'+sign+'","data":'+data+'}';
            postdata=b.encode(postdata);
            console.log(postdata);
            return postdata;

        }
        //获取所有问题的数据
        function getalldata(){
            showLoader();
            ajaxpost('robotapi/searchKeyword','{"keyword":"'+keyword+'"}');
            $.ajax({ type: "post", url: apiurl, data: postdata, success: function (data) {
                data = b.decode(data);
                data = eval("("+data+")");
                console.log(data);
                if (data.status == "1") {
                    data = data.data;
                    result = data.result;
                    var html = '<p style="font-weight: 400;">您可能关注以下内容，点击问题了解更多</p><ol>';
                    for (var i = 0; i < result.length; i++) {
                        html += '<li><span>'+(i+1)+'.</span><a href ="'+result[i].url+'">'+result[i].title+'</a></li>';
                    }
                    html += '</ol>';
                    $('.content').append(html);
                    // console.log(data);
                    // console.log(data.result[0]);
                } else {
                    $('.comfirm_content').html(data.msg);
                    $('.weui_dialog_alert').css('display','block');
                }
            }, error: function (error) {
                $('.weui_dialog_content').html( error.statusText);
                $('.weui_dialog_alert').css('display','block');
            }, complete: function () {
               hideLoader();
            }
            })
        }
        /*生成所有高速*/
        function genhighview(data){
            var html = '';
          // for (var i = 0; i < data.length; i++) {
          //     html='<div id="listitem'+i+'" roadoldid="'+data[i].roadoldid+'" style="padding: 5px 0;width: 100%;height: 50px;line-height: 50px;vertical-align: middle;">' 
          //              +'<img src="'+data[i].headpic+'" style="height: 40px;width: 40px;line-height: 40px;float: left;display: inline-block;margin-left: 10px;" />'
          //              +'<div style="height: 40px;display: inline-block;float: left;line-height:40px;color: #555;margin-left: 10px;"><p style="margin:0;font-weight:700;font-size:16px;">'+data[i].roadname+'</p>'
          //               +'</div>'
          //             +'<div style="float: right;margin-right: 10px;margin-top: 15px;display:inline-block;">';
          //     if (data[i].accidentnum!= 0) {   
          //       html+='<div style="vertical-align: middle;clear: both;height: 22px;display:inline-block;"></span>'
          //         +'<span style="display: inline-block;float: left;height: 22px;margin-right: 1px"><img src="assets/images/accident.png" style="width: 19px;height: 19px;" /></span>'   
          //         +'<span  style="float: left;height: 24px;line-height:24px;display: inline-block;color: #666;font-size: 16px;">'+data[i].accidentnum+'</span>'  
          //         +'</div>';
          //     } 
          //     if (data[i].controlnum!= 0) {   
          //       html+='<div style="vertical-align: middle;clear: both;height: 22px;display:inline-block;margin-left:15px;"></span>'
          //         +'<span style="display: inline-block;float: left;height: 22px;margin-right: 1px"><img src="assets/images/eventicon-2.png" style="width: 19px;height: 19px;" /></span>'   
          //         +'<span  style="float: left;height: 24px;line-height:24px;display: inline-block;color: #666;font-size: 16px;">'+data[i].controlnum+'</span>'  
          //         +'</div>';
          //     }                   
          //     if (data[i].plannum != 0) {        
          //         html+='<div style="vertical-align: middle;clear: both;height: 22px;display:inline-block;margin-left:15px;">'
          //             +'<span style="display: inline-block;float: left;height: 22px;margin-right: 1px"><img src="assets/images/shigong-2.png" style="width: 19px;height: 19px;" /></span>'
          //             +' <span  style="float: left;height: 24px;line-height:24px; display: inline-block;color: #666;font-size: 16px;">'+data[i].plannum+'</span>'
          //             +'</div>'; 
          //     } 
          //     html+='</div></div> </div><hr style="width:100%;margin: 0px;height:1px;border:0;border-bottom: 1px solid #ddd;">';
          //     $("#allhighcontent .allhighroad").append(html);
          //     $("#listitem" + i).unbind("click"); 
          //     $("#listitem" + i).bind("click", function () {
          //         var roadoldid = $(this).attr("roadoldid");
          //         window.location.href="http://wx.js96777.com/JiangSuWeChatAPIServer/etc/highevent?roadoldid="+roadoldid;
          //     }); 
          // }
        }


        $(document).ready(function(){
            getalldata();
           
        });
    </script>
</body>
</html>
