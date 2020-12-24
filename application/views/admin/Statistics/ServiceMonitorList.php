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
        .form-inline select{margin-right: 20px;}
        .photo-img{max-width: 50px;}
        a {cursor: pointer;}
        .heading-ul{width: 100%;height: 50px;line-height: 50px;padding: 0;}
        .heading-li{width: 50%;height: 50px;float: left;list-style: none;margin: 0 auto;border-radius: 3px;text-align: center;}
        .li-hover:hover{background-color: #D1DEF0;}
        .li-color{background-color: #428BCA;}
        .li-color a{color: white;}
        .heading-a{font-size: 24px;width: 100% !important;height: 100% !important;display: block;}
        .heading-a span {font-size: 14px;}
        .dis-none{display: none;}
        .red-font{color: red;}
	</style>
    <script src="<?php $this->load->helper('url');echo base_url('/asset/js/echarts-2.2.7/build/dist/echarts.js') ?>"></script>
    <script type="text/javascript" language="javascript">
        var page = 1;

        function reLoad() {
            Load();
        }

        function subValueStr(str){
            return str.substring(0,(str.length-1));
        }

        function trimStr(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
        
        function Load() {
            checkServiceMonitorInStatus();
        }


        /***************按服务程序监控状态***************/
        //读取内容
        function checkServiceMonitorInStatus(){
            $('#ServiceMonitorInStatusLi').addClass('li-color');
            $('#ServiceMonitorInStatusLi').removeClass('li-hover');
            $('#ServiceMonitorInHistoryLi').removeClass('li-color');
            $('#ServiceMonitorInHistoryLi').addClass('li-hover');

            $('#ServiceMonitorInStatusDiv').removeClass('dis-none');
            $('#ServiceMonitorInHistoryDiv').addClass('dis-none');

            JAjax("admin/Statistics/ServiceMonitorLogic", 'onLoadMsgInStatus', {}, function (data) {
                ReloadTb('ServiceMonitorInStatusTable', data.data);
            }, null);
        }


        /***************按服务程序监控历史***************/
        //获取内容
        function checkServiceMonitorInHistory(t){
            $('#ServiceMonitorInStatusLi').removeClass('li-color');
            $('#ServiceMonitorInStatusLi').addClass('li-hover');
            $('#ServiceMonitorInHistoryLi').addClass('li-color');
            $('#ServiceMonitorInHistoryLi').removeClass('li-hover');

            $('#ServiceMonitorInStatusDiv').addClass('dis-none');
            $('#ServiceMonitorInHistoryDiv').removeClass('dis-none');

            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            var statusSel = $('#statusSel').val();
            JAjax("admin/Statistics/ServiceMonitorLogic", 'onLoadMsgInHistory', {page:t,startTime:startTime,endTime:endTime,statusSel:statusSel}, function (data) {
                ReloadTb('ServiceMonitorInHistoryTable', data.data);
            }, "pagerHistory");
        }


</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="ServiceMonitorInStatusLi">
                        <a onclick="checkServiceMonitorInStatus();" class="heading-a">
                            服务程序监控状态
                        </a>
                    </li>
                    <li class="heading-li" id="ServiceMonitorInHistoryLi">
                        <a onclick="checkServiceMonitorInHistory();" class="heading-a">
                            服务程序监控历史
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div id="ServiceMonitorInStatusDiv">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="ServiceMonitorInStatusTable">
                        <thead>
                            <tr>
                                <th class="title" width="25%" itemvalue="programname" center="true">服务名称
                                </th>
                                <th class="title" width="25%" itemvalue="ip" center="true">服务部署IP
                                </th>
                                <th class="title" width="25%" itemvalue="status" center="true">运行状态
                                </th>
                                <th class="title" width="25%" itemvalue="updatetime" center="true" showformat="yyyy-MM-dd hh:mm">更新时间
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- 数据 -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer"></div>
        </div>
        <div id="ServiceMonitorInHistoryDiv" class="dis-none">
            <div class="panel-body">
                <div class="form-inline mb10">
                    <label for="startTime">时间:</label>
                    <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="endTime">至</label>
                    <input type="text" class="form-control m-10" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="statusSel">状态:</label>
                    <select class="form-control m-10" id="statusSel">
                        <option value="省际">全部</option>
                        <option value="大队">正常</option>
                        <option value="支队">异常</option>
                    </select>
                    <input type="button" value="查 询" id="check" onclick="checkHistoryStatistics();" class="btn btn-primary" >
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="ServiceMonitorInHistoryTable">
                        <thead>
                            <tr>
                                <th class="title" width="25%" itemvalue="programname" center="true">服务名称
                                </th>
                                <th class="title" width="25%" itemvalue="ip" center="true">服务部署IP
                                </th>
                                <th class="title" width="25%" itemvalue="status" center="true">运行状态
                                </th>
                                <th class="title" width="25%" itemvalue="updatetime" center="true" showformat="yyyy-MM-dd hh:mm">记录时间
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
                <div id="pagerHistory" fun="checkServiceMonitorInHistory" class="pager" pagerobj="">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load();
    </script>
</body>
</html>