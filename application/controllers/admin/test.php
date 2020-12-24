<?php

/**
 * 测试页面
 */
$startdate="2015-07-11 11:40:00";
$enddate="2015-07-11 11:40:09";
$date=floor((strtotime($enddate)-strtotime($startdate))/86400);
$hour=floor((strtotime($enddate)-strtotime($startdate))%86400/3600);
$minute=floor((strtotime($enddate)-strtotime($startdate))%86400/60);
$second=floor((strtotime($enddate)-strtotime($startdate))%86400%60);
echo $date."天<br>";
echo $hour."小时<br>";
echo $minute."分钟<br>";
echo $second."秒<br>";




?>