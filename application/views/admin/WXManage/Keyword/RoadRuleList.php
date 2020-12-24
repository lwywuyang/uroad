<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <link rel="stylesheet" type="text/css" href="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/webuploader/webuploader.js') ?>"></script>
	<style>
        .m-0,.form-inline .m-0{margin: 0;}
		.m-5{margin-right:5px;}
		.m-20{margin-right:20px;}
        .m-10{margin-top: 10px;}
        .panel-heading{color: #FF634D !important;font-size: 18px;}
        a {cursor: pointer;}
        .rule-road{width: 200px;float: left;}
	</style>
    
    <script type="text/javascript" language="javascript">
        //var keywordData = "<?php if(isset($keywordData)){echo $keywordData;} ?>";
        var keywordData = '<?php echo isset($keywordData)?$keywordData:''; ?>';
        var ruleId = '<?php echo isset($ruleData["rule_id"])?$ruleData["rule_id"]:''; ?>';
        var ruleName = '<?php echo isset($ruleData["rule_name"])?$ruleData["rule_name"]:''; ?>';
        var ruleRoad = '<?php echo isset($ruleData["remark"])?$ruleData["remark"]:''; ?>';
        var keywordDataArr = new Array();

        $().ready(function(){
            //遍历循环,设置路段的初始选中值
            var ruleRoadArr = ruleRoad.split(',');
            for(var i=0;i<ruleRoadArr.length;i++){
                $("input[type=checkbox][name=road][value="+ruleRoadArr[i]+"]").attr('checked','checked');
            }
            $('#ruleName').val(ruleName);
            //处理keywordData,使适应为空的情况
            if (keywordData != '')
                keywordDataArr = eval('('+ keywordData +')');
            //遍历输出表keywordTable的内容
            ReloadTb('keywordTable', keywordDataArr);

        });


        function dropOut() {
            closeLayerPageJs();
        }

        function trimStr(str){//删除左右两端的空格
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }

        function submitRoadRuleMsg(){
            var ruleName = $('#ruleName').val();

            //获取路段多选框选中值
            var checkboxValue = '';
            var checkedValue = '';
            $("input:checkbox[name='road']:checked").each(function() {
                var value = $(this).val();
                checkboxValue += value + ',';
            });
            if (trimStr(checkboxValue) != '') {//判断是否有选中
                //checkboxValue = subValueStr(checkboxValue);//去掉最后的逗号
                checkedValue = checkboxValue.substring(0,(checkboxValue.length-1))
            }else{
                alert('请至少一条高速公路!');return;
            }

            //获取关键字表格内容
            /*var keywordStr = '';
            $tableTr = $('#keywordTable tbody tr');//获取行
            for(var i=0;i<tableTr.length;i++){
                var tableTd = tableTr.eq(i).find('td');
                ///alert(tableTd.eq(1));return;
                keywordStr += tableTd.eq(1).text();
            }*/


            JAjax("admin/WXManage/KeywordManageLogic","saveMsg_RoadRule",{ruleId:ruleId,ruleName:ruleName,checkedValue:checkedValue}, function (data){
                if (data.Success)
                    //ShowMsg('操作成功!');
                    dropOut();
                else
                    ShowMsg('失败:'+data.Message);
            },'pager');

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



        function deleteKeyword(){
            //获取选中选框,属性的name元素,dataGrid上下文对象
            var values = getAllCheckedValues("kwcheckbox", "#keywordTable");
            //alert(values);
            if (values != "" && values != undefined) {
                ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/WXManage/KeywordManageLogic", "delKeyword", {deleteValue:values}, function (data) {
                        if (data.Success) {
                            //ShowMsg('删除成功!');
                            refreshTable();
                        }else {
                            ShowMsg("失败:" + data.Message);
                        }
                    }, "pager");
                });
            }else {
                ShowMsg("请至少选择一个关键字！");
            }

        }


        function refreshTable(){
            JAjax("admin/WXManage/KeywordManageLogic","onLoadKeyword",{ruleId:ruleId}, function (data){
                
                var dataArr = eval('('+ data.data +')');
                ReloadTb('keywordTable', dataArr);
            },'pager');
        }



</script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">微信关键字规则</div>
        <div class="panel-body">
            <div class="form-inline mb10">
                <label for="ruleName">规则名称:</label>
                <input type="text" class="form-control" id="ruleName" placeholder="请输入规则名称">
            </div>
            <div class="form-inline mb10">
                <?php foreach($road as $item): ?>
                    <div class="rule-road">
                        <label>
                            <input type='checkbox' name="road" value="<?php echo $item['roadoldid']; ?>" />
                            <?php echo $item['roadName']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-inline mb10">
                <input type="button" value="删 除" id="checkRoad" onclick="deleteKeyword();" class="btn btn-primary m-10" >
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="keywordTable">
                    <thead>
                        <tr>
                            <th class="title"  width="30px" itemvalue="key_id" showtype="checkbox" attr="name='kwcheckbox' href='javascript:void(0)' ">
                                <input type="checkbox" id="chkall" onclick="checkall('#keywordTable',this,'kwcheckbox');">
                            </th>
                            <th class="title" width="" itemvalue="keyword" center="true">关键字
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
            <div class="form-inline mb10">
                <input type="button" value="确 定" onclick="submitRoadRuleMsg();" class="btn btn-info m-20" >
                <input type="button" value="取 消" onclick="dropOut();" class="btn btn-danger m-20" >
            </div>
        </div>
    </div>
</body>
</html>