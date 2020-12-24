<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>路段维护</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;margin-bottom: 5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .form-group{margin: 8px 0px;}
	</style>
    <script type="text/javascript" language="javascript">
        var page = 1;
        var roadoldidArr = <?php echo $roadoldidArr ?>;
        var roadoldnameArr = <?php echo $roadoldnameArr ?>;

        function changeRoadper(roadper){
            if(roadper == ''){
                roadper = 0;
            }
            var roadSel = document.getElementById('roadSel');
            roadSel.length = 1;

            for(var i=0;i<roadoldidArr[roadper].length;i++){
                roadSel.options.add(new Option(roadoldnameArr[roadper][i],roadoldidArr[roadper][i]));
            }
            Load(1);
        }
        
        function reLoad() {
            Load(page);
        }
        /**
         * [Load 加载基础数据]
         * @param {[type]} t [description]
         */
        function Load(t) {
            page=t;
            var roadperSel = $('#roadperSel').val();
            var roadId = $('#roadSel').val();
            var type = $('#typeSel').val();
            var keyword = $('#keyword').val();
            var status = $('#statusSel').val();
            JAjax("admin/baseData/RoadPOILogic", 'onLoadRoadPOI', {page:page,roadperSel:roadperSel,roadId:roadId,type:type,keyword:keyword,status:status}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc   查看站点详情/参数传0则为新增
         * @param  {[type]}    poiid [description]
         * @return {[type]}          [description]
         */
        function checkDetail(poiid) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/RoadPOILogic/checkPOIDetail?poiid='); ?>"+poiid, '查看', 880, 555, reLoad);
        }

        /**
         * @desc 新增站点
         */
        function addRoadPoi(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/RoadPOILogic/checkPOIDetail?poiid=0'); ?>", '新增', 880, 555, reLoad);
        }

        var poiname = "";
        /**
         * @desc   获取所有选取项,组成字符串
         * @param  {[type]}    name    [description]
         * @param  {[type]}    context [description]
         * @return {[string]}            [description]
         */
        function getAllCheckedValues(name, context) {
            var target = context ? context : "";
            var result = "";
            poiname = "";
            $(target + " input[name='" + name + "']:checked").each(function () {
                result += $(this).val() + ",";
                poiname += $('#poiid'+$(this).val()).data('poiname') + ",";
            });
            result = result.substring(0, result.length - 1);//去掉最后一个逗号
            poiname = poiname.substring(0, poiname.length - 1);//去掉最后一个逗号
            return result;
        }


        /**
         * @desc   删除站点
         * @data   2015-9-17 19:03:21
         */
        function deletePoi() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/RoadPOILogic", "delRoadPoi", { deleteValue: values,poiname:poiname}, function (data) {
                        if (data.data) {
                            reLoad();
                        }else{
                            ShowMsg("删除失败!");
                        }
                    }, null);
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        function checkUeditor(poiid,name){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/RoadPOILogic/checkUeditorDetail?poiid='); ?>"+poiid+'&name='+name, '图文', 900, 700);
        }

        function reportExcel(){
            var roadperSel = $('#roadperSel').val();
            var roadId = $('#roadSel').val();
            var type = $('#typeSel').val();
            var keyword = $('#keyword').val();
            var status = $('#statusSel').val();
            
            window.location.href = InpageUrl+'admin/baseData/RoadPOILogic/exportExcel?roadperSel='+roadperSel+'&roadId='+roadId+'&type='+type+'&keyword='+keyword+'&status='+status;
        }

        function changeStatus(poiid,status,name){
            JAjax("admin/baseData/RoadPOILogic", "setRoadPoiStatus", {poiid: poiid,status:status,name:name}, function (data) {
                if (data.Success) {
                    reLoad();
                }else{
                    ShowMsg(data.Message);
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
                    <label for="roadperSel">管理处:</label>
                    <select class="form-control" onchange="changeRoadper(this.value)" id="roadperSel">
                        <option value="">全部</option>
                        <?php foreach($roadper as $item): ?>
                            <option value="<?=$item['id']?>"><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="roadSel">高速公路:</label>
                    <select class="form-control" onchange="Load(1)" id="roadSel">
                        <option value="">全部</option>
                        <?php foreach($road as $item): ?>
                            <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['shortname'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="typeSel">类型:</label>
                    <select class="form-control" onchange="Load(1)" id="typeSel">
                        <option value="">全部</option>
                        <?php foreach($type as $item): ?>
                            <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statusSel">状态:</label>
                    <select class="form-control" onchange="Load(1)" id="statusSel">
                        <option value="">全部</option>
                        <?php foreach($status as $item): ?>
                            <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control" id="keyword" placeholder="请输入关键字">
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary" >
                </div>
                <div class="form-group">
                    <input type="button" value="新 增"  id="new" onclick="addRoadPoi();" class="btn btn-info" >
                </div>
                <div class="form-group">
                    <input type="button" value="删 除" id="del" onclick="deletePoi();" class="btn btn-danger" >
                </div>
                <div class="form-group">
                    <input type="button" value="导 出" id="report" onclick="reportExcel();" class="btn btn-success" >
                </div>
            </div>
        </div>
        <div class="panel-body">
            
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title"  width="30px" itemvalue="poiid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)' id='poiid{poiid}' data-poiname='{name}'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');"><!--InPage.js-->
                            </th>
                            <th class="title" width="8%" itemvalue="newcode" center="true">国标  
                            </th>
                            <th class="title" width="16%" itemvalue="shortname" center="true">所属道路
                            </th>
                            <th class="title" width="10%" itemvalue="styleName" center="true">类型
                            </th>
                            <th class="title" width="10%" itemvalue="name" center="true">名称 
                            </th>
                            <th class="title" width="8%" itemvalue="stationcode" center="true">编号
                            </th>
                            <th class="title" width="8%" itemvalue="miles" center="true">公里数 
                            </th>
                            <th class="title" width="8%" itemvalue="statusname" center="true">收费站状态 
                            </th>
                            <th class="title" width="120px" itemvalue="operate" center="true">操作 
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