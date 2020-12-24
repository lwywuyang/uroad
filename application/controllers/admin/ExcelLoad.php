<?php

class ExcelLoad extends CI_Controller {

    public function readTraff()
    {
        $this->load->helper('network');
        $this->load->database();
        // $url="http://localhost:9003/hunanEventServer/getEventList?name=uroad";
        $url="http://localhost:9003/hunanEventServer/getEventList?name=uroad";
        $result=network_get($url);

        echo $result;
    }


    public function test4(){
        $this->load->database();
        $data = $this->db->select('poiid, stationcode')->where('roadoldid > ', '96')->get('gde_roadpoi')->result_array();


        foreach ($data as $item) {
            $mile = $item['stationcode'];

            $mile = str_replace('K', '', $mile);

            $mile = str_replace('+', '.', $mile);

            $this->db->where('poiid', $item['poiid'])->update('gde_roadpoi', ['miles' => $mile]);
        }
    }

    public function readSession() {

        $redis = new Redis();
        try {
            $redis->connect('127.0.0.1', 6379);
        } catch (RedisException $e) {
            var_dump($e->getMessage());
            exit();
        }

        $system = [
            'name' => 'wyc',
        ];
        $redis->select(2);//选择数据库2
        $redis->set('Sys:Login:' . '1', $system, 20);
        $redis->set('Sys:Login:' . '2', $system);

        $data = $redis->get('Sys:Login:' . '1');

        echo json_encode($data);
    }

    function test1(){
        session_start();
        echo 123;
//        $key=$this->config->item('sessionkey');
//        echo $this->session->userdata($key."_EmplId");


//        echo $this->session->userdata($key."_EmplId");
//        if($CI->session->userdata($key."_EmplId")==''||$CI->session->userdata($key."_EmplId")==null||$CI->session->userdata($key."_EmplId")=='0'){
//            echo '<script>alert("登陆超时，请重新登陆");top.location.href="'.base_url('index.php/admin/login').'";</script>';
//            exit;
//        }
    }


    function  addTrafficsplit()
    {
//        gde_trafficsplit

        $this->load->database();
        $data = $this->db
            ->select('DISTINCT(trafficsplitcode), stationcode')
            ->where('trafficsplitcode !=', '')
            ->where('roadlineid >', '96')
            ->get('gde_roadlinestation')
            ->result_array();

        $insertData = [];
        foreach ($data as $item) {
            $insertData[] = [
                'trafficsplitcode' => $item['trafficsplitcode'],
                'instationcode' => $item['stationcode'],
                'outstationcode' => str_replace($item['stationcode'], '', $item['trafficsplitcode']),
                'miles' => 0,
                'trafficcolor' => 1008001
            ];
        }

        echo json_encode($insertData);
        exit;

        $this->db->insert_batch('gde_trafficsplit', $insertData);

    }

    public function getEventListFromGGJ()
    {
//        echo 132;
//        exit();
        $this->load->helper('network');
        $this->load->database();
        // $url="http://localhost:9003/hunanEventServer/getEventList?name=uroad";
        $url = "http://localhost:9003/hunanEventServer/getEventList?name=uroad";
        $result = network_get($url);

        echo $result;
    }

    function test()
    {
        echo phpinfo();
    }

    function addRoadPoiDetail()
    {
        $this->load->database();
        $pois = $this->db->select('poiid')->where('roadoldid > ', 95)->get('gde_roadpoi')->result_array(); //所有站点

        $poiId =array_column($pois, 'poiid');

        $data = [];
        foreach ($poiId as $item) {
            $data[] = [
                'poiid' => $item,
                'stationstatus' => 1
            ];
        }

        $this->db->insert_batch('gde_roadpoidetail', $data);
    }

