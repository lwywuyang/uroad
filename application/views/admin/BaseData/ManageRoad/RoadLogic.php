<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>路段维护</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .form-inline .form-group{margin-right: 0}
        .item-img{max-width: 50px;max-height: 50px;margin: 2px;}
	</style>
    <script type="text/javascript" language="javascript">
        var page = 1;
        
        function reLoad() {
            Load(page);
        }

        //加载基础数据
        function Load(t) {
            page=t;
            var roadper = $('#roadper').val();
            var search = $('#searchTxt').val();
            JAjax("admin/baseData/RoadLogic", 'onLoadRoad', {page:page,roadper:roadper,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        //查看路段详情/参数传0则为新增
        function detail(id) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/roadLogic/detail?id='); ?>"+id, '查看', 650, 530, reLoad);
        }

        //获取所有选取项,组成字符串
        function getAllCheckedValues(name, context) {
            var target = context ? context : "";
            var result = "";
            $(target + " input[name='" + name + "']:checked").each(function () {
                result += $(this).val() + ",";
            });
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            return result;
        }


        //删除路段
        function deleteInfo() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");

            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/RoadLogic", "delRoad", { deleteValue: values}, function (data) {
                        if (data.data) {
                            reLoad();
                        }else{
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, null);
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }  


        //查看沿途站信息并展示
        function checkPoi(roadoldid,roadname){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/roadLogic/showPoiMsg?roadoldid=');?>"+roadoldid+'&roadname='+roadname, '查看沿途站', 800, 600, reLoad);
        }

        function updateMsg(){
            JAjax("admin/baseData/RoadLogic", "updateMsg", {}, function (data) {
                if (data.Success) {
                    ShowMsg('更新成功~');
                }else{
                    ShowMsg('失败:'+data.Message);
                }
            }, null);
        }

        function reportExcel(){
            var search = $('#searchTxt').val();
            window.location.href = InpageUrl+'admin/baseData/RoadLogic/exportExcel?search='+search;
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <input type="button" value="新 增" id="new" onclick="detail(0);" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deleteInfo();" class="btn btn-danger m-15" >
                <div class="form-group">
                    <label for="roadper">管理处:</label>
                    <select class="form-control" id="roadper" onchange="Load(1);">
                        <option value="">全部</option>
                        <?php foreach($roadper as $item): ?>
                            <option value="<?=$item['id']?>"><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchTxt">关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary m-15" >
                <strong>APP模型更新(作用于高速快览)：</strong>
                <input type="button" value="更 新" id="update" onclick="updateMsg();" class="btn btn-success m-15" >
                <input type="button" value="导 出" id="report" onclick="reportExcel();" class="btn btn-success" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="30px" itemvalue="roadoldid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="50px" itemvalue="roadoldid">路段编号 
                            </th>
                            <th class="title" width="60px" itemvalue="picurl" showtype="img">国标图片
                            </th>
                            <th class="title" width="8%" itemvalue="newcode">国标
                            </th>
                            <th class="title" width="16%" itemvalue="shortname" showtype="a" attr="onclick= detail('{roadoldid}') href='javascript:void(0)'" itemtext="{itemvalue}">名称
                            </th>
                            <th class="title" width="10%" itemvalue="direction1">上行方向
                            </th>
                            <th class="title" width="10%" itemvalue="direction2">下行方向 
                            </th>
                            <!-- <th class="title" width="10%" itemvalue="" showtype="a|a" attr="onclick= detail('{roadoldid}') href='javascript:void(0)'|onclick= checkPoi('{roadoldid}','{shortname}') href='javascript:void(0)'" itemtext="查看|查看沿途站">操作
                            </th> -->
                            <th class="title" width="110px" itemvalue="operate">操作 
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
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