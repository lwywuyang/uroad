<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>ETC用户</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
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

            var roadId = $('#roadSel').val();
            var type = $('#typeSel').val();
            var keyword = $('#keyword').val();
            JAjax("admin/ETCManage/ETCAdminLogic", 'onLoadETCAdmin', {page:page,roadId:roadId,type:type,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc   查看站点详情/参数传0则为新增
         * @data   2015-9-17 15:17:47
         * @param  {[type]}    poiid [description]
         * @return {[type]}          [description]
         */
        function checkDetail(poiid) {
            showLayerPageJs("<?php echo base_url('/index.php/admin/ETCManage/ETCAdminLogic/checkETCAdminDetail?id='); ?>"+poiid, '查看', 880, 555, reLoad);
        }

        /**
         * @desc 新增站点
         * @data 2015-9-17 17:57:24
         */
        function addRoadPoi(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/ETCManage/ETCAdminLogic/checkETCAdminDetail?id=0'); ?>", '新增', 880, 555, reLoad);
        }


        /**
         * @desc   获取所有选取项,组成字符串
         * @data   2015-9-17 19:03:09
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
                    JAjax("admin/ETCManage/ETCAdminLogic", "delRoadPoi", { deleteValue: values}, function (data) {
                        //alert(data.data);
                        if (data.data) {
                            //ShowMsg('删除成功!');
                            reLoad();
                        }
                        else {
                            ShowMsg("删除失败!");
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
                    <!-- <label for="searchTxt">车型:</label>
                    <select class="form-control" onchange="Load(1)" id="roadSel">
                        <option value="">全部</option>
                        <option value="1">一型客</option>
                        <option value="2">二型客</option>
                        <option value="3">三型客</option>
                        <option value="4">四型客</option>
                        <option value="15">计重车</option>
                    </select>&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="searchTxt">车牌颜色:</label>
                    <select class="form-control" onchange="Load(1)" id="typeSel">
                        <option value="">全部</option>
                        <option value="0">蓝色</option>
                        <option value="1">黄色</option>
                        <option value="2">黑色</option>
                        <option value="3">白色</option>
                    </select>&nbsp;&nbsp;&nbsp;&nbsp; -->
                    <label for="searchTxt">关键字:</label>
                    <input type="text" class="form-control" id="keyword" placeholder="请输入关键字">
                </div>
                <input type="button" value="查 询" id="search" onclick="Load(1);" class="btn btn-primary m-15" >
                <!-- <strong>APP模型更新(作用于高速快览)：</strong> -->
                <input type="button" value="新 增"  id="new" onclick="addRoadPoi();" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deletePoi();" class="btn btn-danger m-15" >
                <!-- <input type="button" value="更 新" id="update" onclick="" class="btn btn-success" > -->
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
                            <th class="title" width="8%" itemvalue="usercode" center="true">用户编码
                            </th>
                            <th class="title" width="8%" itemvalue="username" center="true">单位名称
                            </th>
                            <th class="title" width="16%" itemvalue="relateman" center="true" showtype="a" attr="onclick= checkDetail('{id}') href='javascript:void(0)'" itemtext="{itemvalue}">名称
                            </th>
                            <th class="title" width="10%" itemvalue="phone" center="true">手机号
                            </th>
                            <th class="title" width="10%" itemvalue="cardid" center="true">卡号
                            </th>
                            <th class="title" width="8%" itemvalue="numberplate" center="true">车牌号
                            </th>
                            <th class="title" width="8%" itemvalue="vehtype1" center="true">车型
                            </th>
                            <th class="title" width="8%" itemvalue="platecolor1" center="true">车牌颜色
                            </th>
                            <th class="title" width="8%" itemvalue="intime" center="true" showformat="yyyy-MM-dd hh:mm:ss">录入时间
                            </th>
                            <th class="title" width="8%" itemvalue="operatorname" center="true">录入人
                            </th>
                            <th class="title" width="8%" itemvalue="modified" center="true" showformat="yyyy-MM-dd hh:mm:ss">修改时间
                            </th>

                            <!-- <th class="title" width="10%" itemvalue="" center="true" showtype="a" attr="onclick= checkDetail('{poiid}') href='javascript:void(0)'" itemtext="查看">操作
                            </th> -->
<!--                            <th class="title" width="50px" itemvalue="operate" center="true">操作-->
<!--                            </th>-->
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