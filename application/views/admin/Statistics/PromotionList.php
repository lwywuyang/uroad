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
                    <label for="StartTime">日期:</label>
                    <input type="text" class="form-control" id="StartTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <label for="EndTime">至</label>
                    <input type="text" class="form-control" id="EndTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
                </div>
                <div class="form-group">
                    <input type="button" value="查 询" id="checkWechat" onclick="statisticsOfPromotion();" class="btn btn-primary">
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-hover table-bordered dataTable" id="PromotionTable">
                <thead>
                    <tr>
                        <th class="title" width="" itemvalue="promotioncode">推广码
                        </th>
                        <th class="title" width="" itemvalue="allnum">总量
                        </th>
                        <th class="title" width="" itemvalue="androidnum">Android使用量
                        </th>
                        <th class="title" width="" itemvalue="iosnum">IOS使用量
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
    function statisticsOfPromotion(t){
		page=t;
		var StartTime = $('#StartTime').val();
		var EndTime = $('#EndTime').val();

        JAjax("admin/Statistics/PromotionLogic",'onLoadPromotionStatistics',{page:page,StartTime:StartTime,EndTime:EndTime}, function (data) {
            ReloadTb('PromotionTable', data.data);
        }, 'pager');
    }

    statisticsOfPromotion(1);
</script>
</html>