    /**
     * 导入Excel（PHPExcel）
     */
    public function readGasuExcel()
    {
//        $this->load->database();
//        include_once './application/libraries/PHPExcel.php';
//        $inputFileName = './gaosu.xls';
//
//        $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
//        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
//        $objPHPExcel = $objReader->load($inputFileName);
//
//
//        // 确定要读取的sheet，什么是sheet，看excel的右下角，真的不懂去百度吧
//        //        //第一个表格
//        $sheet = $objPHPExcel->getSheet(1);
//        $highestRow = $sheet->getHighestRow();
//        $highestColumn = $sheet->getHighestColumn();
//
//        $data = [];
//        // 获取一行的数据
//        for ($row = 1; $row <= $highestRow; $row++) {
//            // Read a row of data into an array
//            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
//
//            $data = array_merge($data, $rowData);
//        }
//
//
//
//        $aHasData = [];
//        $aNoneData = [];
//        foreach ($data as $item) {
//            $result = $this->db->select('roadoldid, shortname')->where('shortname', $item[1])->where('newcode', $item['0'])->get('gde_roadold')->result_array();
//
//            if ($result) {
//                $aHasData[] = $item;
//            } else {
//                $aNoneData[] = $item;
//            }
//        }
//
//        $data = [
//            'h' => ($aHasData),
//            'n' => ($aNoneData)
//        ];
//        $this->db->insert('save_data', ['data' => json_encode($aNoneData)]);
//
//        echo json_encode($data);
//
//
//
//        exit();

        $this->load->database();
        $data = $this->db->where('id', 13)->get('save_data')->result_array();

        $data = json_decode($data[0]['data'], true);

        $insertData = [];
        //循环
        foreach ($data as $key => $item) {

            $saveData = [];

            $direction = $item[3];
            if (!empty($direction)) {
                $directions = explode('、', $direction);

                $tollData = [];

                if (count($directions) != 0) {
                    foreach ($directions as $k => $i) {
                        preg_match('/\(.*\)$/', $i, $match);

                        if (count($match) == 0) {
                            $tollData[] = [
                                'roadoldid' => 'id',
                                'name' => '',    //将括号去掉
                                'stationcode' => ''
                            ];
                        } else {
                            $tollData[] = [
                                'roadoldid' => 'id',
                                'name' => str_replace($match[0], '', $i),    //将括号去掉
                                'stationcode' => $this->delete($match[0])
                            ];
                        }

                    }
                }


                $saveData['save'] = $tollData;

            }


            $directions = explode('-', $item[2]);

            if (count($directions) == 2) {
                $saveData['direction1'] = $directions[0];
                $saveData['direction2'] = $directions[1];
            } else {
                $saveData['direction1'] = $directions[0];
                $saveData['direction2'] = $directions[0];
            }

            $saveData['shortname'] = $item[1];
            $saveData['newcode'] = $item[0];

            $insertData[] = $saveData;
        }


        $this->db->insert('save_data', ['data' => json_encode($insertData)]);

        echo json_encode($insertData);

    }


    public function addData()
    {
        $this->load->database();
        $data = $this->db->where('id', 13)->get('save_data')->result_array();

        $data = json_decode($data[0]['data'], true);

//        echo json_encode($data);
//
//        exit();

        $roadId = [];


        foreach ($data as $key => $item) {

            $inserRoadoldData = [
                'direction1' => $item['direction1'],
                'direction2' => $item['direction2'],
                'shortname' => $item['shortname'],
                'newcode' => $item['newcode']
            ];

            $this->db->insert('gde_roadold', $inserRoadoldData);

            $id = $this->db->insert_id();

            $roadId[] = $id;
            $poiData = [];
            if (isset($item['save'])) {
                $poiData = $item['save'];

                foreach ($poiData as $k => $i) {
                    $poiData[$k]['roadoldid'] = $id;
                    $poiData[$k]['status'] = 1010001;   //可用
                    $poiData[$k]['pointtype'] = 1002001;   //收费站
                    $poiData[$k]['seq'] = $k + 1;
                }

                $this->db->insert_batch('gde_roadpoi', $poiData);
            }

        }
//        echo json_encode($data);


        foreach ($roadId as $id) {
            $data = $this->db->select('poiid, roadoldid, name, stationcode')->where('roadoldid', $id)->order_by('seq asc')->get('gde_roadpoi')->result_array();

            $reverseData = array_reverse($data);

            $forwardArray = []; //正向路
            $reverseArray = []; //反向路
            if (count($data) == 0) {
                continue;
            } else {
                $num = count($data);

                for ($i = 0; $i < $num; $i++) {
                    $forwardArray[] = [
                        'roadlineid' => $data[$i]['roadoldid'],
                        'direction' => 1,
                        'seq' => ($i + 1),
                        'stationid' => $data[$i]['poiid'],
                        'stationcode' => $data[$i]['stationcode'],
                        'trafficsplitcode' => $this->setStationCode($i, $data)
                    ];
                    $reverseArray[] = [
                        'roadlineid' => $reverseData[$i]['roadoldid'],
                        'direction' => 2,
                        'seq' => ($i + 1),
                        'stationid' => $reverseData[$i]['poiid'],
                        'stationcode' => $reverseData[$i]['stationcode'],
                        'trafficsplitcode' => $this->setStationCode($i, $reverseData)
                    ];
                }

            }

            $this->db->insert_batch('gde_roadlinestation', $forwardArray);
            $this->db->insert_batch('gde_roadlinestation', $reverseArray);
        }


        echo json_encode(['成功']);
    }

    public function setStationCode($i, $data)
    {
        $res = '';
        //第一个，判断一下是否只有两个
        if (isset($data[$i +1])) {
            $res =  $data[$i]['stationcode'] . $data[$i +1]['stationcode'];
        }

        return $res;
    }

}