<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
	<style>
		.m-5{margin-right:5px;}
        .m-10{margin-right:10px;}
        .table{margin-bottom: 0;}
        .photo{max-width: 80px;}
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
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            var keyword = $('#keyword').val();
            //var typeSel = $('#typeSel').val();
            
            JAjax("admin/MessageManage/UserLogic", 'onLoadUser', {page:page,startTime:startTime,endTime:endTime,keyword:keyword}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <label for="startTime">绑定时间:</label>
                <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="endTime">至</label>
                <input type="text" class="form-control m-10" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                <label for="keyword">关键字:</label>
                <input type="text" class="form-control m-10" id="keyword" placeholder="请输入关键字">
                <input type="button" value="查 询" id="check" onclick="Load(1);" class="btn btn-primary m-10" >
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title"  width="30px" itemvalue="eventid" showtype="checkbox" attr="name='rpcheckbox' href='javascript:void(0)'  istop='{istop}'">
                                <input type="checkbox" id="chkall" onclick="checkall('#dataGrid', this, 'rpcheckbox');">
                            </th>
                            <th class="title" width="100px" itemvalue="iconfile">头像
                            </th>
                            <th class="title" width="" itemvalue="username">用户名
                            </th>
                            <th class="title" width="" itemvalue="usernickname">昵称
                            </th>
                            <th class="title" width="" itemvalue="phone">电话 
                            </th>
                            <th class="title" width="" itemvalue="mails">邮箱
                            </th>
                            <th class="title" width="" itemvalue="createdtime" showformat="yyyy-MM-dd hh:mm:ss">创建时间
                            </th>
                            <!-- <th class="title" width="150px" itemvalue="operate">操作 
                            </th> -->
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