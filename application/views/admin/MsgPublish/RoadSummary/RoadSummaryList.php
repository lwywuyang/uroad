
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>路况综述</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .table{margin-bottom: 0;}
        .roadsummary-image{max-width: 120px; max-height: 120px;}
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
            page = t;
            var status = $('#statusSel').val();
            var keyword = $('#keyword').val();

            JAjax("admin/MsgPublish/RoadSummaryLogic", 'onLoadRoadSummary', {page:page,status:status,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc   '路况综述'->查看详细信息
         */
        function checkDetail(eventid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadSummaryLogic/operateRoadSummaryDetail?eventid=') ?>"+eventid,'详细',650,500,reLoad);
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
        
        function deleteRoadSummary() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            
            if (values != "" && values != undefined) {
                ShowConfirm("执行删除会无法恢复,您确定要删除吗?", function () {
                    JAjax("admin/MsgPublish/RoadSummaryLogic", "delRoadSummary", {deleteValue:values}, function (data) {
                        if (data.data) {
                            reLoad();
                        }else{
                            ShowMsg("删除失败!");
                        }
                    }, null);
                });
            }else {
                ShowMsg("请至少选择一条记录！");
            }
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="statusSel">状态:</label>
                    <select class="form-control" id="statusSel" onchange="reLoad();">
                        <option value="">全部</option>
                        <option value="1012004">发布中</option>
                        <option value="1012005">已结束</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control" id="keyword" placeholder="请输入关键字">
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary" >
                </div>
                <div class="form-group">
                    <input type="button" value="新 增" id="add" onclick="checkDetail(0);" class="btn btn-success" >
                </div>
                <div class="form-group">
                    <input type="button" value="删 除" id="cancel" onclick="deleteRoadSummary();" class="btn btn-danger" >
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="30px" itemvalue="eventid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="170px" itemvalue="image">综述图片
                            </th>
                            <th class="title" width="" itemvalue="reportinfo">综述内容
                            </th>
                            <th class="title" width="80px" itemvalue="statusName">状态
                            </th>
                            <th class="title" width="95px" itemvalue="intime" showformat="yyyy-MM-dd hh:mm:ss">发布时间 
                            </th>
                            <th class="title" width="95px" itemvalue="updatetime" showformat="yyyy-MM-dd hh:mm:ss">最近更新时间
                            </th>
                            <th class="title" width="100px" itemvalue="operator">操作人员
                            </th>
                            <th class="title" width="100px" itemvalue="operate">操作 
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