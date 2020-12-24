<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Etcquestion_model extends CI_Model
{
    /**
     * 查询列表数据
     */
    public function selectListData($data, $pagerOrder)
    {
        $sql = "SELECT * FROM gde_etcquestion WHERE 1=1";
        $params = array();
        if (!isEmpty($data['startTime'])) {
            $data['startTime'] .= ' 00:00:00';
            $sql .= ' and UNIX_TIMESTAMP(created) >= UNIX_TIMESTAMP(?)';
            array_push($params, $data['startTime']);
        }
        if (!isEmpty($data['endTime'])) {
            $data['endTime'] .= ' 23:59:59';
            $sql .= ' and UNIX_TIMESTAMP(created) <= UNIX_TIMESTAMP(?)';
            array_push($params, $data['endTime']);
        }
        if (!isEmpty($data['status'])) {
            $sql .= " and status=?";
            array_push($params, $data['status']);
        }
        if (!isEmpty($data['keyword'])) {
            $sql .= " and title like concat('%',?,'%')";
            array_push($params, $data['keyword']);
        }
        $sql .= " ORDER BY id DESC";
        $res['data'] = $this->mysqlhelper->QueryPage($sql, $params, $pagerOrder);
        $res['PagerOrder'] = $this->mysqlhelper->GetPageOrder($sql, $params, $pagerOrder);
        return $res;
    }

    /**
     * 更新状态
     */
    public function updateStatus($id, $data)
    {
        $this->db->where("id", $id);
        return $this->db->update("gde_etcquestion", $data);
    }

    /**
     * 更新问题
     */
    public function updateQuestion($id, $data)
    {
        $this->db->where("id", $id);
        return $this->db->update("gde_etcquestion", $data);
    }

    /**
     * 新增问题
     */
    public function insertQuestion($data)
    {
        return $this->db->insert("gde_etcquestion", $data);
    }

    /**
     * 查询问题详情
     */
    public function selectDetails($id)
    {
        $sql = "SELECT * FROM gde_etcquestion WHERE id=?";
        return $this->mysqlhelper->QueryParams($sql, array($id));
    }

    /**
     * 撤销
     */
    public function del($ids)
    {
        $this->db->trans_begin();
        $arr = explode(",", $ids);
        foreach ($arr as $v) {
            $this->db->where("id", $v);
            $this->db->delete("gde_etcquestion");
        }
        if ($this->db->trans_status() === FALSE) {
            return $this->db->trans_rollback();
        } else {
            return $this->db->trans_commit();
        }
    }
}
