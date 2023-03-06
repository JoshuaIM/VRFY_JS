<?php
class Main extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        
        $this->load->library('Common_func');
        $this->load->library('Tstbcommon_func');
        $this->load->library('Tsgrdcommon_func');
        $this->load->library('Mapcommon_func');
    }
    
    private $datafile_dir = "./data/DFSD/";
    private $pngfile_dir = "./GIFD/";
    
    private $bangjae_arr = ["SPRING", "WINTER"];

    public function index(){
        $this->shrt_ts_stn();

        //TODO : 2022-08-17 로그인 추가
        // if(@$this->session->userdata('logged_in')==TRUE) {
        //     $this->shrt_ts_stn();
        // } else {
        //     redirect("/auth");
        // }


    }
    
    
//======= 지점: 단기 시계열 + 집계표   ===========================
    public function shrt_ts_stn($page = 'ts_stn'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_ts_stn';
        $dataHead = "DFS_SHRT_STN_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY"; 
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        // 방재기간(여름철, 겨울철) 추가
        foreach( $this->bangjae_arr as $br ) {
            $data_to_template[$br] = $this->getBangjaeYear("SHRT", $br);
        }

        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        // $data_to_template['varArray'] = ["T3H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "RN6", "SN3", "SN6"];
        // $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "RN6", "SN3", "SN6"];
        //$data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "RN6", "SN1", "SN6"];
        $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN1"];
        // $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "3시간 강수량", "6시간 강수량", "3시간 적설", "6시간 적설"];
        $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//======= 지점: 중기 시계열 + 집계표  ===========================
    public function medm_ts_stn($page = 'ts_stn'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "TMX";
        $vrfyType = 'medm_ts_stn';
        $dataHead = "DFS_MEDM_STN_";
        
        $dataPath = $this->datafile_dir . "MEDM/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("medm");
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["TMX", "TMN", "SKY", "PTY", "POP", "R12", "S12"];
        $data_to_template['varnameArray'] = ["최고기온", "최저기온", "하늘상태", "강수유무", "강수확률", "12시간 강수량", "12시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//=======  지점: 단기 공간분포  ===========================
    public function shrt_map_stn($page = 'map_stn'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_map_stn';
        $dataHead = "DFS_SHRT_STN_";
        
        $dataPath = $this->pngfile_dir . "SHRT";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        //$data_to_template['varArray'] = ["T3H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "RN6", "SN3", "SN6"];
        //$data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "3시간 강수량", "6시간 강수량", "3시간 적설", "6시간 적설"];
        $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN1"];
        $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//=======  지점: 중기 공간분포  ===========================
    public function medm_map_stn($page = 'map_stn'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "TMX";
        $vrfyType = 'medm_map_stn';
        $dataHead = "DFS_MEDM_STN_";
        
        $dataPath = $this->pngfile_dir . "MEDM";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("medm");
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["TMX", "TMN", "SKY", "PTY", "POP", "R12", "S12"];
        $data_to_template['varnameArray'] = ["최고기온", "최저기온", "하늘상태", "강수유무", "강수확률", "12시간 강수량", "12시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//======= 격자: 단기 시계열 + 집계표  ===========================
    public function shrt_ts_grd($page = 'ts_grd'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_ts_grd';
        $dataHead = "DFS_SHRT_GRD_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["T3H", "REH"];
        $data_to_template['varnameArray'] = ["기온", "습도"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//=======  격자: 단기 공간분포  ===========================
    public function shrt_map_grd($page = 'map_stn'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_map_grd';
        $dataHead = "DFS_SHRT_GRD_";
        
        $dataPath = $this->pngfile_dir . "SHRT";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["T3H", "REH"];
        $data_to_template['varnameArray'] = ["기온", "습도"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//======= 지점(산악): 단기 시계열 + 집계표   ===========================
    public function ssps_shrt_ts_stn($page = 'ssps_ts_stn'){
        if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        // $varName = "PTY";
        $vrfyType = 'ssps_shrt_ts_stn';
        $dataHead = "DFS_SHRT_STN_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY"; 
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getSspsTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        // $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN1"];
        // $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "시간 적설"];
        $data_to_template['varArray'] = ["T1H", "REH", "VEC", "WSD", "PTY", "RN1"];
        $data_to_template['varnameArray'] = ["기온", "습도",  "풍향", "풍속", "강수유무", "시간 강수량"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/mainTemplate', $data_to_template);
    }
    
    
//=======  지점: 단기 공간분포  ===========================
public function ssps_shrt_map_stn($page = 'ssps_map_stn'){
    if ( ! file_exists(APPPATH.'views/main/'.$page.'.php')){
        show_404();
    }
    
    $varName = "T3H";
    $vrfyType = 'ssps_shrt_map_stn';
    $dataHead = "DFS_SHRT_STN_";
    
    $dataPath = $this->pngfile_dir . "SHRT";
    $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
    
    $data_to_template['dataHead'] = $dataHead;
    
    // $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
    
    $data_to_template['dateType'] = "month";
    $data_to_template['vrfyType'] = $vrfyType;
    $data_to_template['vrfyTypeName'] = $this->common_func->getSspsTypeName($vrfyType);
    
    $data_to_template['varName'] = $varName;
    //$data_to_template['varArray'] = ["T3H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "RN6", "SN3", "SN6"];
    //$data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "3시간 강수량", "6시간 강수량", "3시간 적설", "6시간 적설"];
    // $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN1"];
    // $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "시간 적설"];
    $data_to_template['varArray'] = ["T1H", "REH", "VEC", "WSD", "PTY", "RN1"];
    $data_to_template['varnameArray'] = ["기온", "습도",  "풍향", "풍속", "강수유무", "시간 강수량"];
    $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
    
    $this->load->view('common/mainTemplate', $data_to_template);
}


//=========================================================================
//=======  사용 메서드      ======================================================
//=========================================================================

// View에서 Ajax로 호출하여 요소(변수)별 검증지수 배열 찾기. "getVrfyTech()" 메서드 호출.
    public function callVrfyTech() {
        $varName = $this->input->post('varName');
        $vrfyTech = $this->common_func->getVrfyTech($varName);
        
        echo json_encode($vrfyTech);
    }

//=========================================================================
//====== 지점 - 시계열 메서드 [예측기간(월별)]  ======================================
//=========================================================================
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
// 2. 선택 월의 범위 월 얻기. 범위 월 구하는 메서드 호출 -> "getDateRangeArr()"
// 3. 파일명 조합. 파일 명 생성 메서드 호출 -> "getFileNames()"
    public function getStnFcstData() {
        
        $data_head = $this->input->post('data_head');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $location = $this->input->post('location');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');
        // $map_type = $this->input->post('map_type');
        
        //$fdir = "./data/DFSD/SHRT/VRFY";
        $fd = $this->datafile_dir;
        $dhead_arr = explode("_", $data_head);
        // SHRT or MEDM
        $dtype = $dhead_arr[1];
        $fdir = $fd . $dtype . "/VRFY";
        
        $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init, $init_hour);
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $data_head,
            'var_select' => $var_select,
            'model_sel' => $model_sel,
            'rangeMon' => $rangeMon,
            'vrfy_idx' => $vrfy_idx,
            'location' => $location,
            // 'map_type' => $map_type
        ];
        
        if( $location[0] == "mean" ) {
            $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
            
            $fnParam['location'] = ["mean"];
            // 표출하기 쉽게 데이터 정리.
            $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);

        } else {
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
        
        //$fdir = "./data/DFSD/SHRT/VRFY";
        $fd = $this->datafile_dir;
        $dhead_arr = explode("_", $data_head);
        // SHRT or MEDM
        $dtype = $dhead_arr[1];
        $fdir = $fd . $dtype . "/VRFY";
        
        $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init, $init_hour);
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $this->input->post('data_head'),
            'var_select' => $this->input->post('var_select'),
            'model_sel' => $this->input->post('model_sel'),
            'rangeMon' => $rangeMon,
            'init_hour' => $init_hour,
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



//=========================================================================
//====== 지점 - 시계열 메서드 [방재기간]  ===================================
//=========================================================================
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
// 2. 선택 월의 범위 월 얻기. 범위 월 구하는 메서드 호출 -> "getDateRangeArr()"
// 3. 파일명 조합. 파일 명 생성 메서드 호출 -> "getFileNames()"
// public function getStnBangjaeData() {
        
//     $data_head = $this->input->post('data_head');
//     $var_select = $this->input->post('var_select');
//     $init_hour = $this->input->post('init_hour');
//     $model_sel = $this->input->post('model_sel');
//     $location = $this->input->post('location');
//     $peri = $this->input->post('peri');
//     $bangjae_year = $this->input->post('bangjae_year');
//     $vrfy_idx = $this->input->post('vrfy_idx');
    
//     //$fdir = "./data/DFSD/SHRT/VRFY";
//     $fd = $this->datafile_dir;
//     $dhead_arr = explode("_", $data_head);
//     // SHRT or MEDM
//     $dtype = $dhead_arr[1];
//     $fdir = $fd . $dtype . "/" . $peri;
    
//     $rangeMon = array();
//         $rangeYear = ($peri == "SPRING") ? $bangjae_year . "051500_" . $bangjae_year . "101500" : $bangjae_year . "051500_" . $bangjae_year . "101500";
//         array_push($rangeYear, $rangeMon);
    
//     $fnParam = [
//         'dir_head' => $fdir,
//         'data_head' => $data_head,
//         'var_select' => $var_select,
//         'model_sel' => $model_sel,
//         'rangeMon' => $rangeMon,
//         'vrfy_idx' => $vrfy_idx,
//         'location' => $location,
//         'map_type' => $map_type
//     ];
    
//     if( $location[0] == "mean" ) {
//         $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
        
//         $fnParam['location'] = ["mean"];
//         // 표출하기 쉽게 데이터 정리.
//         $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);

//     } else {
//         $allTargData = $this->tstbcommon_func->getFcstData($fnParam);
//         // 표출하기 쉽게 데이터 정리.
//         $finalData = $this->tstbcommon_func->arrangeFcstData($allTargData, $fnParam);
//     }
    
//     echo json_encode($finalData);
// }


    
//=========================================================================
//====== 격자 - 시계열 메서드 [예측기간(월별)]  ======================================
//=========================================================================
// 예측기간(월별) 선택 시. [ 단기 only, 중기는 격자 데이터 없음. ]
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
    public function getGrdFcstGrph() {
        
        //$data_head = $this->input->post('data_head');
        // TODO : 지점 자료의 예보시간을 읽어내는게 자료 표출 시 시간이 절약될 것 같아서 임시로 사용. (단, 지점자료의 예보시간 범위와 격자자료의 예보시간 범위가 같을 시)
        // TODO : 격자 자료는 10줄 아래로 149/253개의 자료가 씌어진 후 예보시간이 적혀있고 "2018060100-2018063000+006HOUR" 이 기준으로 적혀 있어 파싱 후 표출이 힘들다.
        // TODO : 기존 시계열 표출 시 사용에 유용하게 지점 자료 데이터를 읽어서 사용하는 방안.

        $data_head = $this->input->post('data_head');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');
        $nx = (int)$this->input->post('nx');
        $ny = (int)$this->input->post('ny');
        $xy_point= $this->input->post('xy_point');
        
        $fd = $this->datafile_dir;
        $dhead_arr = explode("_", $data_head);
        // SHRT or MEDM
        $dtype = $dhead_arr[1];
        $fdir = $fd . $dtype . "/VRFY";
        
        $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init, $init_hour);
        
        $xyArr = $this->tsgrdcommon_func->calcXYCoordinate($xy_point, $nx, $ny);
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $data_head,
            // 지점자료(STN)의 예보시간이 적힌 헤드를 읽어 사용하기 위함.
            // TODO : 그냥 격자 데이터에서 뽑아 쓰기로 결정. 2019-10-16
            //'stn_head' => str_replace("GRD", "STN", $data_head),
            'var_select' => $this->input->post('var_select'),
            'model_sel' => $this->input->post('model_sel'),
            'rangeMon' => $rangeMon,
            'vrfy_idx' => $this->input->post('vrfy_idx'),
            'map_type' => $this->input->post('map_type'),
            'nx' => $nx,
            'ny' => $ny,
            'xyArr' => $xyArr
        ];
        
        $allTargData = $this->tsgrdcommon_func->getFcstData($fnParam);
        
        // 표출하기 쉽게 데이터 정리.
        $finalData = $this->tsgrdcommon_func->arrangeFcstData($allTargData, $fnParam);
        
        echo json_encode($finalData);
    }
    
    
//=========================================================================
//====== 지점 - 공간분포 메서드 [예측기간(월별)]  =====================================
//=========================================================================
// 공간분포 표출 시 예측시간 정보를 데이터로부터 가져오기(변수별로 예측시간 정보가 다르다).
    public function getStnFcstNum() {
        
        $data_head = $this->input->post('data_head');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');
        $peri = $this->input->post('peri');
        
        $fd = $this->datafile_dir;
        $dhead_arr = explode("_", $data_head);
        // SHRT or MEDM
        $dtype = $dhead_arr[1];
        $fdir = $fd . $dtype . "/VRFY";
        
        $rangeMon = $this->common_func->getDateRangeArr($start_init, $end_init, $init_hour);
        
        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $data_head,
            'peri_type' => $peri,
            'var_select' => $var_select,
            'model_sel' => $model_sel,
            'rangeMon' => $rangeMon,
            'vrfy_idx' => $vrfy_idx
        ];
        
        // 데이터의 헤더정보 중 예보시간 읽어서 가져오는 함수.
        $fcst_info = $this->mapcommon_func->getFcstInfo($fnParam);
        
        $data_res = [
            'fcst_info' => $fcst_info,
            'date_info' => $rangeMon
        ];
        
        echo json_encode($data_res);
    }
    
    
    
    public function get_file_head_date($dateLine, $hourLine) {
        
        $yyyymmddhh = substr($dateLine, 0, 8) . " " . substr($dateLine, 8, 2) . ":00:00";
        
        $dt = new DateTime($yyyymmddhh);
        $dt->modify("+1 hour");
        
        
        return $dt->format('Y-m-d H');
    }
    


    // Can select Bangjae year that searching directory. 
    // $peri = SHRT or MEDM, $season = SPRING or WINTER (directory name)
    public function getBangjaeYear($peri, $season) {

        $dataPath = $this->datafile_dir . $peri . "/". $season;
        $yearArr = $this->common_func->getDirectoryYear($dataPath);

        return($yearArr);
    }




}


