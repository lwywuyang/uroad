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
        .photo-img{max-width: 50px;}
        a {cursor: pointer;}
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
            
            JAjax("admin/WXManage/HotLineLogic", 'onLoadHotLineMsg', {page:page}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        function checkDetail(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/HotLineLogic/showDetailMsg?tag=0&id=') ?>"+id,'热线详细',650,350,reLoad);
        }


        function addMsg(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/HotLineLogic/showDetailMsg?tag=1') ?>",'新增热线',650,350,reLoad);
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
        
        function deleteMsg() {
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/WXManage/HotLineLogic", "delHotLineMsg", {deleteValue:values}, function (data) {
                        if (data.Success) {
                            ShowMsg("操作成功!");
                            reLoad();
                            //closeLayerPageJs();
                        }else{
                            ShowMsg(data.Message);
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
                <input type="button" value="增 加" id="check" onclick="addMsg();" class="btn btn-primary m-15" >
                <input type="button" value="删 除" id="check" onclick="deleteMsg();" class="btn btn-danger m-15" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="3%" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="10%" itemvalue="seq" center="true">序号
                            </th>
                            <th class="title" width="20%" itemvalue="content" center="true">名称
                            </th>
                            <th class="title" width="20%" itemvalue="phonenumber" center="true">电话
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