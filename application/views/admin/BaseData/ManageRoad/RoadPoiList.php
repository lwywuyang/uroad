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
        .td_display{display: none;}
	</style>

    <script type="text/javascript" language="javascript">
        var page = 1;
        var roadoldid = '<?php echo $roadoldid ?>';

        var secondTdHTML_option = '';
        var thirdTdHTML,fourthTdHTML,fifthTdHTML,sixTdHTML,sevenTdHTML;
        var msgArr;
        //获取表格行数,用于确定新行的id
        //var tabObj,tabRowsNum;
        //var tableObj = document.getElementById("dataGrid");
        var trObj,tableRows;

        var addnum = 0;//用于标记当前一共新增了多少次,方便初始下拉框选择状态

        //加载该路段的所有沿途站点,用于新增行操作
        $().ready(function(){
            
            JAjax("admin/baseData/RoadLogic", 'checkAllPoi', {roadoldid:roadoldid}, function (data) {

                msgArr = data.data;
                
            }, null);
        });


        function reLoad() {
            Load(page);
        }

        /**
         * [Load 加载基础数据]
         * @param {[type]} t [description]
         */
        function Load(t) {
            page=t;
            JAjax("admin/baseData/RoadLogic", 'onLoadRoadPoi', {roadoldid:roadoldid}, function (data) {
                ReloadTb('dataGrid', data.data);
            }, "pager");
        }

        /**
         * @desc 为表格新增一行
         */
        function addRow(){
            addnum = addnum + 1;
            if (addnum > msgArr.length) {
                addnum = 1;//如果新增下拉框次数为下拉内容条数+1,则重新轮转初始值
            }

            trObj = $("#dataGrid tbody tr");
            tableRows = trObj.length+1;

            //如果可以填充下拉框的内容为空
            if (msgArr.length == 0) {
                alert('没有沿途站');return;
            }
            //新建表格行
            var table = document.getElementById('dataGrid');
            //创建一个tr
            var newTr=document.createElement('tr');

            //创建第一个td
            var firstTd=document.createElement('td');
            firstTd.innerHTML = tableRows;
            newTr.appendChild(firstTd);//将td插入tr

            //创建第二个td
            var secondTd=document.createElement('td');
            secondTdHTML_option = '';
            //标记第几次循环,和addnum一起确定新增下拉框的初始选中值
            var eachNum = 0;
            $.each(msgArr,function(i){
                eachNum += 1;
                //新增第n个tr,则默认选中第n项
                if (addnum == eachNum) {
                    thirdTdHTML = msgArr[i]['miles'];
                    fourthTdHTML = msgArr[i]['poiid'];
                    fifthTdHTML = msgArr[i]['stationcode'];
                    sixTdHTML = msgArr[i]['positivecode'];
                    sevenTdHTML = msgArr[i]['reversecode'];
                    secondTdHTML_option += "<option selected value='"+msgArr[i]['poiid']+"'>"+msgArr[i]['name']+'('+msgArr[i]['miles']+")</option>";
                }else{
                    secondTdHTML_option += "<option value='"+msgArr[i]['poiid']+"'>"+msgArr[i]['name']+'('+msgArr[i]['miles']+")</option>";
                }
            });
            secondTd.innerHTML = "<select class='form-control' onchange='changeSecondTd(this)'>"+secondTdHTML_option+"</select>";
            newTr.appendChild(secondTd);//将td插入tr

            //创建第三个td,公里数
            var thirdTd=document.createElement('td');
            thirdTd.innerHTML = thirdTdHTML;
            newTr.appendChild(thirdTd);//将td插入tr

            //创建第四个td,站点id
            var fourthTd=document.createElement('td');
            fourthTd.setAttribute("class","td_display");//设置成隐藏
            fourthTd.innerHTML = fourthTdHTML;
            newTr.appendChild(fourthTd);//将td插入tr

            //创建第五个td,站点code
            var fifthTd=document.createElement('td');
            //fifthTd.setAttribute("class","td_display");//设置成隐藏
            fifthTd.innerHTML = fifthTdHTML;
            newTr.appendChild(fifthTd);//将td插入tr

            //创建第六个td,trafficsplitcode
            var sixTd = document.createElement('td');
            sixTd.innerHTML = sixTdHTML;
            newTr.appendChild(sixTd);//将td插入tr

            //创建第七个td,trafficsplitcode
            var sevenTd = document.createElement('td');
            sevenTd.innerHTML = sevenTdHTML;
            newTr.appendChild(sevenTd);//将td插入tr

            //创建第八个td,caozuo
            var eightTd=document.createElement('td');
            eightTd.innerHTML = '<lable class="btn btn-success btn-xs" onclick="deleteThisTr(this)">删除</lable>';
            newTr.appendChild(eightTd);//将td插入tr
            
            table.tBodies[0].appendChild(newTr);//将整个tr插入到表格中
        }


        /**
         * @desc   下拉框改变时,改变相应行的公里数
         * @param  {[type]}    obj [description]
         * @return {[type]}        [description]
         */
        function changeSecondTd(obj){
            var temSel = $.trim($(obj).children("option:selected").val());

            for (var i=0; i<msgArr.length; i++) {
                if (msgArr[i]['poiid'] == temSel) {
                    var milesValue = msgArr[i]['miles'];
                    var poiidValue = msgArr[i]['poiid'];
                    var codeValue = msgArr[i]['stationcode'];
                }
            }


            var ParentTr = $(obj).parent().parent();
            ParentTr.find('td').eq(2).text(milesValue);
            ParentTr.find('td').eq(3).text(poiidValue);
            ParentTr.find('td').eq(4).text(codeValue);

            updateCode();
        }


        /**
         * @desc   删除该行站点
         * @param  {[type]}    obj [description]
         * @return {[type]}        [description]
         */
        function deleteThisTr(obj){
            var ParentTr = $(obj).parent().parent();
            ParentTr.remove();

            //更新序号
            var row = 1;
            var $tempTrText = $("#dataGrid tbody tr");
            for (var i = 0; i < $tempTrText.length; i++) {
                var $tempTd = $tempTrText.eq(i).find("td");
                $tempTd.eq(0).text(row);
                row = row + 1;
            }

            updateCode();
        }

        //更新表的trafficsplitcode
        function updateCode(){
            var $tempTr = $("#dataGrid tbody tr");
            for (var i = 0; i < $tempTr.length; i++) {

                //var $lastTempTd = $tempTr.eq(i-1).find("td");放这里会报错哦~~
                var $tempTd = $tempTr.eq(i).find("td");
                var $nextTempTd = $tempTr.eq(i+1).find("td");

                //更新正向code
                if (i != ($tempTr.length - 1)) {
                    var bit1 = $tempTd.eq(4).text().toString();//当前行stationcode
                    var bit2 = $nextTempTd.eq(4).text().toString();//下一行stationcode
                    var newCode = bit1+bit2;
                    $tempTd.eq(5).text(newCode);
                }else{
                    $tempTd.eq(5).text('');
                }

                //更新方向code
                if (i != 0) {
                    //$lastTempTd的声明只能放这里面,否则i为0时会在eq(-1)处报错!!
                    var $lastTempTd = $tempTr.eq(i-1).find("td");
                    var bit1 = $tempTd.eq(4).text().toString();//当前行stationcode
                    var bit2 = $lastTempTd.eq(4).text().toString();//上一行stationcode
                    var newCode = bit1+bit2;
                    $tempTd.eq(6).text(newCode);
                }else{
                    $tempTd.eq(6).text('');
                }

            }
        }


        /**
         * @desc   清除该路段的所有站点
         * @return {[type]}    [description]
         */
        function deleteAllTr(){
            addnum = 0;
            var thisTable = document.getElementById("dataGrid");
            var allTr = thisTable.getElementsByTagName("tr");
           
            for(var i = allTr.length - 1; i > 0; i--) {
                thisTable.deleteRow(i);
            }
        }


        /**
         * @desc   获取表格所有的站点信息,发送到后台保存
         * @return {[type]}    [description]
         */
        function saveAll(){
            var dataArr = new Array();
            var $tempTrText = $("#dataGrid tbody tr");
            for (var i = 0; i < $tempTrText.length; i++) {//每行数据组成一个dataArr的子数组
                var $tempTd = $tempTrText.eq(i).find("td");
                dataArr[i] = Array();
                dataArr[i][0] = $.trim($tempTd.eq(0).text());

                if($tempTd.children().prop("tagName").toUpperCase() == "SELECT"){
                    dataArr[i][1] = $tempTd.children().children("option:selected").text();
                }else{
                    dataArr[i][1] = $.trim($tempTd.eq(1).text());
                }

                dataArr[i][2] = $.trim($tempTd.eq(2).text());
                dataArr[i][3] = $.trim($tempTd.eq(3).text());
                dataArr[i][4] = $.trim($tempTd.eq(4).text());
                dataArr[i][5] = $.trim($tempTd.eq(5).text());
                dataArr[i][6] = $.trim($tempTd.eq(6).text());
                //console.log("addr:"+addr+"miles:"+miles+"poiid:"+poiid);
                //console.log(dataArr[i]);return;
            }
            JAjax('admin/baseData/RoadLogic','changeAllPoi',{roadoldid:roadoldid,dataArr:dataArr},function(data){
                if (data.data == true) {
                    alert("操作成功!");reLoad();
                }else{
                    ShowMsg("操作失败:" + data.data);
                }
            },'pager');
        }


        function goRoadLogicList(){
            closeLayerPageJs();
        }

        /**
         * @desc   清除表格数据,一键生成新数据
         */
        function fastFormAllRow(){
            //首先清空表格
            deleteAllTr();
            for(var i=0;i<msgArr.length;i++){
                addRow();
            }

            updateCode();
        }


        function addOneRow(){
            addRow();
            updateCode();
        }

