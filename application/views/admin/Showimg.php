<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="apple-touch-icon-precomposed" href="">
    <meta name="description" content="">
    <meta name="keywords" content="">
     <script src="<?php $this->load->helper('url');echo base_url('/asset/imgjs/css/stlye.css') ?>"></script>
    <link rel="stylesheet" href="<?php $this->load->helper('url');echo base_url('/asset/imgjs/css/stlye.css') ?>">
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/imgjs/js/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/imgjs/js/drag_map.js') ?>"></script>
    <script type="text/javascript" src="<?php $this->load->helper('url');echo base_url('/asset/imgjs/js/jquery.rotate.min.js') ?>"></script>
    <style type="text/css">
        body{font-size: 12px;font-family: "Verdana" , "Arial" , "Helvetica" , "sans-serif";} td{font-size: 12px; line-height: 150%;} TD{font-size: 12px; color: #000000;} A{font-size: 12px; color: #000000;} #Layer1{z-index: 100; position: absolute; top: 150px;} #Layer2{z-index: 1; position: absolute;}
    </style>
    <script type="text/JavaScript">
    var imgurl='<?php echo $imgurl ?>';
        function MM_reloadPage(init) {
            if (init == true) with (navigator) {
                if ((appName == "Netscape") && (parseInt(appVersion) == 4)) {
                    document.MM_pgW = innerWidth; document.MM_pgH = innerHeight; onresize = MM_reloadPage;
                }
            }
            else if (innerWidth != document.MM_pgW || innerHeight != document.MM_pgH) location.reload();
        }
        MM_reloadPage(true);

        var rot=0;

        function rotate(r){
          rot=rot+r;
           $('#images1').rotate({angle:rot});

        }

    </script>
</head>
<body onLoad="" onmouseup="document.selection.empty()" oncontextmenu="return false"
    onselectstart="return false" ondragstart="return false" onbeforecopy="return false"
    style="overflow-y: hidden; overflow-x: hidden" oncopy="document.selection.empty()"
    leftmargin="0" topmargin="0" onselect="document.selection.empty()" marginheight="0"
    marginwidth="0">
	
    <div id="Layer1">
        <table cellspacing="2" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <td>&nbsp;
                    </td>
                    <td>
                        <img title="放大" style="cursor: hand" onClick="bigit();" height="20" src="<?php $this->load->helper('url');echo base_url('/asset/imgjs/images/zoom_in.gif') ?>"
                            width="20">
                    </td>
                    <td>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;
                    </td>
                    <td>
                        <img title="缩小" style="cursor: hand" onClick="smallit();" height="20" src="<?php $this->load->helper('url');echo base_url('/asset/imgjs/images/zoom_out.gif') ?>"
                            width="20">
                    </td>
                    <td>&nbsp;
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p>
        <br>
    </p>
    <div id="hiddenPic" style="z-index: 1; left: 0px; visibility: hidden; width: 0px;
        position: absolute; top: 30px;left: 80px; height: 0px">
        <img src="<?php echo $imgurl ?>">
    </div>
    <div class="dragAble" id="block1" onMouseOver="dragObj=block1; drag=1;" style="z-index: 10;
        left: 0px; width: 0px; position: absolute; top: 30px;left: 80px; height: 0px" onMouseOut=""
        drag="0">
        <img onmousewheel="return onWheelZoom(this)" style="zoom: 0.7" src="<?php echo $imgurl ?>"
            border="0" name="images1" id="images1">
    </div>
	<center>
    
</center>
<input type="button" onclick="rotate(90)" value="旋转" style="z-index: 13;position: absolute; top: 300px;left: 10px; " >
</body>
</html>
