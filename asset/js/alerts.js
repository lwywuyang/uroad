var loadingHtml = '<div class="weui_dialog_alert" id="dialog2" style="display: none;">';
loadingHtml += '<div class="weui_mask"></div>';
loadingHtml += '<div class="weui_dialog">';
loadingHtml += '<div class="weui_dialog_hd"><strong class="weui_dialog_title">弹窗标题</strong></div>';
loadingHtml += ' <div class="weui_dialog_bd">弹窗内容，告知当前页面信息等</div>';
loadingHtml += '<div class="weui_dialog_ft">';
loadingHtml += '<a href="javascript:;" class="weui_btn_dialog primary">确定</a>';
loadingHtml += '</div></div></div>';
function alerts(text,url,title){
		$(document.body).append(loadingHtml);

        $('#dialog2').show();
        $('#dialog2 .weui_dialog_bd').html(text);
        
        if(title=="" || title==null){
            $('#dialog2 .weui_dialog_title').hide();
        }else{
            $('#dialog2 .weui_dialog_title').html(title);
        }
        if(url=="" || url==null){
            $('#dialog2 .weui_btn_dialog').attr("href",'javascript:hidecommon("dialog2");');
        }else{
            $('#dialog2 .weui_btn_dialog').attr("href",url);
        }
    }

    //隐藏的公共函数   
    function hidecommon(id){
        $('#'+id).hide();
    }
    //show公共函数
    function showcommon(id){
        $('#'+id).show();
        $('#dialog2').hide();
    }




var loadingHtml2 = '<div class="weui_dialog_alert" id="dialog" style="display: none;">';
loadingHtml2 += '<div class="weui_mask"></div>';
loadingHtml2 += '<div class="weui_dialog">';
loadingHtml2 += '<div class="weui_dialog_hd"><span class="weui_dialog_title" style="font-size: 1.3em;">弹窗标题</span></div>';
loadingHtml2 += ' <div class="weui_dialog_bd"><div class="setmoney" ><input type="text" id="inputload" onblur="getinputnum()"  placeholder="请输入圈存金额" /></div></div>';
loadingHtml2 += '<div class="weui_dialog_ft">';
loadingHtml2 += '<a href="javascript:;" class="weui_btn_dialog primary">确定</a>';
loadingHtml2 += '</div></div></div>';
function loadalerts(title,url){
        $(document.body).append(loadingHtml);

        $('#dialog').show();
        
        if(title=="" || title==null){
            $('#dialog .weui_dialog_title').hide();
        }else{
            $('#dialog .weui_dialog_title').html(title);
        }
        if(url=="" || url==null){
            $('#dialog .weui_btn_dialog').attr("href",'javascript:hidecommon("dialog");');
        }else{
            $('#dialog .weui_btn_dialog').attr("href",url);
        }
    }

