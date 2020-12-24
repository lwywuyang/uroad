<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-10{margin-right:10px;}
        .table{margin-bottom: 0;}
        .strong{float: left;line-height: 41px;}
        .form-inline select{margin-right: 20px;}
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .upfile{margin: 5px;max-width: 60px;max-height: 100px;}
	</style>
    <script type="text/javascript" language="javascript">
        var eventid = '<?php echo isset($eventid)?$eventid:'' ?>';
        var eventtype = '<?php echo isset($eventtype)?$eventtype:'' ?>';


        function submit(){
            var realovertime = $('#realovertime').val();

            if (eventtype == '') {
                ShowMsg('事件类型为空!');
                return;
            }else if (eventtype == '1006005') {
                JAjax("admin/MsgPublish/RoadEventLogic","finishControlEventMsg",{eventId:eventid,realovertime:realovertime}, function (data){
                    if (data.Success) {
                        closeLayerPageJs();
                    }else{
                        ShowMsg(data.Message);
                    }
                },null);

            }else{
                JAjax("admin/MsgPublish/RoadEventLogic", 'playOffTheInfo', {eventid:eventid,realovertime:realovertime}, function (data) {
                    if (data.Success)
                        closeLayerPageJs();
                    else
                        ShowMsg(data.Message);
                }, null);
            }
            
        }


</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            请选择结束时间
        </div>
        <div class="panel-body">
            <div class="form-inline mb10">
                <input type="text" class="form-control" id="realovertime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" />
            </div>
            <input type="button" value="确 定" id="submit" onclick="submit();" class="btn btn-primary" >
        </div>
    </div>
</body>
</html>