<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('TopEmpPerMenu')) {
    //输出管理下的事件,顶级事件
    function TopEmpPerMenu($empid){
        // 查找已经选择的功能
        $funs = CheckAllempFunPer($empid);

        $loadFun = array();
        $data = array();

        //一级菜单
        for ($i = 0; $i < count($funs); $i++) {
            // 取出顶级功能
            if($funs[$i]["PID"] == "" && !in_array($funs[$i]["FunctionID"], $loadFun)){

                // 压入顶级功能
                $funs[$i]["subfun"] = array();
                array_push($data, $funs[$i]);

                array_push($loadFun, $funs[$i]["FunctionID"]);
            }
        }

        //二级菜单
        //遍历顶级菜单
        for ($k = 0; $k < count($data); $k++) {
            for ($j = 0; $j < count($funs); $j++) {

                // 取出顶级对应的次级功能
                if($funs[$j]["PID"] == $data[$k]["ID"] && !in_array($funs[$j]["FunctionID"], $loadFun)){

                    array_push($data[$k]["subfun"], $funs[$j]);
                    array_push($loadFun, $funs[$j]["FunctionID"]);
                }
            }

            for ($a = 0; $a < count($data[$k]["subfun"]); $a++) {

                $data[$k]["subfun"][$a]["subfun"] = array();

                for ($b = 0; $b < count($funs); $b++) {

                    if($funs[$b]["PID"] == $data[$k]["subfun"][$a]["ID"] && !in_array($funs[$b]["FunctionID"], $loadFun)){

                        array_push($data[$k]["subfun"][$a]["subfun"], $funs[$b]);
                        array_push($loadFun, $funs[$b]["FunctionID"]);

                    }
                }
            }
        }

        return $data;
    }

    //取出后台的功能
    function CheckAllempFunPer($empid){
        $CI = & get_instance();
        $CI->load->model('Organization/function_model', 'logic');
        $data = $CI->logic->GetAllempFunPer($empid);
        return $data;
    }
}