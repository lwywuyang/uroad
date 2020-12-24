<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>用户日志审计</title>
  <?php $this->load->view('admin/common') ?>
  <style>
    .m-5 {
      margin-right: 5px;
    }

    .m-15 {
      margin-right: 15px;
    }

    .table {
      margin-bottom: 0
    }

    .welcome-pic {
      max-height: 200px;
    }
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
      page = t;
      var startTime = $('#startTime').val();
      var endTime = $('#endTime').val();
      var keyword = $('#keyword').val();
      var postArr = {
        page: page,
        startTime: startTime,
        endTime: endTime,
        keyword: keyword
      }
      JAjax("admin/Organization/UserLog", 'getUserLogList', postArr, function (data) {
        ReloadTb('dataGrid', data.data);
      }, "pager");
    }

  </script>
</head>
<body marginwidth="0" marginheight="0">
<div class="panel panel-default" id="content_list">
  <div class="panel-heading">
    <div class="form-inline mb10">
      <label for="startTime">时间:</label>
      <input type="text" class="form-control" id="startTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
      <label for="endTime">至</label>
      <input type="text" class="form-control m-10" id="endTime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
      <label for="searchTxt">关键字:</label>
      <input type="text" class="form-control" id="keyword" placeholder="可根据用户名查询">
      <input type="button" value="查 询" id="new" onclick="Load(1);" class="btn btn-primary m-10" >
    </div>
  </div>
  <div class="panel-body">

    <div class="table-responsive">
      <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
        <thead>
        <tr>
          <th class="title" width="20%" itemvalue="userName" center="true">用户名
          </th>
          <th class="title" width="20%" itemvalue="ip" center="true">IP地址
          </th>
          <th class="title" width="20%" itemvalue="intime" center="true">登陆时间
          </th>
          <th class="title" width="20%" itemvalue="address" center="true">所在地址
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
    <div id="pager" fun="Load" class="pager" pagerobj=""></div>
  </div>
</div>
<script type="text/javascript" language="javascript">
  Load(1);
</script>
</body>
</html>
