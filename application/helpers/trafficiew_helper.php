<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('trafficiew')) {

  //按月组织字符,拿出当前月
  function trafficviewmouth($table,$mouth,$forum,$year,$pname,$sexmouth,$membermouthtype){
    $html='';
    $mouthdata=mouthdata($table,$mouth,$forum,$year,$pname,$sexmouth,$membermouthtype);
    for ($i=1; $i<32;$i++) { 
      $flag=0;
      //取出数据
      if($i==31){
        for($j=0;$j<count($mouthdata);$j++){
        if($i==$mouthdata[$j]['e']){
          $html.=$mouthdata[$j]['e'].','.$mouthdata[$j]['num'];
          $flag=1;
          }
        }
        if($flag==0){
          $html.=$i.',0';
        }
      }else{
        for($j=0;$j<count($mouthdata);$j++){
          if($i==$mouthdata[$j]['e']){
            $html.=$mouthdata[$j]['e'].','.$mouthdata[$j]['num'].';';
            $flag=1;
          }
        }
        if($flag==0){
          $html.=$i.',0;';
        }
      }

    }
    $html.='';
    return $html;
  }


  function mouthdata($table,$mouth,$forum,$year,$pname,$sexmouth,$membermouthtype){
    $CI =& get_instance();
    $CI->load->model('Trafficview/Trafficview_model', 'tra');
    $data=$CI->tra->mouth($table,$mouth,$forum,$year,$pname,$sexmouth,$membermouthtype);
    return $data;
  }
}

?>