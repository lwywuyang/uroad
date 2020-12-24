<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 栏目管理模型
 */
class Feedback_model extends CI_Model
{
    /**
     * 分页查找数据$key,$Status,$AdPictype,$txtstarttime,$txtendtime,$pageOnload
     */
    function getAdPicData($StartTime, $EndTime, $stateSel,$keyword, $pageOnload) {
        $sql = "select a.*,b.name typename,c.nickname FROM gde_feedback a join gde_dict b on a.type=b.dictcode join gde_user c on a.useruuid=c.useruuid WHERE 1 = 1";
        $params = array();
        if (!isEmpty($StartTime)) {
            $StartTime .= ' 00:00:00';
            $sql .= ' and UNIX_TIMESTAMP(intime) >= UNIX_TIMESTAMP(?)';
            array_push($params, $StartTime);
        }
        if (!isEmpty($EndTime)) {
            $EndTime .= ' 23:59:59';
            $sql .= ' and UNIX_TIMESTAMP(intime) <= UNIX_TIMESTAMP(?)';
            array_push($params, $EndTime);
        }
        if (!isEmpty($stateSel)) {
            $sql .= " and a.type = ?";
            array_push($params, $stateSel);
        }

        if(!isEmpty($keyword)){
            $sql .= " and (c.nickname=? or a.content=?)";
            array_push($params, $keyword);
            array_push($params, $keyword);
        }

        $data['data'] = $this->mysqlhelper->QueryPage($sql, $params, $pageOnload);
        $data['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql, $params, $pageOnload);

        return $data;

    }


    /**
     * 按照id查找数据
     */
    function checkAdPicData($id) {
        $sql = "select * from gde_ad where id = ?";
        return $this->mysqlhelper->GetRecordBySql($sql, array($id));
    }

    public function getDict($type) {
        $sql = 'SELECT dictcode,name FROM gde_dict where codetype=?';
        $data = $this->db->query($sql,[$type])->result_array();
        return $data;
    }

    public function getAdPicDataExcel($StartTime, $EndTime, $stateSel,$keyword) {
        $sql = "select 0 xh,c.nickname,a.intime,b.name typename,a.phone,a.images,a.content FROM gde_feedback a join gde_dict b on a.type=b.dictcode join gde_user c on a.useruuid=c.useruuid WHERE 1 = 1";
        $params = array();
        if (!isEmpty($StartTime)) {
            $StartTime .= ' 00:00:00';
            $sql .= ' and UNIX_TIMESTAMP(intime) >= UNIX_TIMESTAMP(?)';
            array_push($params, $StartTime);
        }
        if (!isEmpty($EndTime)) {
            $EndTime .= ' 23:59:59';
            $sql .= ' and UNIX_TIMESTAMP(intime) <= UNIX_TIMESTAMP(?)';
            array_push($params, $EndTime);
        }
        if (!isEmpty($stateSel)) {
            $sql .= " and a.type = ?";
            array_push($params, $stateSel);
        }

        if(!isEmpty($keyword)){
            $sql .= " and (c.nickname=? or a.content=?)";
            array_push($params, $keyword);
            array_push($params, $keyword);
        }
        $data = $this->db->query($sql,$params)->result_array();
        return $data;
    }

}