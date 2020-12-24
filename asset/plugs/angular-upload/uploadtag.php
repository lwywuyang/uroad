<?php
header("Content-type:text/html;charset=gb2312");
$url = 'http://hunangstapi.u-road.com/GSTHuNanAdmin/fileUpload/html/2016101714284714_201610160902594531.pdf';
$content = file_get_contents($url);
echo $content;
?>