<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>图文信息</title>
  <?php $this->load->view('admin/common'); ?> 
  <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
  <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
  <script type="text/javascript">
   var id='<?php echo $id ?>';
   var status='<?php echo isset($status)?$status:"" ?>';
   var imgurl='<?php echo isset($imgurl)?$imgurl:"" ?>';
   //var imgurlthumbnail='<?php echo isset($imgurlthumbnail)?$imgurlthumbnail:"" ?>';
   var cateid='<?php echo isset($cateid)?$cateid:"" ?>';

         function save(status){
            //取出数据
            var title=$("#title").val();
            var html=UE.getEditor('html').getContent();
            var summay=$("#summay").val();
            var url=$("#url").val();
            var sort=$("#sort").val();
            // var cateid=$("#cateid").val();
             
            if(title==''){
              ShowMsg('标题不能空');
            }else {
              JAjax('admin/WXManage/WXMenuLogic','onSavenew',{title:title,html:html,summay:summay,id:id,status:status,url:url,imgurl:imgurl,cateid:cateid,sort:sort},function (data){
                if(data.Success)
                {
                  closeLayerPageJss();  
                }
                else
                {
                  ShowMsg("失败：" + data.Message);
                }

              },"pager");
            }
          }
        

        
       
        jQuery(document).ready(function(){
          if(status=='1'){
            $("#cexiao").show();
          }
          if(status=='0'){
            $("#fabu").show();
          }
          UE.getEditor('html');
          //显示图片
          if(imgurl!=''){
              //src='<?php $this->load->helper("url");echo base_url("") ?>/'+imgurl;
              //src_small='<?php $this->load->helper("url");echo base_url("") ?>/'+imgurlthumbnail;
                $("#imgupload").html("<img src="+imgurl+"  width='200px' onclick= 'showLayerImage()'/>");
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
              imgurl=response['_raw'];
              $("#imgupload").html("<img src="+imgurl+" id='imgurl' width='200px' onclick= 'showLayerImage(this.src)'/>");
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

        function showLayerImage(src){
          window.parent.parent.showLayerImage(src);
        }
        function closeLayerPageJss(){
          window.parent.parent.closeAll();
        }

</script>

</head>
<body>     
  <div class="panel panel-default form-horizontal ">
    <div class="panel-body ">
      <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
        <tbody>                   
          <tr>
            <td width='10%' nowrap="nowrap" >标题:</td>
            <td width='90%' >
              <input  type="text"  id="title" NotNull="true" class="form-control" value="<?php echo isset($title)?$title:"" ?>" />
            </td>         
          </tr>
          <tr>
            <td width='10%' nowrap="nowrap" >简介:</td>
            <td width='90%'  >
             <textarea  style="width:100%" rows="3" id="summay" ><?php echo isset($intro)?$intro:"" ?></textarea>
           </td>
         </tr> 
         <tr>
            <td width='10%' nowrap="nowrap" >链接:</td>
            <td width='90%' >
              <input  type="text"  id="url" NotNull="true" class="form-control" value="<?php echo isset($url)?$url:"" ?>" />
           </td>
         </tr> 
        <tr>
         <td width='10%' nowrap="nowrap">排序:</td>
         <td width='90%' >
           <input type="text" id="sort" NotNull="true" class="form-control" value="<?php echo isset($sort)?$sort:"" ?>" />
         </td>
       </tr> 
       <tr>
         <td width='10%' nowrap="nowrap" >详细内容:</td>
         <td width='90%' >
           <textarea  style="height:400px; width:100%" id="html"><?php echo isset($content)?$content:"" ?></textarea>
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
          <td width="90%" algin="center" >
            <div id="imgupload"  width="100%">
              
            </div>
          </td>
        </tr>                     
    </tbody>
  </table> 

  <!-- panel-body -->
</div>
<!-- panel-body -->
<div class="panel-footer">
  <input type="button"  id="Save" value="保存"  class="btn btn-primary"  onclick="save(0);"/>
  <input type="button"  id="fabu"  value="发布"  class="btn btn-primary"  onclick="save(1);" style='display:none'/>
  <input type="button"  id="cexiao"  value="撤销 "  class="btn btn-primary"  onclick="save(0);" style='display:none'/>
  <input type="button"  id="btnCancel"  value="关闭" class="btn btn-primary" onclick="closeLayerPageJss();" />

</div>
</div>

</body>
</html>
