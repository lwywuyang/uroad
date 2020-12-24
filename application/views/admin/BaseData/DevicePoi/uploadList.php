<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
    <style>
        .m-0,.form-inline .m-0{margin: 0;}
        .m-5{margin-right:5px;}
        .m-20{margin-right:20px;}
        .m-t-10{margin-top:10px;}
        .p-b-15{padding-bottom: 15px;}
        .vc_table{margin: 0;padding:0;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .vc_table .content{width: 80%!important;}
        .progress{background-color: #FFFF4E;}
    </style>
    
    <script type="text/javascript" language="javascript">
        var deviceid = "<?php echo isset($deviceid)?$deviceid:''; ?>";
        var jpgurl = "<?php echo isset($data['picturefile'])?$data['picturefile']:''; ?>";

        $().ready(function(){
            if(jpgurl!=''){
                $("#imgupload1").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImageJs(this.src)'/>");
            }
        });


        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        function savePic(){
            //var imgurl = $('#imgurl').attr('src');
            var picture = jpgurl;

            if(trimStr(picture) == ''){alert('请上传图片');return;}

            JAjax("admin/baseData/DevicePoiLogic", "savePictureMsg", {deviceid:deviceid,picture:picture}, function (data){
                if (data.Success) {
                    closeLayerPageJs();
                }else{
                    ShowMsg("提示:" + data.Message);
                }
            },null);
        }


        jQuery(function (){
            var $ = jQuery,
            $list = $('#fileList1'),
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
                server: '<?php echo base_url("/index.php/admin/Uploadimg/uploadser1") ?>',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#filePicker1',

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
                $("#imgupload1").html("<img src="+jpgurl+"  width='200px' onclick= 'showLayerImage(this.src)'/>");
               
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
                var $li = $( '#imgupload1' ),
                    $percent = $li.find('.progress span');

                // 避免重复创建
                if ( !$percent.length ) {
                    $percent = $('<p class="progress"><span>uploading</span></p>')
                            .appendTo( $li )
                            .find('span');
                }

                $percent.css( 'width', percentage * 100 + '%' );
            });

        })


</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading"><?php echo $data['name'] ?>监控快拍图片</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <!-- <tr>
                        <td class="td-width">
                            图片名称:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                        </td>
                        <td class="td-width">
                            结束时间:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                        </td>
                    </tr> -->
                    <tr>
                        <td class="td-width">
                            <p>图片:
                                <div id="uploader-demo">
                                    <div id="fileList1" class="uploader-list"></div>
                                    <div id="filePicker1" class="button-pic" style="margin-bottom: 15px;">选择</div>
                                </div>
                            </p>
                        </td>
                        <td class="content" colspan="3">
                            <div id="imgupload1"  width="100%"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="确 定" id="save" onclick="savePic();" class="btn btn-info m-20" >
                <input type="button" value="返 回" id="del" onclick="closeLayerPageJs();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>