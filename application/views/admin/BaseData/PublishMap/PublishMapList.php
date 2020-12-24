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
            var keyword = $('#keyword').val();
            //alert(checkboxValue);
            JAjax("admin/baseData/PublishMapLogic", 'onLoadMap', {page:page,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        function addMap(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/PublishMapLogic/operateMapMsg?tag=1') ?>",'新增',530,325,reLoad);
        }

        function changeMap(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/PublishMapLogic/operateMapMsg?tag=0&id=') ?>"+id,'修改',530,325,reLoad);
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


        function deleteMap(){
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/PublishMapLogic", "delMapMsg", {deleteValue:values}, function (data) {
                        if (data.Success) {
                            //ShowMsg('删除成功!');
                            //alert('删除成功!');
                            reLoad();
                        }else {
                            ShowMsg('删除失败!');
                        }
                    }, "pager");
                });
            }else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        function reportExcel(){
            var keyword = $('#keyword').val();
            
            window.location.href = InpageUrl+'admin/baseData/PublishMapLogic/exportExcel?keyword='+keyword;
        }
</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <strong class="strong">搜索编码:</strong>
                <input type="text" class="form-control" id="keyword" placeholder="请输入关键字">
                <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-success m-15" >
                <input type="button" value="增 加" id="addMap" onclick="addMap();" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="deleteMap" onclick="deleteMap();" class="btn btn-danger m-15" >
                <input type="button" value="导 出" id="report" onclick="reportExcel();" class="btn btn-primary" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title" width="15px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="65px" itemvalue="mapid" center="true">简图ID
                            </th>
                            <th class="title" width="15%" itemvalue="pubcode" center="true">编码
                            </th>
                            <th class="title" width="8%" itemvalue="x" center="true">X
                            </th>
                            <th class="title" width="10%" itemvalue="y" center="true">Y
                            </th>
                            <!-- <th class="title" width="10%" itemvalue="" center="true" showtype="a" attr="onclick=changeMap('{id}') href='javascript:void(0)' " itemtext="修改">操作</th> -->
                            <th class="title" width="30px" itemvalue="operate" center="true">操作
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