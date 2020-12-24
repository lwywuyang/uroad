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
        .m-10{margin-top: 10px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
	</style>
    
    <script type="text/javascript" language="javascript">
        var ruleId = '<?php echo isset($ruleId)?$ruleId:''; ?>';

        function dropOut() {
            closeLayerPageJs();
        }

        //删除左右两端的空格
        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function submitNewKeyword(){
            if (trimStr(ruleId) == '') {
                ShowMsg('异常:不正确的ID');return;
            }
            var newKeyword = $('#newKeyword').val();

            JAjax("admin/WXManage/KeywordManageLogic","saveNewKeyword",{ruleId:ruleId,newKeyword:newKeyword}, function (data){
                if (data.Success)
                    //ShowMsg('操作成功!');
                    dropOut();
                else
                    ShowMsg('失败:'+data.Message);
            },'pager');
        }


</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">关键字</div>
        <div class="panel-body">
            <div class="form-inline mb10">
                <!-- <label for="newKeyword">关键字:</label> -->
                <input type="text" class="form-control" id="newKeyword" placeholder="请输入新关键字">
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="确 定" onclick="submitNewKeyword();" class="btn btn-info m-20" >
                <input type="button" value="取 消" onclick="dropOut();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>