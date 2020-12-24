<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <?php $this->load->view('admin/common'); ?> 
  <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
  <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
  <script type="text/javascript">
    var id = '<?php echo isset($id)?$id:'' ?>';
    var jpgurl='<?php echo isset($data[0]['jpgurl'])?$data[0]['jpgurl']:"" ?>';
   
    function save(status){
        var title=$("#title").val();
        var html=UE.getEditor('html').getContent();
        var jpgurl = $('#imgurl').attr('src');

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        if(trimStr(title)==''){
            ShowMsg('标题不能空');
        }else {
            JAjax('admin/MsgPublish/TollStandardLogic','onSave',{id:id,title:title,html:html,jpgurl:jpgurl},function (data){
                if(data.Success){
                    ShowMsg("操作成功!");
                }else{
                    ShowMsg("失败：" + data.Message);
                }
            },"pager");
        }
    }
       
    jQuery(document).ready(function(){
        UE.getEditor('html');
        //显示图片
        if(jpgurl!=''){
            $("#imgupload").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImage(this.src)'/>");
        }
    });
        

    jQuery(function () {
        var $ = jQuery,
            $list = $('#fileList'),
            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

            // 缩略图大小
            thumbnailWidth = 100 * ratio,
            thumbnailHeight = 100 * ratio,

            // Web Uploader实例
            uploader;


        // 初始化Web Uploader
        uploader = WebUploader.create({

            // 自动上传。
            auto: true,

            // swf文件路径
            swf: '/webuploader/Uploader.swf',

            // 文件接收服务端。
            server: '<?php $this->load->helper("url");echo base_url("/index.php/admin/Uploadimg/uploadser") ?>',

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',

            // 只允许选择文件，可选。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }

        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function (file,response) {
          jpgurl=response['_raw'];
         
        
            $("#imgupload").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImage(this.src)'/>");
        });

        // 文件上传失败，现实上传出错。
        uploader.on('uploadError', function (file) {
            var $li = $('#' + file.id),
                $error = $li.find('div.error');

            // 避免重复创建
            if (!$error.length) {
                $error = $('<div class="error"></div>').appendTo($li);
            }

            $error.text('上传失败');
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on('uploadComplete', function (file) {
            $('#' + file.id).find('.progress').remove();

        });

        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#imgupload' ),
                $percent = $li.find('.progress span');

            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="progress"><span></span></p>')
                        .appendTo( $li )
                        .find('span');
            }

            $percent.css( 'width', percentage * 100 + '%' );
        });

    })

    function showLayerImage(src){
        window.parent.parent.showLayerImage(src);
    }

</script>
<style type="text/css">
    .panel-heading{color: #FF634D !important;font-size: 18px;}
</style>
</head>
<body marginwidth="0" marginheight="0">     
    <div class="panel panel-default form-horizontal">
        <div class="panel-heading">收费标准详细信息</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>                   
                    <tr>
                        <td width='10%' nowrap="nowrap">标题:</td>
                        <td width='90%'>
                            <input  type="text"  id="title" NotNull="true" class="form-control" value="<?php echo isset($data[0]['title'])?$data[0]['title']:"" ?>" />
                        </td>         
                    </tr>
                    <tr height="150px">
                        <td width="10%" nowrap="nowrap">封面图片:
                            <div id="uploader-demo">
                            <!--用来存放item-->
                            <div id="fileList" class="uploader-list"></div>
                            <div id="filePicker">选择图片</div>
                            </div>
                        </td>
                        <td width="90%" algin="center">
                            <div id="imgupload" width="100%"></div>
                        </td>
                    </tr>
                    <tr>
                        <td width='10%' nowrap="nowrap">详细内容:</td>
                        <td width='90%'>
                            <textarea  style="height:400px; width:100%" id="html"><?php echo isset($data[0]['html'])?$data[0]['html']:"" ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table> 
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <input type="button" id="submit" value="保存" class="btn btn-primary" onclick="save();"/>
        </div>
    </div>
</body>
</html>