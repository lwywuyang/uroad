<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>加精编辑</title>
    <?php $this->load->view('admin/common'); ?>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=rRf7mtEphGeh8d4rKV0wYEbN"></script>
    <script type="text/javascript">
        var addNewMarker = null;
        var map;
        var index = parent.layer.getFrameIndex(window.name);
        $(document).ready(function () {
            // ReSizeiFrameByPage2();
            $("#allmap").height($(window).height()-120);
            $("#allmap").width($(window).width()-40);
            var x = '<?php echo $x ?>' ;
            var y = '<?php echo $y ?>' ;
            map = new BMap.Map("allmap");    // 创建Map实例
            
            map.addControl(new BMap.MapTypeControl());   //添加地图类型控件
            map.setCurrentCity("沈阳");          // 设置地图显示的城市 此项是必须设置的
            map.enableScrollWheelZoom(true);
            map.addEventListener("click", clickMap);
            if (!x||!y||x == 0||y==0) {
                x=123.4299;
                y=41.798306;
            }
            else{
                addNewMarker = new BMap.Marker(new BMap.Point(x, y));
                addNewMarker.enableDragging();
                map.addOverlay(addNewMarker);

            }
           
            map.centerAndZoom(new BMap.Point(x, y), 12);  // 初始化地图,设置中心点坐标和地图级别
        });
        function clickMap(e) {
            //alert(e.point.lng + ", " + e.point.lat);
            //alert(e.point.lng + ", " + e.point.lat);
            if (addNewMarker != null) {
                //alert(addMarker);
                addNewMarker.setPosition(new BMap.Point(e.point.lng, e.point.lat));
            }
            else {
                addNewMarker = new BMap.Marker(new BMap.Point(e.point.lng, e.point.lat));
                addNewMarker.enableDragging();
                map.addOverlay(addNewMarker);
            }
            
        }
       \
            if (addNewMarker != null) {
                
                var geoc = new BMap.Geocoder();    
                geoc.getLocation(addNewMarker.point, function(rs){
                    var addComp = rs.addressComponents;
                    var address=addComp.province +  addComp.city +  addComp.district +  addComp.street + addComp.streetNumber
                    //alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
                    parent.getxy(addNewMarker.point.lng,addNewMarker.point.lat,address);
                    $("#BodyContent_address").val(address);
                    parent.layer.close(index);
                });  
               
               
            }
        }
        function closeLayer()
        {

            // parent.getxy(addNewMarker.point.lng,addNewMarker.point.lat,"2");
            parent.layer.close(index);
        }
    </script>

  </head>
  
  <body>
     <div class="panel panel-default form-horizontal ">
       
        <div class="panel-body ">
           <div id="allmap" style="width:400px; height:400px;"></div>
        <!-- panel-body -->
        </div>
        <!-- panel-body -->
        <div class="panel-footer">
           
            <input type="button" value="确定" class="btn btn-primary" onclick="save();" />
            <input type="button" value="取消" class="btn btn-primary" onclick="closeLayer();" />
            
        </div>
    </div>
  </body>

</html>