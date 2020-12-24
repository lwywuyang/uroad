<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>设备维护</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-r-5{margin-right:5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .picture{max-height: 150px; max-width: 150px;}
	</style>

    <script type="text/javascript" language="javascript">
        var page = 1;

        function reLoad(){
            Load(page);
        }
        
        /**
         * @desc  拉取'设备维护'页面表格数据
         * @param {[string]}    t [页码,默认第一页]
         */
        function Load(t) {
            page = t;
            var road = $('#roadSel').val();
            var type = $('#typeSel').val();
            var search = $('#searchTxt').val();

            JAjax("admin/baseData/DeviceLogic", 'onLoadDevice', {page:page,road:road,type:type,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        /**
         * @desc 打开'新增设备'页面
         */
        function addDevice(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/DeviceLogic/operateDevice'); ?>?tag=0",'新增',630,570,reLoad);
        }


        /**
         * @desc   打开'设备信息'页面
         * @param  {[string]}    deviceid [设备id]
         */
        function checkDevice(deviceid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/DeviceLogic/operateDevice'); ?>?tag=1&deviceid="+deviceid,'查看',630,570,reLoad);
        }


        /**
         * @desc   获取所有选取项,组成字符串
         * @param  {[type]}    name    [description]
         * @param  {[type]}    context [description]
         * @return {[string]}            [description]
         */
        function getAllCheckedValues(name, context) {
            var target = context ? context : "";
            var result = "";
            $(target + " input[name='" + name + "']:checked").each(function () {
                result += $(this).val() + ",";
            });
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            return result;
        }

        function deleteDevice(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");

            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/DeviceLogic", "delDevice", { deleteValue: values}, function (data) {

                        if (data.data) {
                            reLoad();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, null);
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }


        /**
         * @desc   查看图片
         * @param  {[type]}    url [description]
         */
        function checkPic(url){
            //alert(url);
            showLayerImageJs(url);
        }

        function changeStatus(deviceid,newstatus){
            JAjax("admin/baseData/DeviceLogic", "setNewStatus", {deviceid:deviceid,newstatus:newstatus}, function (data) {

                if (data.Success)
                    reLoad();
                else
                    ShowMsg("Failure:" + data.Message);
            }, null);
        }
</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="roadSel">高速公路:</label>
                    <select class="form-control" id="roadSel" onchange="Load(1)">
                        <option value="">全部</option>
                        <?php foreach($roadold as $item):?>
                            <option value="<?=$item['roadoldid']?>"><?=$item['shortname']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="typeSel">类型:</label>
                    <select class="form-control" id="typeSel" onchange="Load(1)">
                        <option value="">全部</option>
                        <?php foreach($type as $item):?>
                            <option value="<?=$item['dictcode']?>"><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchTxt">关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <input type="button" value="查 询" id="new" onclick="Load(1);" class="btn btn-primary m-15" >
                <input type="button" value="新 增" id="new" onclick="addDevice();" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deleteDevice();" class="btn btn-danger m-20">
            </div>
        </div>
        <div class="panel-body">
            
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="30px" itemvalue="deviceid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');"><!--InPage.js-->
                            </th>
                            <th class="title" width="" itemvalue="shortname">所属路段
                            </th>
                            <th class="title" width="" itemvalue="deviceType">类型  
                            </th>
                            <th class="title" width="" itemvalue="name">名称
                            </th>
                            <th class="title" width="" itemvalue="miles">公里数
                            </th>
                            <th class="title" width="" itemvalue="statusname">是否进行快拍截图
                            </th>
                            <th class="title" width="170px" itemvalue="picture">快拍
                            </th>
                            <th class="title" width="130px" itemvalue="operate">操作
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