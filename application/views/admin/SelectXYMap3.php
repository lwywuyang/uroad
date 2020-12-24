<!DOCTYPE html>
<html>
<!-- 离线地图 -->
  <head>
  
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <link rel="stylesheet" href="<?php $this->load->helper('url');echo base_url('/asset/esri.css') ?>">
    <style>
      html, body, #map {
        height: 100%; width: 100%; margin: 0; padding: 0; 
      }
    </style>
    <?php $this->load->view('admin/common'); ?>
     <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/Common.js') ?>"></script>
     <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/arcigs.js') ?>"></script>
    <script>

     
        function xingHaoDeng() {
            this.name = "信号灯名字";
            this.address = "信号灯地址";
         }

        var map;
        var graphicPoint;
        var index = parent.layer.getFrameIndex(window.name);
        var Overallx = '<?php echo $x ?>' ;
        var Overally = '<?php echo $y ?>' ;
    
        
        // var markerSymbol ;123.442997,41.823381
        require(["esri/map", "esri/layers/ArcGISTiledMapServiceLayer", "esri/geometry/Point", "esri/symbols/SimpleMarkerSymbol", "esri/symbols/PictureMarkerSymbol", "esri/symbols/PictureFillSymbol", "esri/symbols/CartographicLineSymbol", "esri/graphic", "esri/Color", "esri/toolbars/draw", "dojo/dom", "dojo/on", "dojo/domReady!"],
        function (Map, Tiled, draw) {
            map = new Map("map", { center: [123.442997, 41.823381], zoom: 16, logo: false,showAttribution:false });
           //一开始载入
            map.on("load", function (e) {
              if(Overallx!=''&&Overally!=''){
                addGraphic(Overallx,Overally,'');
                var initPoint= new esri.geometry.Point(Overallx, Overally);
                map.centerAt(initPoint);
              }
               
            });
            //点击事件
             map.on("click", function (e) {
             
              if(graphicPoint!=null){
                  map.graphics.remove(graphicPoint);
                graphicPoint=null;
              }

                var targetP = e.mapPoint;
        
                var lon = targetP.getLongitude();
                var lat = targetP.getLatitude();
              //  tryAdd(lon, lat);
              var g=addGraphic(lon,lat,'');
               
            });

            // var tiled = new Tiled("http://192.168.199.136:7080/PBS/rest/services/MyPBSService1/MapServer");
            var tiled = new Tiled("http://10.1.2.8:7080/PBS/rest/services/MyPBSService2/MapServer");

            map.addLayer(tiled);

        }
      );

    
        function addTestPoint() {

            var ety1 = new xingHaoDeng();
            ety1.name = "信号灯1";

            addGraphic(112.461989, 23.05495, ety1);

            var ety2 = new xingHaoDeng();
            ety2.name = "信号灯2";
            addGraphic(112.46577, 23.056904, ety2);

            map.graphics.on("click", myGraphicsClickHandler);

         }




        function addGraphic(x, y,obj) {

            var p = new esri.geometry.Point(x, y);
            var simbol = new esri.symbol.PictureMarkerSymbol('<?php $this->load->helper("url");echo base_url("/images/machineMap.png") ?>', 80, 80);

            var attr = new Array();
            attr['obj'] = obj;
            var g = new esri.Graphic(p, simbol, attr);



            map.graphics.add(g);
            var graphicNode = g.getNode();
            graphicPoint=g;
            return g;


        }



        //点击弹出框
        function myGraphicsClickHandler(evt) {
            var g = evt.graphic;
            var attr = g.attributes;
            var ety = attr['obj'];
            var name = ety.name;
            OpendialogModeWindow('../Pop.aspx?name=' + name, window, 500, 400, 'modal');
        } 

         
     //关闭
      function closeLayer()
        {

            parent.layer.close(index);
        }
        var h;
        var w;
        jQuery(document).ready(function() {
         
             w =  document.body.scrollWidth;
             h =   document.body.scrollHeight;
            
              h=h*0.75;
              h1=h*0.7;
            // $(".panel-body").css("width",w);
            // $(".panel-body").css("height",h);
            // $("#map").css("height",h);
        });
    </script>
    <style type="text/css">
    #map{
      padding: 0;
      height: 100%;
      width: 100%;
    }
    </style>
  </head>
  <body  >
    
           <div id="map"></div>
        <!-- panel-body -->
      
        <!-- panel-body -->
        <div class="footer">
           
            <input type="button" id='closeLayer' value="关闭" class="btn btn-primary" onclick="closeLayer();" />
            
        </div>

  </body>
  <style type="text/css">
   /* #save{

    }*/
   .footer {
      padding: 10px 15px;
      background-color: #f5f5f5;
      border-top: 1px solid #ddd;
      border-bottom-right-radius: 3px;
      border-bottom-left-radius: 3px;
      position: fixed;
      bottom: 0px;
    }
  </style>
</html>