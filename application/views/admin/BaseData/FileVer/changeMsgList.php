<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>  
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
    <script type="text/javascript">
        var isMap = '<?php echo isset($isMap)?$isMap:''; ?>';
        var jpgurl = '<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>';
        var isforce = '<?php if(isset($data[0]['isforce'])){echo $data[0]['isforce'];}?>';

        $().ready(function(){
            if (isMap == '1') {
                $('#remarkTr').addClass('hidden');
                $('#uploadImage').removeClass('hidden');
            }

            if(jpgurl!=''){
                $("#imgupload").html("<img src="+jpgurl+"  width='200px' onclick= 'showLayerImage(this.src)'/>");
            }
            $('#isforce').find('option[value='+isforce+']').attr('selected',true);
        });

        /**
         * @desc   保存站点信息
         * @return {[type]}    [description]
         */
        function submit(){
            var fileid = '<?php echo $data[0]["fileid"] ?>';
            //var verno = '<?php echo $data[0]["verno"] ?>';
            var verno = $('#verno').val();
            var isforce = $('#isforce').val();
            //console.log(isMap);//return;
            if (isMap == '1') {
                var remark = jpgurl;
            }else{
                var remark = $('#remark').val();
            }
            //console.log(remark);return;
            if (fileid == '') {alert('接收ID参数出错!');return;}
            if (verno == '') {alert('接收版本参数出错!');return;}

            JAjax("admin/baseData/FileVerLogic", 'changeFileVer', {fileid:fileid,verno:verno,remark:remark,isforce:isforce}, function (data) {
                if (data.data == true) {
                    //alert('操作成功');
                    closeLayerPageJs();
                }else{
                    ShowMsg("操作失败:" + data.data);
                }
            }, "pager");
        }
        
        function closeqw(){
            closeLayerPageJs();
        }


    </script>
    <style type="text/css">
        .panel-body{padding: 0;}
        .content{color:#0000FF;width: 75% !important;}
        table{border-collapse: collapse;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        .m-10{margin-right: 10px;}
    </style>
</head>
<body>
<div class="panel panel-default form-horizontal ">
    <div class="panel-heading">数据版本信息</div>
    <div class="panel-body" style="padding:10px;">
        <table cellspacing="1" cellpadding="4" class="vc_table" style="width:99%">
            <tbody>                   
                <tr>
                    <td class="name" nowrap="nowrap" >
                        当前版本号:
                    </td>
                    <td class="content">
                        <!-- <?php if(isset($data[0]['verno'])){echo $data[0]['verno'];}?> -->
                        <input type="text" class="form-control" id="verno" placeholder="<?php if(isset($data[0]['verno'])){echo $data[0]['verno'];}?>" value="<?php if(isset($data[0]['verno'])){echo $data[0]['verno'];}?>" />
                    </td>
                </tr>
                <tr id="remarkTr">
                    <td class="name" nowrap="nowrap" >
                        更新备注:
                    </td>
                    <td class="content">
                        <input type="text" class="form-control" id="remark" placeholder="<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>" value="<?php if(isset($data[0]['remark'])){echo $data[0]['remark'];}?>" />
                    </td>
                </tr>
                <tr id="isforceTr">
                    <td class="name" nowrap="nowrap" >
                        是否强制更新:
                    </td>
                    <td class="content">
                        <select class="form-control" id="isforce">
                                <option value="0">否</option>
                                <option value="1">是</option>
                        </select>
                    </td>
                </tr>
                <tr id="uploadImage" class="hidden">
                    <td class="name" nowrap="nowrap" >
                        封面图片:
                        <div id="uploader-demo">
                            <div id="fileList" class="uploader-list"></div>
                            <div id="filePicker">选择</div>
                        </div>
                    </td>
                    <td class="content">
                        <div id="imgupload"  width="100%"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <input type="button" value="更新" class="btn btn-info m-10" onclick="submit();" />
        <input type="button" value="返回" class="btn btn-danger" onclick="closeqw();" />
    </div>
 </div>
</body>
</html>