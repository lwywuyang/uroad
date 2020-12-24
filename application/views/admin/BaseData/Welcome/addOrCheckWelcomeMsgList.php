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
    </style>
    <script type="text/javascript" language="javascript">
        var id = "<?php echo isset($data[0]['id'])?$data[0]['id']:''; ?>";
        var jpgurl = "<?php echo isset($data[0]['url'])?$data[0]['url']:''; ?>";
        var startTime = "<?php echo isset($data[0]['startdate'])?$data[0]['startdate']:''; ?>";
        var endTime = "<?php echo isset($data[0]['enddate'])?$data[0]['enddate']:''; ?>";
        //var linkurl = "<?php echo isset($data[0]['adurl'])?$data[0]['adurl']:''; ?>";

        var base_url = '<?php $this->load->helper("url");echo base_url() ?>';

        $().ready(function(){
            $('#startTime').val(startTime);
            $('#endTime').val(endTime);
            //$('#linkurl').val(linkurl);

            if(jpgurl!=''){
                $("#imgupload").html("<img src="+jpgurl+" id='imgurl' width='200px' onclick= 'showLayerImageJs(this.src)'/>");
            }
        });
        

        function dropOut() {
            closeLayerPageJs();
        }


        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }


        function detailChange(){
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            //var imgurl = $('#imgurl').attr('src');
            var imgurl = jpgurl;
            var linkurl = $('#linkurl').val();

            if(trimStr(startTime) == ''){alert('开始时间不能为空');return;}
            if(trimStr(endTime) == ''){alert('结束时间不能为空');return;}
            if(trimStr(imgurl) == ''){alert('请上传图片');return;}

            if (id == '0') {//新增
                JAjax("admin/baseData/WelcomeLogic", "saveWelcomeMsg", {id:0,startTime:startTime,endTime:endTime,imgurl:imgurl,linkurl:linkurl}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg("ERROR:" + data.Message);
                    }
                },null);
            }else{
                JAjax("admin/baseData/WelcomeLogic", "saveWelcomeMsg", {id:id,startTime:startTime,endTime:endTime,imgurl:imgurl,linkurl:linkurl}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg("ERROR:" + data.Message);
                    }
                },null);
            }
        }

    </script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">欢迎页面</div>
        <div class="panel-body">
            <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
                <tbody>
                    <tr>
                        <td class="td-width">
                            开始时间:
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
                    </tr>
                    <tr>
                        <td class="td-width">
                            链接:
                        </td>
                        <td class="content" colspan="3">
                            <input type="text" class="form-control" id="linkurl" value="<?php echo isset($data[0]['adurl'])?$data[0]['adurl']:''; ?>" placeholder="<?php echo isset($data[0]['adurl'])?$data[0]['adurl']:''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="td-width">
                            <p>欢迎页面:
                                <div id="uploader-demo">
                                    <div id="fileList" class="uploader-list"></div>
                                    <div id="filePicker" class="button-pic" style="margin-bottom: 15px;">选择</div>
                                </div>
                            </p>
                        </td>
                        <td class="content" colspan="3">
                            <div id="imgupload"  width="100%"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="保 存"  id="new" onclick="detailChange();" class="btn btn-info m-20" >
                <input type="button" value="返 回" id="del" onclick="closeLayerPageJs();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>