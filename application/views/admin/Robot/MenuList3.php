<!DOCTYPE html>
<html id="ng-app" ng-app="app">
<head>
    <title>Simple example</title>
    <?php $this->load->view('admin/common'); ?>
    <!-- <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" /> -->
    <style>
        /* .my-drop-zone { border: dotted 3px lightgray; }
        .nv-file-over { border: dotted 3px red; } Default class applied to drop zones on over
        .another-file-over-class { border: dotted 3px green; }
        
        html, body { height: 100%; } */
        #title{height: 40px!important;}
        /* #content{height: 300px;} */
        .col-xs-2{padding-right: 0;}
        .col-xs-10{padding-left: 0;}
        .col-xs-2,.col-xs-10,.col-xs-12{/* height: 40px;   */line-height: 40px;margin: 5px 0;}
        .getfile-btn{width: 75px; overflow: hidden;}
        .m-r-10{margin-right: 10px;}
        .word-break{white-space: pre-wrap; word-break: break-all; word-wrap: break-word;}
        .form-control{margin-left: 0; margin-right: 0}
        .wz{line-height:40px;float:left;}
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
            var status = $('#statusSel').val();
            var keyword = $('#keyword').val();
            JAjax("admin/Robot/RobotLogic", 'problemPage', {page:page,status:status,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
                // console.log(data);
            }, "pager");
           
        }

        function addProblemList(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/Robot/RobotLogic/addProblemList?questionid='); ?>"+id, '查看', 650, 530, reLoad);
        }

        function detail(id){
            showLayerPageJs("<?php echo base_url('/index.php/admin/Robot/RobotLogic/addProblemList?questionid='); ?>"+id, '修改', 650, 530, reLoad);
        }
        

        // 删除
        function dodelete(id){
            var questionid = id;
            
            ShowConfirm("您确定要删除吗？", function () {
                    JAjax("admin/Robot/RobotLogic", "deleteProblem", { questionid: questionid}, function (data) {
                        //alert(data.data);
                        if (data.data) {
                            ShowMsg('删除成功!');
                            reLoad();
                        }
                        else {
                            ShowMsg("删除失败：" + data.Message);
                        }
                    }, "pager");
                });
        }
    //预览
    function read(id){
        // if (url == '') {
         
            showLayerPageJs("<?php echo base_url('/index.php/admin/Robot/RobotLogic2/Newsdetail?questionid='); ?>"+id, '信息', 400, 600, reLoad);
        // }else{
        //     // showLayerPageJs(url, '信息', 600, 900, reLoad);
        // }
        
    }
       
 function Excel(){

        var statusSel = $('#statusSel').val();
        var keyword = $('#keyword').val();
        window.location.href = InpageUrl+'admin/Robot/RobotLogic/Excel?typeSel='+statusSel+"&keyword="+keyword;
    }
    </script>
</head>
<body marginwidth="0" marginheight="0" style="">
    <div class="panel panel-default" id="content_list">
    <div class="panel-heading"><a target="_parent" href="<?php echo base_url('index.php/admin/Meun'); ?>"><i class="fa fa-home"></i>首页</a>><a>回复菜单</a></div>
        <div class="panel-body">
            <div class="form-inline mb10">

                <div class="form-group">
                    <label for="searchTxt" class="wz" style="margin-right:50px;">智能机器人回复菜单</label>
                    
                    <div class="wz">类型：</div>
                    <select class="form-control" id="statusSel" onchange="reLoad();" style="float: left;">
                        <option value="">全部</option>
                        <option value="1">文本</option>
                        <option value="2">图文</option>
                        <option value="3">路况</option>
                    </select>  

                <div class="wz">关键字：</div>
                <input type="text" class="form-control m-10" style="width:200px;float:left;" id="keyword" placeholder="请输入关键字" />
                 
                <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-info m-15" style="float: left;">
                <!-- <input type="button" value="导 出" id="Channel" onclick="Excel();" class="btn btn-success m-10" style="margin-left: 10px;"> -->
                </div>
                <input type="button" style="float:right;" value="新增问题" id="new" onclick="addProblemList(0);" class="btn btn-success m-15" >
            </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="180px" itemvalue="title" center="true">问题
                            </th>
                            <th class="title" width="100px" itemvalue="questiontype" center="true">类型
                            </th>
                            <th class="title" width="300px" itemvalue="answer" center="true">回复
                            </th>
                            <th class="title" width="200px" itemvalue="keyword" center="true">关键字
                            </th>               
                            <th class="title" width="250px" itemvalue="operate" center="true">相关操作
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
                <!-- <div id="pager" fun="Load" class="pager" pagerobj="">
                </div> -->
            </div>
        </div>
        <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div>
        <!-- panel-body -->
    </div>
    <script type="text/javascript" language="javascript">
        
    </script>
</body>

<script type="text/javascript">
    
</script>
<script type="text/javascript" language="javascript">
        Load(1);
    </script>
</html>
