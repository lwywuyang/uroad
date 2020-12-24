<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .strong{float: left;line-height: 41px;}
        .form-inline select{margin-right: 20px;}
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .photo-img{max-width: 50px;}
	</style>

    <script type="text/javascript" language="javascript">
        var page = 1;
        
        function reLoad() {
            Load(page);
        }

        function subValueStr(str){
            return str.substring(0,(str.length-1));
        }

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
        
        function Load(t) {
            page=t;
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            var keyword = $('#keyword').val();
            //var typeSel = $('#typeSel').val();
            
            JAjax("admin/WXManage/FeedbackLogic", 'onLoadFeedbackMsg', {page:page,startTime:startTime,endTime:endTime,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        function checkPhoto(url){
            window.parent.parent.showLayerImage(url);
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <label for="startTime">时间:</label>
                <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="endTime">至</label>
                <input type="text" class="form-control" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="keyword">&nbsp;&nbsp;&nbsp;&nbsp;关键字:</label>
                <input type="text" class="form-control" id="keyword" placeholder="请输入关键字">
                <!-- <label for="typeSel">&nbsp;&nbsp;&nbsp;&nbsp;事件类型</label>
                <select class="form-control" id="typeSel" onchange="reLoad();">
                    <option value="">全部</option>
                    <?php foreach($eventtype as $item): ?>
                        <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                    <?php endforeach; ?>
                </select> -->
                <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary m-15" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <!-- <th class="title"  width="3%" itemvalue="eventid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th> -->
                            <th class="title" width="10%" itemvalue="wechatname" center="true">用户名
                            </th>
                            <th class="title" width="10%" itemvalue="phone" center="true">电话
                            </th>
                            <th class="title" width="30%" itemvalue="remark" center="true">反馈内容
                            </th>
                            <th class="title" width="15%" itemvalue="intime" center="true" showformat="yyyy-MM-dd hh:mm:ss">反馈时间
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>