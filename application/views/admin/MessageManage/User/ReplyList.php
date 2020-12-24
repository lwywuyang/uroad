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
        var eventid = '<?php echo isset($eventid)?$eventid:'0'; ?>';
        var id = '<?php echo isset($eventid)?$id:'0'; ?>';
        var touserid = '<?php echo isset($touserid)?$touserid:'0'; ?>';


        //删除左右两端的空格
        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function save(){
            var reply = $('#reply').val();
            var userid = $('#vestSel').val();

            JAjax("admin/WXManage/ReportMessageLogic","saveNewReply",{eventid:eventid,id:id,userid:userid,touserid:touserid,reply:reply}, function (data){
                if (data.Success)
                    closeLayerPageJs();
                else
                    ShowMsg('失败:'+data.Message);
            },null);
        }


</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">评论</div>
        <div class="panel-body">
            <div class="form-inline mb10">
                <label for="vestSel" style="line-height: 40px;float: left;">马甲:</label>
                <select class="form-control" id="vestSel" style="width: 180px;float: left;">
                    <?php foreach($vest as $item): ?>
                        <option value="<?php echo $item['userid'] ?>"><?php echo $item['username'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-inline mb10">
                <!-- <label for="newKeyword">关键字:</label> -->
                <textarea class="form-control" style="width: 100%;height: 100px;" id="reply"></textarea>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-inline mb10">
                <input type="button" value="确 定" onclick="save();" class="btn btn-info m-20" >
                <input type="button" value="取 消" onclick="closeLayerPageJs();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>