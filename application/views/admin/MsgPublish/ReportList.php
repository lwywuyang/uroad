<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
        .m-r-5{margin-right: 5px;}
		.m-r-15{margin-right: 15px;}
        .table{margin-bottom: 0;}
        .form-inline .form-group{margin-right: 0px;}
        .form-group{margin: 8px 0px;}
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .upfile{margin: 5px;max-width: 60px;max-height: 100px;}
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
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            var keyword = $('#keyword').val();
            var eventTypeSel = $('#eventTypeSel').val();
            var eventStatusSel = $('#eventStatusSel').val();

            JAjax("admin/MsgPublish/ReportLogic", 'onLoadReportMessage', {page:t,startTime:startTime,endTime:endTime,keyword:keyword,eventTypeSel:eventTypeSel,eventStatusSel:eventStatusSel}, function (data) {
                ReloadTb('dataGrid', data.data);
                //closeloading();
            }, "pager");
        }


        function getAllCheckedValues(name, context) {
            var target = context ? context : "";
            var result = "";
            $(target + " input[name='" + name + "']:checked").each(function () {
                result += $(this).val() + ",";
            });
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            return result;
        }
        
        function deleteEventMsg() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");

            if (values != "" && values != undefined) {
                ShowConfirm("您确定要结束选择的事件吗?", function () {
                    JAjax("admin/MsgPublish/ReportLogic", "delEventMsg", {deleteValue:values}, function (data) {
                        if (data.data) {
                            reLoad();
                        }else {
                            ShowMsg("提示:"+data.Message);
                        }
                    }, null);
                });
            }else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        function changStatus(eventid,status){
            if (status == '3') {
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/ReportLogic/showCaseList?eventid=') ?>"+eventid,'打回原因',550,515,reLoad);
                //alert('稍等!');
                return;
            }
            JAjax("admin/MsgPublish/ReportLogic", "setEventStatus", {eventid:eventid,status:status}, function (data) {
                if (data.Success) {
                    reLoad();
                }else {
                    ShowMsg("提示:"+data.Message);
                }
            }, null);
        }
    </script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="startTime">时间:</label>
                    <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <label for="endTime">至</label>
                    <input type="text" class="form-control m-r-15" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control m-r-15" id="keyword" placeholder="请输入关键字">
                </div>
                <div class="form-group">
                    <label for="eventTypeSel">报料类型:</label>
                    <select class="form-control m-r-15" id="eventTypeSel" onchange="Load(1)">
                        <option value="">全部</option>
                        <?php foreach($eventtype as $item): ?>
                            <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="eventStatusSel">报料状态:</label>
                    <select class="form-control m-r-15" id="eventStatusSel" onchange="Load(1);">
                        <option value="">全部</option>
                        <option value="1">发布</option>
                        <option value="2">待发布</option>
                        <option value="3">打回</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary" >
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <!-- <th class="title"  width="30px" itemvalue="eventid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th> -->
                            <th class="title" width="80px" itemvalue="nickname">报料用户
                            </th>
                            <th class="title" width="" itemvalue="remark">报料内容
                            </th>
                            <th class="title" width="" itemvalue="upfile">报料图片
                            </th>
                            <th class="title" width="95px" itemvalue="occtime" showformat="yyyy-MM-dd hh:mm:ss">报料时间
                            </th>
                            <th class="title" width="" itemvalue="occplace">报料地点
                            </th>
                            <th class="title" width="80px" itemvalue="eventTypeName">报料类型
                            </th>
                            <th class="title" width="100px" itemvalue="status">报料状态
                            </th>
                            <th class="title" width="170px" itemvalue="operate">操作 
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj=""></div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>