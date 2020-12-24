<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
        .m-10{margin-right:10px;}
        .table{margin-bottom: 0;}
        .newcode-img{max-width: 50px;}
        .disnone-button{display: none;}
        .repeat{color: red;font-weight: bold;}
        .form-goup{margin: 8px 0px;}
        .form-inline .form-group{margin-right: 0;}
	</style>
    <script type="text/javascript" language="javascript">
        //var eventType = "<?php if(isset($eventtype)){echo $eventtype;}else{echo '1006001';}?>";
        var page = 1;

        $().ready(function(){
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
            var keyword = $('#keyword').val();

            JAjax("admin/MsgPublish/EventCheckLogic", 'onLoadEventCheck', {page:t,roadId:roadId,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
                page = t;
            }, "pager");
        }


        /**
         * @desc   '事件信息'->查看详细信息
         */
        function checkInfo(eventid,eventtype){
            if (eventtype == '1006005' || eventtype=='1006007')
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/EventCheckLogic/showDetailMsg?eventid=') ?>"+eventid+'&eventtype='+eventtype,'查看',700,535,reLoad);
            else
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/EventCheckLogic/showDetailMsg?eventid=') ?>"+eventid+'&eventtype=001002','查看',1100,595,reLoad);
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
                    JAjax("admin/MsgPublish/EventCheckLogic", "delEventMsg", {deleteValue:values}, function (data) {
                        if (data.data) {
                            reLoad();
                        }else {
                            ShowMsg("操作失败!");
                        }
                    }, "pager");
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
            <div class="form-inline">
                <div class="form-group">
                    <label for="roadSel">高速公路:</label>
                    <select class="form-control m-10" id="roadSel" onchange="reLoad();">
                        <option value="">全部</option>
                        <?php foreach($road as $item): ?>
                            <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control m-10" id="keyword" placeholder="请输入关键字">
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary m-10" >
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
                            <th class="title" width="65px" itemvalue="newcode" center="true">国标
                            </th>
                            <th class="title" width="10%" itemvalue="shortname" center="true">高速公路
                            </th>
                            <th class="title" width="8%" itemvalue="eventTypeName" center="true">事件类型
                            </th>
                            <th class="title" width="20%" itemvalue="reportout" center="true">发布内容
                            </th>
                            <th class="title" width="8%" itemvalue="eventstatusName" center="true">发布状态
                            </th>
                            <th class="title" width="8%" itemvalue="occtime" center="true" showformat="yyyy-MM-dd hh:mm:ss">发生时间 
                            </th>
                            <th class="title" width="8%" itemvalue="updatetime" center="true" showformat="yyyy-MM-dd hh:mm:ss">更新时间
                            </th>
                            <th class="title" width="8%" itemvalue="duration" center="true">持续时间
                            </th>
                            <th class="title" width="8%" itemvalue="operatorname" center="true">操作人员
                            </th>
                            <th class="title" width="10%" itemvalue="operate" center="true">操作 
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