</script>
</head>
<body marginwidth="0" marginheight="0">
    <div class="panel panel-default" id="content_list">
        <div class="panel-body">
            <div class="form-inline mb10">
                <input type="button" value="新 增" id="new" onclick="addOneRow();" class="btn btn-info m-15" >
                <input type="button" value="一键生成" id="fastForm" onclick="fastFormAllRow();" class="btn btn-info m-15" >
                <input type="button" value="清 除" id="del" onclick="deleteAllTr();" class="btn btn-danger m-20" >
                <div class="form-group">
				    <label for="searchTxt">路段:<?php echo $roadname ?></label>
                </div>
                <input type="button" value="保 存" id="search" onclick="saveAll();" class="btn btn-primary m-15" >
                <input type="button" value="返 回" id="update" onclick="goRoadLogicList();" class="btn btn-success m-15" >
            </div>
            <div class="table-responsive">
                <table class="table mb30 table-hover table-bordered dataTable" id="dataGrid">
                    <thead>
                        <tr>
                            <th class="title" width="50px" itemvalue="seq" center="true">序号
                            </th>
                            <th class="title" width="180px" itemvalue="name" center="true">站名
                            </th>
                            <th class="title" width="" itemvalue="miles" center="true">公里数
                            </th>
                            <th itemvalue="stationid" style="display: none;" hide="true" >站点ID
                            </th>
                            <th itemvalue="stationcode">站点code<!-- style="display: none;" hide="true" -->
                            </th>
                            <th class="title" width="" itemvalue="positivecode" center="true">正向code
                            </th>
                            <th class="title" width="" itemvalue="reversecode" center="true">反向code
                            </th>
                            <th class="title" width="80px" itemvalue="operate" center="true">操作
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 数据 -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        Load(1);
    </script>
</body>
</html>