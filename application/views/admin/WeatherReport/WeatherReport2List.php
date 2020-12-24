<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>天气预报-天气预报-列表页</title>
    <?php $this->load->view('admin/common') ?>
    <style>
        /* common */
        th {
            font-weight: bold !important;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .m-r-10 {
            margin-right: 10px;
        }

        /* 表单 */
        .filtrate-wrap {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 20px 20px 0;
            list-style: none;
        }

        .filtrate-item {
            position: relative;
            display: flex;
            align-items: center;
            margin: 0 10px 10px 0;
        }

        .filtrate-item label {
            white-space: nowrap;
        }

        .name {
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
</head>

<body>
<div class="panel panel-default">
    <!-- 筛选 -->
    <div class="filtrate-wrap">
        <div class="filtrate-item">
            <label class="name">时间：</label>
            <input type="text" class="form-control" id="startTime" autocomplete="off"
                   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
            <label class="name">&nbsp;&nbsp;-&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="endTime" autocomplete="off"
                   onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
        </div>
        <div class="filtrate-item">
            <label class="name">预警来源：</label>
            <input type="text" class="form-control" id="warningfrom" placeholder="请输入预警来源">
        </div>
        <div class="filtrate-item">
            <input type="button" value="查 询" class="btn btn-primary m-r-10" onclick="getListData(1)">
        </div>
    </div>
    <!-- 列表 -->
    <div class="panel-body">
        <table class="table table-hover table-bordered dataTable" id="dataGrid">
            <thead>
            <tr>
                <th class="title" width="10%" itemvalue="warningimg" center="true" maxlength='6000'>封面图
                </th>

                <th class="title" width="10%" itemvalue="warningfrom" center="true">警报来源
                </th>
                <th class="title" width="10%" itemvalue="warnningstate" center="true">警报等级
                </th>
                <th class="title" width="30%" itemvalue="warnningtext" center="true">警报描述
                </th>
                <th class="title" width="10%" itemvalue="created" center="true" showformat="yyyy-MM-dd hh:mm">警报时间
                </th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <!-- 分页器 -->
    <div class="panel-footer">
        <div id="pager" fun="getListData" class="pager" pagerobj=""></div>
    </div>
</div>
</body>


<script type="text/javascript" language="javascript">
    let page = 1; // 页码

    $(function () {
        getListData(1);
    });

    /**
     * 获取列表数据
     * @param {String/Number} t 页码
     */
    function getListData(t) {
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();
        var warningfrom = $('#warningfrom').val();

        page = t;
        JAjax("admin/WeatherReport/WeatherReportLogic", "onLoadWeatherReport", {
            page: t,
            startTime: startTime,
            endTime: endTime,
            warningfrom: warningfrom,
        }, function (res) {
            ReloadTb('dataGrid', res.data);
        }, "pager", true);
    }


</script>
</html>