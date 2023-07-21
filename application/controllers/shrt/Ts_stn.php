<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: 
 * @property 
 * @property 
 */

class Ts_stn extends My_Controller {

    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->ts_stn();
    }

    public function ts_stn($page = 'ts_stn')
    {

        $varName = "T1H";
        $vrfyType = 'shrt_ts_stn';
        $dataHead = "DFS_SHRT_STN_";
        
        // ex) ./data/SHRT/CALC/DAOU/MOND/YYYYMM
        $dataPath = $this->datafile_dir . "SHRT/" . $this->data_group_dir . $this->mon_dir; 
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);

        // ex) ./data/SHRT/CALC/DAOU/BNGJ/YYYYMM
        $bangjaeDataPath = $this->datafile_dir . "SHRT/" .  $this->data_group_dir . $this->bangjae_dir; 
        $bangjae_date = $this->common_func->getDateDirectoryArray($bangjaeDataPath);
        $data_to_template['bangjaeDate'] = $bangjae_date;
        // YYYY:selectBox 와 MM(방재기간 또는 계절에 따라 naming 변경):selectBox 분리를 위해서
        $data_to_template['bangjaeArrMap'] = $this->bangjae_func->getDateSelctBoxArray($bangjae_date);
        
        // ex) ./data/SHRT/CALC/DAOU/SEAS/YYYYMM
        $seasonDataPath = $this->datafile_dir . "SHRT/" .  $this->data_group_dir . $this->season_dir; 
        $season_date = $this->common_func->getDateDirectoryArray($seasonDataPath);
        $data_to_template['seasonDate'] = $season_date;
        // YYYY:selectBox 와 MM(방재기간 또는 계절에 따라 naming 변경):selectBox 분리를 위해서
        $data_to_template['seasonArrMap'] = $this->bangjae_func->getDateSelctBoxArray($season_date);
        
        $data_to_template['dataHead'] = $dataHead;
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = $this->var_array;
        $data_to_template['varnameArray'] = $this->var_name_array;
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $data_to_template['main_content'] = "shrt/ts_stn";

        $this->load->view("templates/header", $data_to_template);
        $this->load->view("navigation/main_nav", $data_to_template);
        $this->load->view('shrt/import_js', $data_to_template); 
        $this->load->view('shrt/ts_stn', $data_to_template); 
        $this->load->view('shrt/top_options', $data_to_template); 
        $this->load->view('common/content');
        $this->load->view("templates/footer");
    }


    public function get_ts_stn_data()
    {
        $data_head = $this->input->post('data_head');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $location = $this->input->post('location');
        $start_init = $this->input->post('start_init');
        $range_date = $this->input->post('range_date');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');
        $peri = $this->input->post('peri');

        /////////////////////////////////////////////////////////////////////
        // 00UTC+12UTC의 경우 00#12
        $infoUTC = array();
        foreach ( $init_hour as $utc )
        {
            $targUTC = explode("#" , $utc);
            array_push($infoUTC, $targUTC[0]);
        }
        /////////////////////////////////////////////////////////////////////

        $data_head_exp_arr = explode("_", $data_head);
        $fdir = "";
        $range_mon = array();
            if ( $peri === "FCST" )
            {
                $type_dir = "";
                if ( $model_sel[0] === "SSPS" )
                {
                    $type_dir = "SSPS";
                }
                else
                {
                    $type_dir = $data_head_exp_arr[1];
                }

                $fdir = $this->datafile_dir . $type_dir . "/" . $this->data_group_dir . $this->mon_dir; 

                $range_mon = $this->common_func->getDateRangeArr($start_init, $end_init);
            }
            else if ( $peri === "BANGJAE" )
            {
                $fdir = $this->datafile_dir . $data_head_exp_arr[1] . "/" . $this->data_group_dir . $this->bangjae_dir;

                $range_mon = $this->bangjae_func->getDateBangjae($range_date, $this->bangjae_season);
            }
            else if ( $peri === "SEASON" )
            {
                $fdir = $this->datafile_dir . $data_head_exp_arr[1] . "/" . $this->data_group_dir . $this->season_dir; 

                $range_mon = $this->bangjae_func->getDateSeason($range_date);
            }
            else if ( $peri === "ALLMONTH" )
            {
                $fdir = $this->datafile_dir . $data_head_exp_arr[1] ."/" . $this->data_group_dir . $this->allmonth_dir; 

                $range_mon = $this->common_func->getAllMonthDateRangeArr($fdir, $var_select, $this->allmonth_start);
            }
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $data_head,
            'var_select' => $var_select,
            'model_sel' => $model_sel,
            'infoUTC' => $infoUTC,
            'rangeMon' => $range_mon,
            'vrfy_idx' => $vrfy_idx,
            'location' => $location,
        ];
        
        if ( $peri === "MONTH" )
        {
            if( $location[0] == "mean" ) {

                $allTargData = $this->tstbcommon_func->getMonData($fnParam);
    
                $fnParam['location'] = ["mean"];
                // 표출하기 쉽게 데이터 정리.
                $finalData = $this->tstbcommon_func->arrangeMonData($allTargData, $fnParam);
    
            } else {
    
                $allTargData = $this->tstbcommon_func->getMonData($fnParam);
                
                // 표출하기 쉽게 데이터 정리.
                $finalData = $this->tstbcommon_func->arrangeMonData($allTargData, $fnParam);
            }
            
            echo json_encode($finalData);
        }
        else
        {
            if( $location[0] == "mean" )
            {
                $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
                
                $fnParam['location'] = ["mean"];
                // 표출하기 쉽게 데이터 정리.
                $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);
            }
            else
            {
                $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
                // 표출하기 쉽게 데이터 정리.
                $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);
            }
            
            echo json_encode($finalData);
        }

    }




    public function getStnFcstData()
    {
        $data_head = $this->input->post('data_head');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $location = $this->input->post('location');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');

        $data_head_exp_arr = explode("_", $data_head);
        $type_dir = "";
        if( $model_sel[0] === "SSPS" )
        {
            $type_dir = "SSPS";
        }
        else
        {
            $type_dir = $data_head_exp_arr[1];
        }

        $fdir = $this->datafile_dir . $type_dir . "/" . $this->data_group_dir . $this->mon_dir; 
        
        /////////////////////////////////////////////////////////////////////
        // 00UTC+12UTC의 경우 00#12
        $infoUTC = array();
        foreach ( $init_hour as $utc )
        {
            $targUTC = explode("#" , $utc);
            array_push($infoUTC, $targUTC[0]);
        }
        /////////////////////////////////////////////////////////////////////
        
        // $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init, $init_hour);
        $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init);
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $data_head,
            'var_select' => $var_select,
            'model_sel' => $model_sel,
            'infoUTC' => $infoUTC,
            'rangeMon' => $rangeMon,
            'vrfy_idx' => $vrfy_idx,
            'location' => $location,
        ];
        
        if( $location[0] == "mean" )
        {
            $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
            
            $fnParam['location'] = ["mean"];
            // 표출하기 쉽게 데이터 정리.
            $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);
        }
        else
        {
            $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
            // 표출하기 쉽게 데이터 정리.
            $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);
        }
        
        echo json_encode($finalData);
    }



