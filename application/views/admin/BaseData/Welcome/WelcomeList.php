<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
		.m-15{margin-right:15px;}
        .table{margin-bottom: 0}
        .welcome-pic{max-height: 200px;}
	</style>

    <script type="text/javascript" language="javascript">
        var page = 1;
        
        function reLoad() {
            Load(page);
        }

        /**
         * [Load 加载基础数据]
         * @param {[type]} t [description]
         */
        function Load(t) {
            page=t;
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            JAjax("admin/baseData/WelcomeLogic", 'onLoadWelcomeMsg', {page:page,startTime:startTime,endTime:endTime}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        /**
         * @desc   点击'查看'
         * @param  {[type]}    id [description]
         */
        function checkWelcome(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/WelcomeLogic/addOrCheckWelcomeMsg?id=') ?>"+id,'查看',550,420,reLoad);
        }

        /**
         * @desc 点击'新增'->调用新增控制器函数
         */
        function addWelcome(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/WelcomeLogic/addOrCheckWelcomeMsg?id=0') ?>",'新增',550,420,reLoad);
        }
        

        /**
         * @desc   获取所有选取项,组成字符串
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


        function deleteWelcome() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");

            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/WelcomeLogic", "delWelcomeMsg", {deleteValue:values}, function (data) {
                        if (data.data) {
                            reLoad();
                        }else{
                            ShowMsg("删除失败:" + data.Message);
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
            <div class="form-inline mb10">
                <!-- <label for="start_time">时间:</label>
                <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="end_time">至:</label>
                <input type="text" class="form-control" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary m-15" > -->
                <input type="button" value="新 增" id="new" onclick="addWelcome();" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deleteWelcome();" class="btn btn-danger m-15" >
            </div>
        </div>
        <div class="panel-body">
            
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title"  width="25px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="40%" itemvalue="picImg" center="true">欢迎页面
                            </th>
                            <th class="title" width="10%" itemvalue="startdate" center="true">开始时间
                            </th>
                            <th class="title" width="10%" itemvalue="enddate" center="true">结束时间
                            </th>
                            <th class="title" width="40px" itemvalue="operate" center="true">操作 
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
            <div id="pager" fun="Load" class="pager" pagerobj=""></div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>