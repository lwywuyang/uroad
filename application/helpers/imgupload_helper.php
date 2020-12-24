<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('imgupload')){
/*
     * 上传图片
     * */
     function imgupload(){
        $CI =& get_instance();
        $CI->load->helper('file');

        $imagebase64 = $_POST['imagebase64'];

         
        $imagefile = base64_decode($imagebase64);
        //substr返回字符串的部分
        //strrpos() 函数查找字符串在另一个字符串中最后一次出现的位置。strrpos(string,find,start)
        $filename = 'img/'.date('YmdHis').substr(microtime(), 2, 3).'.jpg';
        // $urlhost = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')+1);
        $urlhost = base_url();

       
      
       


        if (!write_file($filename, $imagefile, 'w+')) {
            $errorMsg = "上传失败";
            return false;
        }
        else {


            $dataArray = array('path' => $filename, 'url' => $urlhost.$filename);
            return $dataArray;
        }

    }

    function imgreport($imagebase64){
        $CI =& get_instance();
        $CI->load->helper('file');
        $imgdata=array(
                'path'=>'',
                'url'=>'',
            );
        // $imgbase64 = explode(",", $imagebase64);
        for($i=0;$i<count($imgbase64);$i++){

            $imagefile = base64_decode($imgbase64[$i]);

            $filename = 'img/'.date('YmdHis').substr(microtime(), 2, 3).'.jpg';

            $urlhost = base_url();

            if (!write_file($filename, $imagefile, 'w+')) {
                $errorMsg = "上传失败";
                return false;
                break;
            }else {
                $imgdata[$i]['path'].=$filename;
                $imgdata[$i]['url'].=$urlhost.$filename;
            }
        } 
        log_message('info',print_r($imgdata, 1));
       

    }


   
}