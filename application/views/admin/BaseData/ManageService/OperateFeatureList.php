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
        .form-inline .col-xs-10{width:83.33333333%;}
        .vc_table{margin: 0;padding:0;}
        .td-width{text-align: right;}
        .vc_table tr td:first{width: 80px;}
        .vc_table .content{max-width: 180px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .vc_table .content{width: auto !important;}
	</style>
    
    <script type="text/javascript" language="javascript">
        var poiid = '<?php echo isset($poiid)?$poiid:''; ?>';
        var eventid = '<?php echo isset($eventid)?$eventid:''; ?>';
        var jpgurl = "<?php echo isset($data['jpgurl'])?$data['jpgurl']:''; ?>";
        var base_url = '<?php $this->load->helper("url");echo base_url(); ?>';

        function dropOut() {
            closeLayerPageJs();
        }


        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function saveFeature(){
            //var imgurl = $('#imgurl').attr('src');
            var imgurl = jpgurl;
            var title = $('#title').val();
            var seq = $('#seq').val();
            var html=UE.getEditor('html').getContent();
            //alert(html);
            if(trimStr(imgurl) == ''){alert('请上传图片!');return;}
            if(trimStr(title) == ''){alert('标题不能为空!');return;}
            if(trimStr(seq) == ''){alert('序号不能为空');return;}

            if(eventid == 0){//新增
                JAjax("admin/baseData/ServiceLogic", "saveFeatureMsg", {eventid:0,poiid:poiid,imgurl:imgurl,title:title,seq:seq,html:html}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg("操作失败:" + data.Message);
                    }
                },null);
            }else{//保存数据
                JAjax("admin/baseData/ServiceLogic", "saveFeatureMsg", {eventid:eventid,imgurl:imgurl,title:title,seq:seq,html:html}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg("操作失败:" + data.Message);
                    }
                },null);
            }
        }


        /**
         * @desc   点击图片展示原尺寸大图
         */
        jQuery(document).ready(function(){
            //显示图片
            if(jpgurl!=''){
                $("#imgupload").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImage(this.src)'/>");
            }

            //html
            UE.getEditor('html');
        });

</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">服务区特色详细信息</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            <p>封面图片:
                                <div id="uploader-demo">
                                    <!--用来存放item-->
                                    <div id="fileList" class="uploader-list"></div>
                                    <div id="filePicker" class="button-pic" style="margin-bottom: 15px;">选择</div>
                                </div>
                            </p>
                        </td>
                        <td class="content" colspan="3">
                            <div id="imgupload"  width="100%"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            标题:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="title" placeholder="" value="<?php echo isset($data['title'])?$data['title']:''; ?>">
                        </td>
                        <td class="td-width">
                            序号:
                        </td>
                        <td class="content">
                            <input type="text" class="form-control col-xs-10" id="seq" placeholder="" value="<?php echo isset($data['seq'])?$data['seq']:''; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="content" colspan="4"><!--横向最多5个td-->
                            <textarea style="height:300px;width:100%" id="html"><?php echo isset($data['html'])?$data['html']:''; ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="确 定"  id="addFeature" onclick="saveFeature();" class="btn btn-info m-20" >
                <input type="button" value="取 消" id="cancel" onclick="dropOut();" class="btn btn-danger" >
            </div>
        </div>
    </div>
</body>
</html>