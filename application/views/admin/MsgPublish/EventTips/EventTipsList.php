
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-10{margin-right:10px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
        .strong{float: left;line-height: 41px;}
        .form-inline select{margin-right: 20px;}
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .newcode-img{max-width: 50px;}
	</style>

    <script type="text/javascript" language="javascript">
        var eventType = "<?php if(isset($eventtype)){echo $eventtype;}else{echo '1006003';}?>";
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
            var roadId = $('#roadSel').val();
            var status = $('#statusSel').val();
            var keyword = $('#keyword').val();

            JAjax("admin/MsgPublish/EventTipsLogic", 'onLoadEventTips', {page:t,eventType:eventType,roadId:roadId,status:status,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc   '易堵预报'->查看详细信息
         */
        function checkInfo(eventid){
            showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/EventTipsLogic/checkTipsMsg?eventid=') ?>"+eventid,'预报详细',550,515,reLoad);
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
        
        function deleteTipsMsg() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要撤销吗？", function () {
                    JAjax("admin/MsgPublish/EventTipsLogic", "delTipsMsg", {deleteValue:values}, function (data) {
                        if (data.data) {
                        //ShowMsg('删除成功!');
                            reLoad();
                        }else {
                            ShowMsg("撤销失败!");
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
            <div class="form-inline mb10">
                <strong class="strong">高速公路</strong>
                <select class="form-control" id="roadSel" onchange="reLoad();" style="float: left;">
                    <option value="">全部</option>
                    <?php foreach($road as $item): ?>
                        <option value="<?php echo $item['roadoldid'] ?>"><?php echo $item['roadName'] ?></option>
                    <?php endforeach; ?>
                </select>
                <strong class="strong">状态</strong>
                <select class="form-control" id="statusSel" onchange="reLoad();" style="float: left;">
                    <option value="1012004">发布中</option>
                    <option value="1012005">已结束</option>
                </select>
                <label><strong class="strong">关键字</strong>
                <input type="text" class="form-control" id="keyword" placeholder="请输入关键字"></label>
                <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary m-10" >
                <input type="button" value="新 增" id="add" onclick="checkInfo(0);" class="btn btn-success m-10" >
                <input type="button" value="撤 销" id="cancel" onclick="deleteTipsMsg();" class="btn btn-danger m-10" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title"  width="25px" itemvalue="eventid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="20%" itemvalue="reportout" center="true">发布内容
                            </th>
                            <th class="title" width="8%" itemvalue="occtime" center="true" showformat="yyyy-MM-dd hh:mm:ss">发生时间 
                            </th>
                            <th class="title" width="8%" itemvalue="updatetime" center="true" showformat="yyyy-MM-dd hh:mm:ss">更新时间
                            </th>
                            <th class="title" width="8%" itemvalue="eventStatus" center="true">发布状态
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