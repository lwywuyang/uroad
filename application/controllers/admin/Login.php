<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//检测常量是否定义
class Login extends CI_Controller {

	public function index(){
		$this->load->helper('cookie');

		$data = array(
			'username' => get_cookie('username'),
			'password' => get_cookie('password')
        );


		$this->load->view('admin/login',$data);
	}

	/**
	 * 登陆处理
	 */
	public function login_in(){
		$this->load->helper('cookie');

		$username = $this->input->post('username');
		$passwd = $this->input->post('password');

        $redisKey = 'Token:Login:'.$username;

        $redis = new Redis();
        try {
            $redis->connect('127.0.0.1', 6379);
        } catch (RedisException $e) {
            var_dump($e->getMessage());
            exit();
        }
        $redidsData = [
            'freezing_time' => time(),
            'num' => 0
        ];

        $userRedisInfo = $redis->get($redisKey);
        if ($userRedisInfo) {

            $userRedisInfo = json_decode($userRedisInfo, true);

            if (!$userRedisInfo['freezing_time']) {
                $redis->set($redisKey, json_encode($redidsData));
                $userRedisInfo = $redidsData;
            }

            if ($userRedisInfo['freezing_time'] > time()) {
                ajax_error('账号已被锁定，解封时间为' . date('Y-m-d H:i:s', $userRedisInfo['freezing_time']));
                exit();
            }

        } else {
            //初始的用户Data
            $redis->set($redisKey, json_encode($redidsData));

            $userRedisInfo = $redidsData;
        }

        $this->load->model('Organization/orgmanage_model', 'org');
		$userdata = $this->org->checkEmplCode($username);

		if(count($userdata) != 0 && $userdata[0]['PassWord'] == md5($passwd)){
			$redis->set($redisKey, json_encode($redidsData));
            $this->load->library('session');

            $key = $this->config->item('sessionkey');

            $this->session->set_userdata($key."_EmplId",$userdata[0]["ID"]);
            $this->session->set_userdata($key."_EmplCode",$userdata[0]["EmplCode"]);
            $this->session->set_userdata($key."_DepartmentID",$userdata[0]["DepartmentID"]);
            $this->session->set_userdata($key."_EmplName",$userdata[0]["EmplName"]);
            $this->session->set_userdata($key."_DepaName",$userdata[0]["DepaName"]);

            //存入cookie,以保存密码
            $this->input->set_cookie("username",$username,86400);
            $this->input->set_cookie("password",$passwd,86400);

            $this->queryIp($username);

            $data['success'] = true;
            ajax_success($data,null);

        }else{

            $userRedisInfo['num'] = $userRedisInfo['num'] + 1;

            //错5次就提示
            if ($userRedisInfo['num'] == 5) {
                $userRedisInfo['freezing_time'] = time() + 30 * 60;

                $redis->set($redisKey, json_encode($userRedisInfo));

                ajax_error('用户名或密码错误（连续输入5次错误的账号或密码将锁定30分钟）');
                exit();
            }

            //错10次后提示
            if ($userRedisInfo['num'] == 10) {
                $userRedisInfo['freezing_time'] = time() + 60 * 60 * 12;
                $userRedisInfo['num'] == 0;

                $redis->set($redisKey, json_encode($userRedisInfo));

                ajax_error('用户名或密码错误（连续输入10次错误的账号或密码将锁定12小时）');
                exit();
            }

            $redis->set($redisKey, json_encode($userRedisInfo));
            ajax_error('用户名或密码错误');
            exit();
		}

	}

	/**
	 * 退出登陆
	 */
	public function login_out(){
		//清楚session
		$this->load->library('session');

		$key=$this->config->item('sessionkey');

		$this->session->unset_userdata($key."_EmplId");
		$this->session->unset_userdata($key."_EmplCode");
		$this->session->unset_userdata($key."_DepartmentID");
		$this->session->unset_userdata($key."_DepaName");
		$this->session->unset_userdata($key."_EmplName");

		/*delete_cookie('username');
		delete_cookie('password');*/

		ajax_success('',NULL);

	}

	//修改密码
	public function editpassword(){
		$data['empid'] = $this->uri->segment(4);

		$this->load->view('admin/Organization/EditPassword',$data);
	}

	public function doeditpassword(){
		//提取前台数据
		$newpwd = md5($this->input->post('Newpwd'));

		$res = preg_match('/^(\w*(?=\w*\d)(?=\w*[A-Za-z])\w*){8,16}$/', $this->input->post('Newpwd'));
		if (!$res) {
			ajax_error('8-16位字符（英文/数字/符号）至少两种或下划线组合');
			exit;
		}

		$NewPassword = array(
			'ID' => $this->input->post('EmpID'),
			'PassWord' => $newpwd
		);

		$OldPwd = $this->input->post('OldPwd');
		// 查处id的旧密码
		$this->load->model('Organization/orgmanage_model','org');
		$userdata = $this->org->checkEmpId($NewPassword['ID']);

		if($userdata[0]['PassWord'] != md5($OldPwd)){
			ajax_error('旧密码错误');
		}else{
			/*delete_cookie('username');
			delete_cookie('password');*/

			$this->org->save('org_employee',$NewPassword,'ID');

			ajax_success($NewPassword,NULL);
		}
	}

	public function queryIp($username) {
		$this->load->helper('network');
		$ip = network_getclientip();
		if ($ip != '' && $ip != 'UNKNOWN') {
			$this->load->model('Organization/orgmanage_model','org');
			$url = 'http://ip.ws.126.net/ipquery';
			$result = network_get($url, array('ip' => $ip));
			if ($result) {
				$result = iconv("GB2312","UTF-8//IGNORE", $result);
				$ind = strpos($result, ';');
				if ($ind === FALSE) {
					
				} else {
					if ($ind >= 0) {
						$result = substr($result, 0, $ind);
						$result = str_replace("var lo=", '', $result);
						$result = str_replace(", lc=", '', $result);
						$result = str_replace("\"", '', $result);

						$this->org->saveIp($username, $ip, $result, NULL);
						return;
					}
				}
			}
			$this->org->saveIp($username, $ip, NULL, NULL);
		}
	}
}
