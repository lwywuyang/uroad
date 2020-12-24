<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-10{margin-right:10px;}
        .table{margin-bottom: 0;}
        .newcode-img{max-width: 50px;}
        .disnone-button{display: none;}
        .form-group{margin: 8px 0px; margin-right: 15px;}
	</style>
    <script type="text/javascript" language="javascript">
        var hasRescue = '<?php echo isset($hasRescue)?$hasRescue:'0' ?>';
        var eventType = "<?php if(isset($eventtype)){echo $eventtype;}else{echo '1006001';}?>";
        var page = 1;

        var roadoldidArr = <?php echo $roadoldidArr ?>;
        var roadoldnameArr = <?php echo $roadoldnameArr ?>;


        var eventid = "<?php if(isset($ids)){echo $ids;}else{echo '';}?>";//高德ID
        var gaodecztype = "<?php if(isset($gaodecztype)){echo $gaodecztype;}else{echo '';}?>";//高德ID

        /*$().ready(function(){
            if (hasRescue == '0') {
                $('#add').addClass('disnone-button');
                $('#cancel').addClass('disnone-button');
            }
        });*/

        //下拉框联动
        function changeRoadper(roadper){
            if(roadper == ''){
                roadper = 0;
            }
            var roadSel = document.getElementById('roadSel');
            roadSel.length = 1;

            for(var i=0;i<roadoldidArr[roadper].length;i++){
                roadSel.options.add(new Option(roadoldnameArr[roadper][i],roadoldidArr[roadper][i]));
            }
        }

        $().ready(function(){
            if(gaodecztype=='1'){
                newEvent();
            }
            if(gaodecztype=='2'){
                checkInfo(eventid);
            }
         
            /*if (hasRescue == '0') {
                //$('#add').addClass('disnone-button');
                $('#cancel').addClass('disnone-button');
            }*/
        });
        
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
            var roadId = $('#roadSel').val();
            var status = $('#statusSel').val();
            var keyword = $('#keyword').val();
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();

            JAjax("admin/MsgPublish/RoadEventLogic", 'onLoadRoadEvent', {page:t,roadId:roadId,eventType:eventType,status:status,keyword:keyword,startTime:startTime,endTime:endTime}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc   '事件信息'->查看详细信息
         */
        function checkInfo(eventid){
            //判断eventtype,如果是管制事件,则展示另一个页面
            if (eventType == '1006005' || eventType == '1006007')//管制事件,eventtype=1006005
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/operateControlEventMsg?eventid=') ?>"+eventid+'&eventtype='+eventType,'查看',800,600,reLoad);
            else
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/showDetailMsg?eventid=') ?>"+eventid+'&eventtype='+eventType,'查看',1200,700,reLoad);
        }

        function newEvent(){
            if (eventType == '1006005' || eventType == '1006007')//管制事件
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/operateControlEventMsg?eventid=0') ?>"+'&eventtype='+eventType,'新增',800,600,reLoad);
            else
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/newEventMsg?eventtype=') ?>"+eventType+"&eventid="+eventid,'新增',1200,650,reLoad);
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
                ShowConfirm("您确定要结束吗？", function () {
                    JAjax("admin/MsgPublish/RoadEventLogic", "delEventMsg", {deleteValue:values}, function (data) {
                        if (data.data){
                            reLoad();
                        }else{
                            ShowMsg("操作失败!");
                        }
                    }, null);
                });
            }else{
                ShowMsg("请至少选择一条记录！");
            }
        }
</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline">
                <div class="form-group">
                    <label for="startTime">时间:</label>
                    <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                    <label for="endTime">至</label>
                    <input type="text" class="form-control m-10" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
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
                    <select class="form-control" id="roadSel" onchange="reLoad();">
                        <option value="">全部</option>
                        <?php foreach($road as $item): ?>
                            <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statusSel">状态:</label>
                    <select class="form-control" id="statusSel" onchange="reLoad();">
                        <option value="1012002">待审核</option>
                        <option value="1012003">退回</option>
                        <option value="1012004">发布中</option><!--包括1012004和1012005-->
                        <option value="1012006">已结束</option><!--指结束已审核的事件-->
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
                    <input type="button" value="新 增" id="add" onclick="newEvent();" class="btn btn-success" >
                </div>
                <!-- <input type="button" value="结 束" id="cancel" onclick="deleteEventMsg();" class="btn btn-danger" > -->
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
                            <th class="title" width="65px" itemvalue="newcode" center="true">国标
                            </th>
                            <th class="title" width="100px" itemvalue="shortname" center="true">高速公路
                            </th>
                            <th class="title" width="80px" itemvalue="eventTypeName" center="true">事件类型
                            </th>
                            <th class="title" width="50%" itemvalue="reportout" center="true">发布内容
                            </th>
                            <th class="title" width="80px" itemvalue="eventstatusName" center="true">发布状态
                            </th>
                            <th class="title" width="10%" itemvalue="occtime" center="true">发生时间
                            </th>
                            <th class="title" width="10%" itemvalue="checktime" center="true">审核发布时间
                            </th>
                            <th class="title" width="10%" itemvalue="updatetime" center="true">更新时间
                            </th>
                            <th class="title" width="10%" itemvalue="canceltime" center="true">结束时间
                            </th>
                            <th class="title" width="10%" itemvalue="readtime" center="true">审核结束时间
                            </th>
                            <!-- <th class="title" width="8%" itemvalue="operatorname" center="true" showformat="yyyy-MM-dd hh:mm:ss">操作人员
                            </th> -->
                            <th class="title" width="80px" itemvalue="operate" center="true">操作 
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