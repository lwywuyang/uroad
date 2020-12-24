<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Etcsubscribetransact_model extends CI_Model
{
    /**
     * 查询列表数据
     */
    public function selectListData($data, $pagerOrder = "")
    {
        $sql = "SELECT a.*,b.name color,c.name bank
                FROM etc_applycard a
                LEFT JOIN gde_dict b ON a.colorno=b.dictcode 
                LEFT JOIN gde_dict c ON a.bankno=c.dictcode 
                WHERE 1=1";
        $params = array();
        if (!isEmpty($data['startTime'])) {
            $data['startTime'] .= ' 00:00:00';
            $sql .= ' and UNIX_TIMESTAMP(a.createtime) >= UNIX_TIMESTAMP(?)';
            array_push($params, $data['startTime']);
        }
        if (!isEmpty($data['endTime'])) {
            $data['endTime'] .= ' 23:59:59';
            $sql .= ' and UNIX_TIMESTAMP(a.createtime) <= UNIX_TIMESTAMP(?)';
            array_push($params, $data['endTime']);
        }
        if (!isEmpty($data['bankno'])) {
            $sql .= " and bankno=?";
            array_push($params, $data['bankno']);
        }
        if (!isEmpty($data['keyword'])) {
            $sql .= " and (a.name like concat('%',?,'%') or a.address like concat('%',?,'%'))";
            array_push($params, $data['keyword']);
            array_push($params, $data['keyword']);
        }
        $sql .= " ORDER BY id DESC";
        if ($pagerOrder) {
            $res['data'] = $this->mysqlhelper->QueryPage($sql, $params, $pagerOrder);
            $res['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql, $params, $pagerOrder);
        } else {
            $res['data'] = $this->mysqlhelper->QueryParams($sql, $params);
        }
        return $res;
    }

    /**
     * 查询车牌颜色
     */
    public function selectPlateColor()
    {
        $sql = "SELECT dictcode code,name FROM gde_dict WHERE codetype='2007'";
        return $this->mysqlhelper->Query($sql);
    }

    /**
     * 查询银行
     */
    public function selectBank()
    {
        $sql = "SELECT dictcode code,name FROM gde_dict WHERE codetype='2008'";
        return $this->mysqlhelper->Query($sql);
    }

    /**
     * 查询详情数据
     */
    public function selectDetails($data)
    {
        $sql = "SELECT * FROM etc_applycard WHERE id=?";
        return $this->mysqlhelper->QueryParams($sql, array($data['id']));
    }

    /**
     * 保存更新
     */
    public function updateDetails($data, $id)
    {
        $this->db->where("id", $id);
        return $this->db->update("etc_applycard", $data);
    }
}
