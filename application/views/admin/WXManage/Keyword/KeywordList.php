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
        .form-inline select{margin-right: 20px;}
        .photo-img{max-width: 50px;}
        a {cursor: pointer;}
        .heading-ul{width: 100%;height: 50px;line-height: 50px;padding: 0;}
        .heading-li{width: 50%;height: 50px;float: left;list-style: none;margin: 0 auto;border-radius: 3px;text-align: center;}
        .li-hover:hover{background-color: #D1DEF0;}
        .li-color{background-color: #428BCA;}
        .li-color a{color: white;}
        .heading-a{font-size: 24px;width: 100% !important;height: 100% !important;display: block;}
        .dis-none{display: none;}
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
            checkRoad(t);
        }

        function Load2(){
            checkRemark(1);
        }

        /***************路况***************/
        //读取内容
        function checkRoad(t){
            $('#road').addClass('li-color');
            $('#road').removeClass('li-hover');
            $('#remark').removeClass('li-color');
            $('#remark').addClass('li-hover');

            $('#roadTable').removeClass('dis-none');
            $('#remarkTable').addClass('dis-none');

            var keyword = $('#keyword_road').val();
            JAjax("admin/WXManage/KeywordManageLogic", 'onLoadMsg_RoadRule', {page:t,keyword:keyword}, function (data) {
                ReloadTb('dataRoad', data.data);
            }, "pagerRoad");
        }

        /**
         * @desc   路况->点击'新增规则'->展示新增关键字规则的信息页面
         */
        function addRoadRule(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/KeywordManageLogic/operate_RoadRule?tag=1') ?>",'新增',857,550,reLoad);
        }

        /**
         * @desc   路况->点击'修改'->展示某关键字规则的详细信息页面
         */
        function changeDetail_RoadRule(ruleId){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/KeywordManageLogic/operate_RoadRule?tag=0&ruleId=') ?>"+ruleId,'修改',857,550,reLoad);
        }

        /**
         * @desc   路况->点击'新增关键字'->展示新增关键字页面
         *         共用
         */
        function addKeyword_RoadRule(ruleId){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/KeywordManageLogic/addKeyword?ruleId=') ?>"+ruleId,'添加',500,280,reLoad);
        }



        /***************关键字回复***************/
        //获取内容
        function checkRemark(t){
            $('#road').removeClass('li-color');
            $('#road').addClass('li-hover');
            $('#remark').addClass('li-color');
            $('#remark').removeClass('li-hover');

            $('#roadTable').addClass('dis-none');
            $('#remarkTable').removeClass('dis-none');

            var keyword = $('#keyword_remark').val();
            JAjax("admin/WXManage/KeywordManageLogic", 'onLoadMsg_Remark', {page:t,keyword:keyword}, function (data) {
                ReloadTb('dataRemark', data.data);
            }, "pagerRemark");
        }

        /**
         * @desc   关键字回复->点击'新增规则'->展示新增关键字规则的信息页面
         */
        function addRemark(){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/KeywordManageLogic/operate_Remark?tag=1') ?>",'新增',857,550,checkRemark,1);
        }

        /**
         * @desc   关键字回复->点击'修改'->展示某关键字规则的详细信息页面
         */
        function changeDetail_Remark(ruleId){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/KeywordManageLogic/operate_Remark?tag=0&ruleId=') ?>"+ruleId,'修改',857,550,checkRemark,1);
        }

        /**
         * @desc   关键字回复->点击'新增关键字'->展示新增关键字页面
         */
        function addKeyword_Remark(ruleId){
            showLayerPageJs("<?php echo base_url('/index.php/admin/WXManage/KeywordManageLogic/addKeyword?ruleId=') ?>"+ruleId,'添加',500,280,Load2);
        }


</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <ul class="heading-ul">
                    <li class="heading-li" id="road"><a onclick="checkRoad();" class="heading-a">路 况</a></li>
                    <li class="heading-li" id="remark"><a onclick="checkRemark();" class="heading-a">关 键 字 回 复</a></li>
                </ul>
            </div>
        </div>
        <div id="roadTable">
            <div class="panel-body">
                <div class="form-inline mb10">
                    <input type="button" value="新增规则" id="addRoadRule" onclick="addRoadRule();" class="btn btn-danger m-15">
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control" id="keyword_road" placeholder="请输入关键字">
                    <input type="button" value="查 询" id="checkRoad" onclick="checkRoad();" class="btn btn-primary m-15" >
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="dataRoad">
                        <thead>
                            <tr>
                                <th class="title" width="15%" itemvalue="rule_name" center="true">规则名称
                                </th>
                                <th class="title" width="20%" itemvalue="keystring" center="true">关键字
                                </th>
                                <th class="title" width="64px" itemvalue="operate" center="true">操作
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
                <div id="pagerRoad" fun="checkRoad" class="pager" pagerobj="">
                </div>
            </div>
        </div>
        <div id="remarkTable" class="dis-none">
            <div class="panel-body">
                <div class="form-inline mb10">
                    <input type="button" value="新增规则" id="addKeywordRule" onclick="addRemark();" class="btn btn-danger m-15" >
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control" id="keyword_remark" placeholder="请输入关键字">
                    <input type="button" value="查 询" id="checkRemark" onclick="checkRemark();" class="btn btn-primary m-15" >
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered dataTable" id="dataRemark">
                        <thead>
                            <tr>
                                <th class="title" width="15%" itemvalue="rule_name" center="true">规则名称
                                </th>
                                <th class="title" width="15%" itemvalue="keystring" center="true">关键字
                                </th>
                                <th class="title" width="30%" itemvalue="remark" center="true">回复内容
                                </th>
                                <th class="title" width="110px" itemvalue="operate" center="true">操作
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
                <div id="pagerRemark" fun="checkRemark" class="pager" pagerobj="">
                </div>
            </div>
        </div>
        <!-- panel-body -->
        <!-- <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div> -->
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>