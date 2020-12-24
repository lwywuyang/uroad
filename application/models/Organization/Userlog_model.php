<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userlog_model extends CI_Model
{
  /**
   * Notes:获取全部的用户日志
   * User: henry
   * Date: 2020/4/14
   * Time: 13:50
   */
  public function getUserLogListAll($startTime, $endTime, $keyword, $pageOnload)
  {
    $sql = "SELECT 
            username as userName,
            ip,
            address,
            intime
            FROM `sys_iplog` where 1=1  ";
    $params = [];
    if (isset($startTime) && !!$startTime) {
      $startTime = $startTime . " 00:00:00";
      $sql .= ' and intime >= ? ';
      array_push($params, $startTime);
    }
    if (isset($endTime) && !!$endTime) {
      $endTime = $endTime . " 59:59:99";
      $sql .= ' and intime <= ? ';
      array_push($params, $endTime);
    }
    if (isset($keyword) && !!$keyword) {
      $sql .= " and userName like concat('%',?,'%')";
      array_push($params, $keyword);
    }
    $data['data'] = $this->mysqlhelper->QueryPage($sql, $params, $pageOnload);
    $data['pageOnload'] = $this->mysqlhelper->getPageOrder($sql, $params, $pageOnload);
    return $data;
  }
}
