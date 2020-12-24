<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>设备维护</title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-15{margin-right:15px;}
		.m-20{margin-right:20px;}
        .table{margin-bottom: 0;}
	</style>

    <script type="text/javascript" language="javascript">
        var page = 1;

        function reLoad(){
            Load(page);
        }
        
        /**
         * @desc  拉取'设备维护'页面表格数据
         */
        function Load(t) {
            page = t;
            var road = $('#roadSel').val();
            var search = $('#searchTxt').val();

            JAjax("admin/MsgPublish/PhoneLogic", 'onLoadPhone', {page:page,road:road,search:search}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }


        function operatePhone(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/MsgPublish/PhoneLogic/operatePhone'); ?>?id="+id,'救援电话',630,380,reLoad);
        }


        /**
         * @desc   获取所有选取项,组成字符串
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

        function deletePhone(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("rpcheckbox", "#dataGrid");
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/MsgPublish/PhoneLogic", "delPhone", { deleteValue: values}, function (data) {
                        //alert(data.data);
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
                    <label for="roadSel">高速公路:</label>
                    <select class="form-control" id="roadSel" onchange="Load(1)">
                        <option value="">全部</option>
                        <?php foreach($roadold as $item):?>
                            <option value="<?=$item['roadoldid']?>"><?=$item['shortname']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchTxt">关键字:</label>
                    <input type="text" class="form-control" id="searchTxt" placeholder="请输入关键字">
                </div>
                <input type="button" value="查 询" id="new" onclick="Load(1);" class="btn btn-info m-15" >
                <input type="button" value="新 增" id="new" onclick="operatePhone(0);" class="btn btn-info m-15" >
                <input type="button" value="删 除" id="del" onclick="deletePhone();" class="btn btn-danger m-20">
            </div>
        </div>
        <div class="panel-body">
            
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>               
                        <tr>
                            <th class="title"  width="30px" itemvalue="id" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                            <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');"><!--InPage.js-->
                            </th>
                            <th class="title" width="10%" itemvalue="roadname" center="true">路段名
                            </th>
                            <th class="title" width="10%" itemvalue="phonenum" center="true">救援电话  
                            </th>
                            <th class="title" width="16%" itemvalue="remark" center="true">备注
                            </th>
                            <th class="title" width="70px" itemvalue="operate" center="true">操作
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