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
        .checkbox-d{width: 150px;float: left;margin-right: 5px;}
        .checkbox-d-s{float: left;margin-top: 10px;margin-right: 5px;}
        .top-img{margin: 5px 8px;}
        .newcode-img{max-width: 50px;}
        .m-5{margin: 5px;}
	</style>

    <script type="text/javascript" language="javascript">
        var eventType = "<?php if(isset($eventtype)){echo $eventtype;}else{echo '1006001';}?>";
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
            var roadId = $('#roadSel').val();
            var status = $('#statusSel').val();
            var keyword = $('#keyword').val();

            JAjax("admin/ETCManage/CheckPendingLogic", 'onLoadCheckPendingEvent', {page:page,eventType:eventType,roadId:roadId,status:status,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc   '事件信息'->查看详细信息
         * @data   2015-10-21 11:01:26
         */
        function checkInfo(cardno,carno){
            //判断eventtype,如果是管制事件,则展示另一个页面
            /*if (eventType == '1006005')//管制事件,eventtype=1006005
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/operateControlEventMsg?eventid=') ?>"+eventid,'查看',700,500,reLoad);
            else*/

                showLayerPageJs("<?php echo base_url('/index.php/admin/ETCManage/CheckPendingLogic/showDetailMsg?cardno=') ?>"+cardno+"&carno="+carno,'新增',1100,595,reLoad);
        }

        /**
         * 调用存储过程
         * */
        function newEvent(){
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");

            /*if (eventType == '1006005')//管制事件
                showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/operateControlEventMsg?eventid=0') ?>",'新增',700,500,reLoad);
            else*/
//                showLayerPageJs("<?php //echo base_url('/index.php/admin/MsgPublish/RoadEventLogic/showDetailMsg?cardno=') ?>//"+eventType,'新增',1100,595,reLoad);
            JAjax("admin/ETCManage/CheckPendingLogic", "updateCheckPending", {values:values}, function (data) {
                reLoad();
//                if (data.data) {
//                    //ShowMsg('删除成功!');
//                    reLoad();
//                }else {
//                    ShowMsg("error:"+data.Message);
//                }
            });
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
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要结束选择的事件吗？", function () {
                    JAjax("admin/MsgPublish/RoadEventLogic", "delEventMsg", {deleteValue:values}, function (data) {
                        if (data.data) {
                        //ShowMsg('删除成功!');
                            reLoad();
                        }else {
                            ShowMsg("error:"+data.Message);
                        }
                    }, "pager");
                });
            }else {
                ShowMsg("请至少选择一条记录！");
            }
        }

        function delCheckPending(id){
            ShowConfirm("您确定要删除吗？", function () {
                JAjax("admin/ETCManage/CheckPendingLogic", "delCheckPending", {id: id}, function (data) {
                    if (data.Success) {
                        ShowMsg('删除成功!');
                        reLoad();
                    } else {
                        ShowMsg("error:" + data.Message);
                    }
                }, null);
            })
        }
</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
<!--                <strong class="strong">高速公路</strong>-->
<!--                <select class="form-control" id="roadSel" onchange="reLoad();" style="float: left;">-->
<!--                    <option value="">全部</option>-->
<!--                    --><?php //foreach($road as $item): ?>
<!--                        <option value="--><?php //echo $item['roadoldid'] ?><!--">--><?php //echo $item['roadName'] ?><!--</option>-->
<!--                    --><?php //endforeach; ?>
<!--                </select>-->
<!--                <strong class="strong">事件状态:</strong>-->
<!--                <select class="form-control" id="statusSel" onchange="reLoad();" style="float: left;">-->
<!--                    <option value="">全部</option>-->
<!--                    <option value="1012004">发布中</option>-->
<!--                    <option value="1012005">已结束</option>-->
<!--                </select>-->
                <label><strong class="strong">关键字</strong>
                <input type="text" class="form-control" id="keyword" placeholder="请输入关键字"></label>
                <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary m-15" >
                <input type="button" value="更 新" id="add" onclick="newEvent();" class="btn btn-success m-15" >
<!--                <input type="button" value="结 束" id="cancel" onclick="deleteEventMsg();" class="btn btn-danger m-15" >-->
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title"  width="30px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <!-- <th class="title" width="50px" itemvalue="infosourcePic" center="true">
                            </th> -->
                            <th class="title" width="66px" itemvalue="cardno" center="true">卡号
                            </th>
                            <th class="title" width="150px" itemvalue="carno" center="true">车牌
                            </th>
                            <th class="title" width="95px" itemvalue="bindtime" center="true" showformat="yyyy-MM-dd hh:mm:ss">绑定时间
                            </th>
                            <th class="title" width="100px" itemvalue="operate" center="true">操作
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
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>