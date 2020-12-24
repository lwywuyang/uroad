<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('create_guid')) {
	function create_guid() {
        $uid=date('YmdHis',time());
        $stime = explode(' ', microtime());
        $stime =$stime[0]*1000000;
        $stime=ceil($stime);

        $ran=rand(100000,999999);
        $uuid="T".$uid.$stime.$ran;
        return $uuid;
	}
}