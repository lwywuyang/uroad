<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title></title>
    <?php $this->load->view('admin/common') ?>
    <script src="<?php echo base_url('/asset/plugs/echarts.common.min.js') ?>"></script>
	<style>
        .table{margin-bottom: 0;}
        .form-group{margin-right: 10px;}
	</style>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="form-inline mb10">
                <div class="form-group" style="margin-right: 0;">
                    <label for="subtypeSel" id="subtypelabel">类型:</label>
                    <select class="dis-none form-control m-10" id="subtypeSel" onchange="statisticsOfPopularize();">
                        <option value="">全部</option>
                        <?php foreach($type as $item): ?>
                            <option value="<?php echo $item['dictcode'] ?>"><?php echo $item['name'] ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="form-group" style="margin-right: 0;">
                    <label for="keyword">关键字:</label>
                    <input type="text" class="form-control" id="keyword"  />
                </div>
                <div class="form-group" style="margin-right: 0;">
                    <label for="StartTime">日期:</label>
                    <input type="text" class="form-control" id="StartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <label for="EndTime">至</label>
                    <input type="text" class="form-control" id="EndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="checkWechat" onclick="reLoad();" class="btn btn-primary">
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-hover table-bordered dataTable" id="PopularizeTable">
                <thead>
                    <tr>
                        <th class="title" width="10%" itemvalue="empname">操作人
                        </th>
                        <th class="title" width="12%" itemvalue="name">类型
                        </th>
                        <th class="title" width="" itemvalue="content">内容
                        </th>
                        <th class="title" width="20%" itemvalue="intime" showformat="yyyy-MM-dd hh:mm:ss">操作时间
                        </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div id="pager" fun="Load" class="pager" pagerobj="">
            </div>
        </div>
    </div>
</body>
<script type="text/javascript" language="javascript">
	var page = 1;

    function reLoad() {
        Load(page);
    }

    //获取内容
    function Load(t){
		page=t;
        var keyword = $('#keyword').val();
		var StartTime = $('#StartTime').val();
		var EndTime = $('#EndTime').val();
		var subtypeSel = $('#subtypeSel').val();

        JAjax("admin/Statistics/ReleaseStatistics",'onLoadPopularizeStatistics',{page:page,keyword:keyword,StartTime:StartTime,EndTime:EndTime,subtypeSel:subtypeSel}, function (data) {
            ReloadTb('PopularizeTable', data.data);
        }, 'pager');
    }

    Load(1);
</script>
</html>