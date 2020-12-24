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
            var search = $('#searchTxt').val();
            JAjax("admin/baseData/BigPhoneLogic", 'onLoadBigPhone', {page:page,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        /**
         * @desc   点击'查看'
         * @data   2015-9-25 14:52:48
         * @param  {[type]}    id [description]
         */
        function checkBigPhone(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/BigPhoneLogic/operateBigPhoneMsgList?tag=0&id=') ?>"+id,'查看',550,460,reLoad);
        }

        /**
         * @desc 点击'新增'->调用新增控制器函数
         * @data 2015-9-25 15:49:27
         */
        function add(){
            //alert('deviceid信息不全,无法开展工作');
            showLayerPageJs("<?php echo base_url('/index.php/admin/baseData/BigPhoneLogic/operateBigPhoneMsgList?tag=1') ?>",'新增',550,380,reLoad);
        }
        

        /**
         * @desc   获取所有选取项,组成字符串
         * @data   2015-9-16 09:25:36
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


        function deleteBigPhone() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/baseData/BigPhoneLogic", "delBigPhone", {deleteValue:values}, function (data) {
                        if (data.data) {
                            //ShowMsg('删除成功!');
                            reLoad();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "pager");
                });
            }
            else {
                ShowMsg("请至少选择一条记录！");
            }
        }

</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group">
                    <label for="searchTxt">  关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary m-15" >
                <input type="button" value="新 增" id="new" onclick="add();" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deleteBigPhone();" class="btn btn-danger m-15" >
            </div>
        </div>
        <div class="panel-body">
            
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title"  width="3%" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');"><!--InPage.js-->
                            </th>
                            <th class="title" width="15%" itemvalue="devicename" center="true">大手机名称
                            </th>
                            <th class="title" width="8%" itemvalue="longitude" center="true">经度
                            </th>
                            <th class="title" width="8%" itemvalue="latitude" center="true">纬度
                            </th>
                            <th class="title" width="12%" itemvalue="city" center="true">城市
                            </th>
                            <th class="title" width="10%" itemvalue="statusName" center="true">状态 
                            </th>
                            <!-- <th class="title" width="10%" itemvalue="" center="true" showtype="a" attr="onclick= checkBigPhone('{id}') href='javascript:void(0)' " itemtext="查看">操作
                            </th> -->
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