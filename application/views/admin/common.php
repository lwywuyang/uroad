<script type="text/javascript">
    var InpageUrl="<?php $this->load->helper('url');echo base_url('') ?>";
    InpageUrl=InpageUrl+"index.php/";
</script>

    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery-1.10.2.min.js') ?>">
    </script>
    <!-- bootstrap -->
    <link href="<?php $this->load->helper('url');echo base_url('/asset/plugs/bootstrap-3.3.5-dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <script src="<?php $this->load->helper('url');echo base_url('/asset/plugs/bootstrap-3.3.5-dist/js/bootstrap.min.js') ?>">
    </script>


    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.ztree.all-3.5.js') ?>">
    </script>
    <link href="<?php $this->load->helper('url');echo base_url('/asset/css/style.default.css?333333') ?>" rel="stylesheet">
    
    <link href="<?php $this->load->helper('url');echo base_url('/asset/css/jquery.datatables.css') ?>" rel="stylesheet">
    <link href="<?php $this->load->helper('url');echo base_url('/asset/css/zTreeStyle.css') ?>" rel="stylesheet">
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/WebSocketHelper.js?55') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery-migrate-1.2.1.min.js') ?>"></script>
    
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/modernizr.min.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.sparkline.min.js') ?>">
    </script>

    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/toggles.min.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/retina.min.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.cookies.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.json.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/morris.min.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/raphael-2.1.0.min.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.datatables.min.js') ?>">
    </script>
    
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/chosen.jquery.min.js') ?>">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/jquery.timers-1.2.js') ?>" type="text/javascript">
    </script>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/layer/layer.min.js') ?>">
    </script>
       <script src="<?php $this->load->helper('url');echo base_url('/asset/layer/extend/layer.ext.js') ?>">
    </script>
    <link type="text/css" rel="stylesheet" href="<?php $this->load->helper('url');echo base_url('/asset/layer/skin/layer.css') ?>" id="skinlayercss">
    
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/InPage20160623.js?as121') ?>">
    </script>

    <script type="text/javascript" language="javascript" src="<?php $this->load->helper('url');echo base_url('/asset/js/datepicker/WdatePicker.js?2222') ?>">
    </script>

    <link href="<?php $this->load->helper('url');echo base_url('/asset/js/datepicker/skin/WdatePicker.css') ?>" rel="stylesheet"
    type="text/css">

    <link href="<?php $this->load->helper('url');echo base_url('/asset/css/custom.css?22') ?>" rel="stylesheet">

    <script type="text/javascript" language="javascript" src="<?php $this->load->helper('url');echo base_url('/asset/js/ajaxfileupload.js') ?>">
    </script>

    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/ueditor/ueditor.config.js') ?>"></script>
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/ueditor/ueditor.all.min.js') ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>

    <script type="text/javascript">
    //上传图片
        jQuery(function (){
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
                $("#imgupload").html("<img src="+jpgurl+" width='200px' onclick='showLayerImage(this.src)'/>");
               
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
              // alert(1);
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
    </script>