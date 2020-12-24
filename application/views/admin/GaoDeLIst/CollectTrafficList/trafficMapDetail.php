<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>地图详细</title>
    <?php $this->load->view('admin/common') ?>
    <style>
        .map {
            padding: 5px;
        }
    </style> 
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=aaaacfd2066bd9ff25658d55741539ae&plugin=AMap.Geocoder"></script>    

</head>
<body marginwidth="0" marginheight="0" style="">
        <div class="panel panel-default form-horizontal ">
            <div class="panel-body ">
                <div class="map" id="container" ></div>
            </div>
        </div>
    <script type="text/javascript" language="javascript">
        var base_url = "<?php echo $this->config->base_url(); ?>";
        var detailData = '<?php echo $detailData; ?>';
        var xy = '<?php echo empty($gaodexy)?"":$gaodexy; ?>';
        var jamSpeed = '<?php echo empty($event['jamSpeed'])?"":$event['jamSpeed']; ?>';
        var jamDist = '<?php echo empty($event['jamDist'])?"":$event['jamDist']; ?>';
        var longTime = '<?php echo empty($event['longTime'])?"":$event['longTime']; ?>';
        var roadName = '<?php echo empty($gaoderoadName)?"":$gaoderoadName; ?>';
        var createTime = '<?php echo empty($gaodeinserttime)?"":$gaodeinserttime; ?>';

        var jsondata = JSON.parse(detailData);
        console.log(jsondata);
        var lineAllArr = jsondata.split('|');
        // console.log(lineAllArr);
        var map = new AMap.Map('container', {
            resizeEnable: true,
            zoom:15,
            center: [xy.split(",")[0],xy.split(",")[1]]
        });
        for (var i = 0; i < lineAllArr.length; i++) {
            var xys = lineAllArr[i].split(';');
            var lineArr = [];
            for (var j = 0; j < xys.length; j++) {
                lineArr[j] = [xys[j].split(",")[0] , xys[j].split(",")[1]];
            };
             var polyline = new AMap.Polyline({
                path: lineArr,          //设置线覆盖物路径
                strokeColor: "#ff0000", //线颜色
                strokeOpacity: 1,       //线透明度
                strokeWeight: 5,        //线宽
                strokeStyle: "solid",   //线样式
                strokeDasharray: [10, 5] //补充线样式
            });
            polyline.setMap(map);

        };

        // for (var i = 0; i < jsondata.length; i++) {
        //     var xys = jsondata.split(";");

        //     var lineArr = [];
        //     for (var j = 0; j < xys.length; j++) {
        //         lineArr[j] = [xys[j].split(",")[0] , xys[j].split(",")[1]];
        //     };
        //     var polyline = new AMap.Polyline({
        //         path: lineArr,          //设置线覆盖物路径
        //         strokeColor: "#ff0000", //线颜色
        //         strokeOpacity: 1,       //线透明度
        //         strokeWeight: 5,        //线宽
        //         strokeStyle: "solid",   //线样式
        //         strokeDasharray: [10, 5] //补充线样式
        //     });
        //     polyline.setMap(map);
        // };   
        var marker = new AMap.Marker({
            map: map,
            position: [xy.split(",")[0],xy.split(",")[1]],
            offset: new AMap.Pixel(-20, -20), //相对于基点的偏移位置
            clickable: true  
        }); 
        marker.setMap(map);   

        var trafficLayer = new AMap.TileLayer.Traffic({
            zIndex: 10
        });
        trafficLayer.setMap(map);
        trafficLayer.hide();
        marker.on('click',function(){
            showInfoWindows();
        });

        var startXY = [xy.split(",")[0],xy.split(",")[1]];

        var geocoder = new AMap.Geocoder({
            radius: 1000,
            extensions: "all"
        });
        geocoder.getAddress(startXY, function(status, result) {
            if (status === 'complete' && result.info === 'OK') {
                startAddress = result.regeocode.formattedAddress;
                showInfoWindows();
            }
        });

        function showInfoWindows(){
            var info = [];
            var infoWindow;
            info.push("<div><b>" + roadName + "</b>");
            info.push("地点： "+ startAddress);
            info.push("事件创建时间： " + createTime);
            info.push( "时速： "+jamSpeed + " 公里/小时");
            info.push("拥堵： "+ jamDist +" 公里");
            info.push("持续： "+ longTime +"  分钟</div>");
            infoWindow = new AMap.InfoWindow({
                content: info.join("<br/>")  //使用默认信息窗体框样式，显示信息内容
            });
            infoWindow.open(map, marker.getPosition());
        }

        AMap.plugin(['AMap.ToolBar'],
            function(){
                map.addControl(new AMap.ToolBar());
            });
    </script>   
</body>
</html>