//=========================================================================
//====== 지점 - 시계열 메서드 [월별]  =============================================
//=========================================================================
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
// 2. 선택 월의 범위 월 얻기. 범위 월 구하는 메서드 호출 -> "getDateRangeArr()"
// 3. 파일명 조합. 파일 명 생성 메서드 호출 -> "getFileNames()"
    public function getStnMonthData() {
            
        $data_head = $this->input->post('data_head');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $location = $this->input->post('location');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');

        $data_head_exp_arr = explode("_", $data_head);
        $type_dir = "";
        if( $model_sel[0] === "SSPS" ) {
            $type_dir = "SSPS";
        } else {
            $type_dir = $data_head_exp_arr[1];
        }

        $fdir = $this->datafile_dir . $type_dir ."/" . $this->data_group_dir . $this->mon_dir; 

        /////////////////////////////////////////////////////////////////////
        // 00UTC+12UTC의 경우 00#12
        $infoUTC = array();
        foreach ( $init_hour as $utc ) {
            $targUTC = explode("#" , $utc);
            array_push($infoUTC, $targUTC[0]);
        }
        /////////////////////////////////////////////////////////////////////

        // $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init, $init_hour);
        $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init);
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $this->input->post('data_head'),
            'var_select' => $this->input->post('var_select'),
            'model_sel' => $this->input->post('model_sel'),
            'rangeMon' => $rangeMon,
            'infoUTC' => $infoUTC,
            'vrfy_idx' => $this->input->post('vrfy_idx'),
            'location' => $this->input->post('location'),
            'map_type' => $this->input->post('map_type')
        ];
        
        if( $location[0] == "mean" ) {

            $allTargData = $this->tstbcommon_func->getMonData($fnParam);

            $fnParam['location'] = ["mean"];
            // 표출하기 쉽게 데이터 정리.
            $finalData = $this->tstbcommon_func->arrangeMonData($allTargData, $fnParam);

        } else {

            $allTargData = $this->tstbcommon_func->getMonData($fnParam);
            
            // 표출하기 쉽게 데이터 정리.
            $finalData = $this->tstbcommon_func->arrangeMonData($allTargData, $fnParam);
        }
        
        echo json_encode($finalData);
    }
    














}

