<?php
class ArbitraryExpression extends CI_Controller {
    
    
    public function __construct(){
        parent::__construct();
        
        $this->load->library('Common_func');
        $this->load->library('Tstbcommon_func');
        $this->load->library('Tsgrdcommon_func');
        $this->load->library('Mapcommon_func');
    }
    
    private $datafile_dir = "./data/DFSD/";
    
    public function index(){
        $this-> arbi_shrt_ts_stn();
    }
    
    
//=======  임의기간(지점): 단기 시계열 + 집계표  ===========================
    public function arbi_shrt_ts_stn($page = 'arbi_ts_stn'){
        if ( ! file_exists(APPPATH.'views/arbitraryExpression/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H"; 
        $vrfyType = 'shrt_ts_stn';
        $dataHead = "DFS_SHRT_STN_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;

        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "arbi";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        // $data_to_template['varArray'] = ["T3H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "RN6", "SN3", "SN6"];
        // $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "3시간 강수량", "6시간 강수량", "3시간 적설", "6시간 적설"];
        $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN1"];
        $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/arbiTemplate', $data_to_template);
    }
    
    
//=======  임의기간(지점): 중기 시계열 + 집계표  ===========================
    public function arbi_medm_ts_stn($page = 'arbi_ts_stn'){
        if ( ! file_exists(APPPATH.'views/arbitraryExpression/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H"; 
        $vrfyType = 'medm_ts_stn';
        $dataHead = "DFS_MEDM_STN_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;

        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("medm");
        
        $data_to_template['dateType'] = "arbi";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["TMX", "TMN", "SKY", "PTY", "POP", "R12", "S12"];
        $data_to_template['varnameArray'] = ["최고기온", "최저기온", "하늘상태", "강수유무", "강수확률", "12시간 강수량", "12시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/arbiTemplate', $data_to_template);
    }

    
//=======  지점: 단기 공간분포  ===========================
    public function arbi_shrt_map_stn($page = 'arbi_map_stn'){
        if ( ! file_exists(APPPATH.'views/arbitraryExpression/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_map_stn';
        $dataHead = "DFS_SHRT_STN_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "arbi";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        // $data_to_template['varArray'] = ["T3H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "RN6", "SN3", "SN6"];
        // $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "3시간 강수량", "6시간 강수량", "3시간 적설", "6시간 적설"];
        $data_to_template['varArray'] = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN1"];
        $data_to_template['varnameArray'] = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/arbiTemplate', $data_to_template);
    }
    
    
//=======  지점: 중기 공간분포  ===========================
    public function arbi_medm_map_stn($page = 'arbi_map_stn'){
        if ( ! file_exists(APPPATH.'views/arbitraryExpression/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'medm_map_stn';
        $dataHead = "DFS_MEDM_STN_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("medm");
        
        $data_to_template['dateType'] = "arbi";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["TMX", "TMN", "SKY", "PTY", "POP", "R12", "S12"];
        $data_to_template['varnameArray'] = ["최고기온", "최저기온", "하늘상태", "강수유무", "강수확률", "12시간 강수량", "12시간 적설"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/arbiTemplate', $data_to_template);
    }
    
    
//=======  임의기간(격자): 단기 시계열 + 집계표  ===========================
    public function arbi_shrt_ts_grd($page = 'arbi_ts_grd'){
        if ( ! file_exists(APPPATH.'views/arbitraryExpression/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_ts_grd';
        $dataHead = "DFS_SHRT_GRD_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "arbi";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["T3H", "REH"];
        $data_to_template['varnameArray'] = ["기온", "습도"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/arbiTemplate', $data_to_template);
    }
    
    
//=======  임의기간(격자): 공간분포  ===========================
    public function arbi_shrt_map_grd($page = 'arbi_map_grd'){
        if ( ! file_exists(APPPATH.'views/arbitraryExpression/'.$page.'.php')){
            show_404();
        }
        
        $varName = "T3H";
        $vrfyType = 'shrt_map_grd';
        $dataHead = "DFS_SHRT_GRD_";
        
        $dataPath = $this->datafile_dir . "SHRT/VRFY";
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($dataPath);
        
        $data_to_template['dataHead'] = $dataHead;
        
        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
        
        $data_to_template['dateType'] = "arbi";
        $data_to_template['vrfyType'] = $vrfyType;
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfyType);
        
        $data_to_template['varName'] = $varName;
        $data_to_template['varArray'] = ["T3H", "REH"];
        $data_to_template['varnameArray'] = ["기온", "습도"];
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($varName);
        
        $this->load->view('common/arbiTemplate', $data_to_template);
    }
    

//=====================================================================================    
//=======  사용 메서드     ===================================================================
//=====================================================================================    

    
//=========================================================================
//====== 지점 - 시계열 메서드 [임의기간]  ==========================================
//=========================================================================
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
    public function getArbiStnFcstData() {

        $data_head = $this->input->post('data_head');
        $peri = $this->input->post('peri');
        $var_select = $this->input->post('var_select');
        $model_sel = $this->input->post('model_sel');
        $init_hour = $this->input->post('init_hour');
        $location = $this->input->post('location');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');

        $req_time = date("YmdHis");
        
        $dHead_arr = explode("_", $data_head);
        $dataType = $dHead_arr[1] . " " . $dHead_arr[2];
        
        $pHead_arr = explode("_", $peri);
        $periType = $pHead_arr[1];
        
        $model_arr_string = "";
            for( $md=0; $md<sizeof($model_sel); $md++ ) {
                // 마지막에 '#' 안찍기 위함.
                if( $md == sizeof($model_sel)-1 ) {
                    $model_arr_string .= $model_sel[$md];    
                } else {
                    $model_arr_string .= $model_sel[$md] . "#";    
                }
            }
            
        // 쉘로 보내는 파라메터에 대해 데이터가 모두 생산 된다고 가정하여 ( 안 만들어져 있으면 표출 자체가 안된다. ) 데이터 추출 시 파일명을 가져오기 위해 선언.
        // 기존 데이터 추출 및 가공하는 소스를 재사용하기 위해 같은 형식으로 만들어주기 위함.
        $rangeMon = array();
        $utc_arr_string = "";
            for( $utc=0; $utc<sizeof($init_hour); $utc++ ) {
                $tmp = explode("#", $init_hour[$utc]);    
                
                $tmp_utc = "";
                    if( $tmp[0] == $tmp[1] ) {
                        $tmp_utc = $tmp[0];
                        
                        $monInfo = [
                            'utc_info' => $tmp[0] . "UTC",
                            'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                        ];
                        array_push($rangeMon, $monInfo);
                        
                    } else {
                        $tmp_utc = $tmp[0] . $tmp[1];
                        
                        $monInfo = [
                            'utc_info' => $tmp[0] . "-" . $tmp[1] . "UTC",
                            'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                        ];
                        array_push($rangeMon, $monInfo);
                    }
                
                // 마지막에 '#' 안찍기 위함.
                if( $utc == sizeof($init_hour)-1 ) {
                    $utc_arr_string .= $tmp_utc;    
                } else {
                    $utc_arr_string .= $tmp_utc . "#";    
                }
            }
            
        $vrfy_arr_string = "";
            for( $vf=0; $vf<sizeof($vrfy_idx); $vf++ ) {
                // 마지막에 '#' 안찍기 위함.
                if( $vf == sizeof($vrfy_idx)-1 ) {
                    $vrfy_arr_string .= $vrfy_idx[$vf];
                } else {
                    $vrfy_arr_string .= $vrfy_idx[$vf] . "#";
                }
            }
            
// 파라메터 순서 : 1.호출시간(년월일시분초 단위)[$req_time], 2.단기(SHRT)or중기(MEDM)[$req_time], 3.지점(STN)or격자(GRD)[$req_time], 
//            4.발표(INIT)or발효(TARG)[$periType], 5.요소[$var_select], 
//            6.시작시간[$start_init], 7.끝시간[$end_init], 8.검증지수배열('#'으로 구분)[$vrfy_arr_string], 
//            9.초기시각배열('#'으로 구분)[$utc_arr_string], 10.모델-기법배열('#'으로 구분)[$model_arr_string], 
//            11.지점배열('#'으로 구분)[$loc_arr_string]

// TODO: 현업 시 주석 해제. ( 생산 프로그램과 연동 시 필요. )
        // $request_data_str = $req_time . " " . $dataType . " " . $periType. " " . $var_select . " " .  
        //                     $start_init . " " . $end_init . " " . $vrfy_arr_string . " " . 
        //                     $utc_arr_string . " " . $model_arr_string;
                            
// TODO: 현업 시 주석 해제. ( 생산 프로그램과 연동 시 필요. )
        //  $cmd = "csh /home/dfs/VRFY_SHEL/ts_shell.csh " . $request_data_str;
        //  $res = shell_exec($cmd);
        
        $dir_name = $this->datafile_dir . $dHead_arr[1] . "/VRFY/TEMP/STN/" . $var_select . "/";
        
// TODO: 현업 시 주석 해제. ( 생산 프로그램과 연동 시 필요. )
        // TODO: overwrite >> $data_head. 쉘 작동으로 수행 된 ID값(생산시각) 추가하여 overwrite  
        // $data_head = $req_time . "_" . $periType . "_" . $data_head;
        $fnParam = [
            'peri_type' => $periType,
            'dir_name' => $dir_name,
            'data_head' => $data_head,
            'vrfy_idx' => $vrfy_idx,
            'location' => $location,
            'rangeMon' => $rangeMon,
            'rangeInfo' => $start_init . " ~ " . $end_init,
            'model_sel' => $model_sel,
            'var_select' => $var_select
        ];
        
        $allTargData = $this->tstbcommon_func->getArbiData($fnParam);
        
        $finalData = $this->tstbcommon_func->arrangeArbiData($allTargData, $fnParam);
        
        echo json_encode( $finalData );
    }

    
    
//=========================================================================
//====== 지점 - 공간분포 메서드 [임의기간]  =========================================
//=========================================================================
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
    public function getArbiStnFcstNum() {
    
        $data_head = $this->input->post('data_head');
        $peri = $this->input->post('peri');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');
        
        // 현재시간(년월일시분초)을 파일 앞에 줘서 이 호출에 맞는 데이터가 나오는지를 구별한다.
        $req_time = date("YmdHis");

        // 단기or중기, 지점or격자
        $dHead_arr = explode("_", $data_head);
        $dataType = $dHead_arr[1] . " " . $dHead_arr[2];
        
        // 시작시간or목표시간
        $pHead_arr = explode("_", $peri);
        $periType = $pHead_arr[1];
        
        // 모델-기법 파라미터 만들기.
        $model_arr_string = "";
        for( $md=0; $md<sizeof($model_sel); $md++ ) {
            // 마지막에 '#' 안찍기 위함.
            if( $md == sizeof($model_sel)-1 ) {
                $model_arr_string .= $model_sel[$md];
            } else {
                $model_arr_string .= $model_sel[$md] . "#";
            }
        }
        // 쉘로 보내는 파라미터에 대해 데이터가 모두 생산 된다고 가정하여 ( 안 만들어져 있으면 표출 자체가 안된다. ) 데이터 추출 시 파일명을 가져오기 위해 선언.
        // 기존 데이터 추출 및 가공하는 소스를 재사용하기 위해 같은 형식으로 만들어주기 위함.
        $rangeMon = array();
        $utc_arr_string = "";
        for( $utc=0; $utc<sizeof($init_hour); $utc++ ) {
            $tmp = explode("#", $init_hour[$utc]);
            
            $tmp_utc = "";
            if( $tmp[0] == $tmp[1] ) {
                $tmp_utc = $tmp[0];
                
                $monInfo = [
                    'utc_info' => $tmp[0] . "UTC",
                    'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                ];
                array_push($rangeMon, $monInfo);
                
            } else {
                $tmp_utc = $tmp[0] . $tmp[1];
                
                $monInfo = [
                    'utc_info' => $tmp[0] . "-" . $tmp[1] . "UTC",
                    'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                ];
                array_push($rangeMon, $monInfo);
            }
            
            // 마지막에 '#' 안찍기 위함.
            if( $utc == sizeof($init_hour)-1 ) {
                $utc_arr_string .= $tmp_utc;
            } else {
                $utc_arr_string .= $tmp_utc . "#";
            }
        }
        
        // 검증지수 파라메터 만들기.
        $vrfy_arr_string = "";
        for( $vf=0; $vf<sizeof($vrfy_idx); $vf++ ) {
            // 마지막에 '#' 안찍기 위함.
            if( $vf == sizeof($vrfy_idx)-1 ) {
                $vrfy_arr_string .= $vrfy_idx[$vf];
            } else {
                $vrfy_arr_string .= $vrfy_idx[$vf] . "#";
            }
        }
        
// 파라메터 순서 : 1.호출시간(년월일시분초 단위)[$req_time], 2.단기(SHRT)or중기(MEDM)[$req_time], 3.지점(STN)or격자(GRD)[$req_time],
//            4.발표(ANN)or발효(TARGET)[$periType], 5.요소[$var_select],
//            6.시작시간[$start_init], 7.끝시간[$end_init], 8.검증지수배열('#'으로 구분)[$vrfy_arr_string],
//            9.초기시각배열('#'으로 구분)[$utc_arr_string], 10.모델-기법배열('#'으로 구분)[$model_arr_string],
//            11.지점배열('#'으로 구분)[$loc_arr_string]
        
// TODO: 현업 시 주석 해제. ( 생산 프로그램과 연동 시 필요. )
        // 데이터 생산 시 지점은 필요 없다.
        // $request_data_str = $req_time . " " . $dataType . " " . $periType. " " . $var_select . " " .
        //     $start_init . " " . $end_init . " " . $vrfy_arr_string . " " .
        //     $utc_arr_string . " " . $model_arr_string;
            
// TODO: 현업 시 주석 해제. ( 생산 프로그램과 연동 시 필요. )
        // $cmd = "csh /home/dfs/VRFY_SHEL/map_shell.csh " . $request_data_str . " > /op/DFSM_GRPH/VRFY/log";
        // $cmd = "csh /home/dfs/VRFY_SHEL/map_shell.csh " . $request_data_str;
        // $res = shell_exec($cmd);
        
	$dir_name = $this->datafile_dir . $dHead_arr[1] . "/VRFY/TEMP/STN/" . $var_select . "/";


// TODO: 현업 시 주석 해제. ( 생산 프로그램과 연동 시 필요. )
        // TODO: overwrite >> $data_head. 쉘 작동으로 수행 된 ID값(생산시각) 추가하여 overwrite  
        // $data_head = $req_time . "_" . $periType . "_" . $data_head;
        $fnParam = [
            'peri_type' => $periType,
            'dir_name' => $dir_name,
            'data_head' => $data_head,
            'vrfy_idx' => $vrfy_idx,
            'rangeMon' => $rangeMon,
            'model_sel' => $model_sel,
            'var_select' => $var_select
        ];
        
        // 만들어진 데이터를 읽어서 헤더의 예측시간 데이터 받아오기.
        $fcst_info = $this->mapcommon_func->getArbiInfo($fnParam);
        
        $data_res = [
            'tmp_fnParam' => $fnParam,

            'fcst_info' => $fcst_info,
            'rangeInfo' => $start_init . " ~ " . $end_init,
            'date_info' => $rangeMon,
            'arbi_data_head' => $data_head
        ];
        
        echo json_encode($data_res);
    }
       
    
        
//=========================================================================
//====== 격자 - 시계열 메서드 [예측기간(월별)]  ======================================
//=========================================================================
// 임의기간 선택 시. [ 단기 only, 중기는 격자 데이터 없음. ]
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
    public function getGrdArbiGrph() {
        
        //$data_head = $this->input->post('data_head');
        // TODO : 지점 자료의 예보시간을 읽어내는게 자료 표출 시 시간이 절약될 것 같아서 임시로 사용. (단, 지점자료의 예보시간 범위와 격자자료의 예보시간 범위가 같을 시)
        // TODO : 격자 자료는 10줄 아래로 149/253개의 자료가 씌어진 후 예보시간이 적혀있고 "2018060100-2018063000+006HOUR" 이 기준으로 적혀 있어 파싱 후 표출이 힘들다.
        // TODO : 기존 시계열 표출 시 사용에 유용하게 지점 자료 데이터를 읽어서 사용하는 방안.
        
        $data_head = $this->input->post('data_head');
        $peri = $this->input->post('peri');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');
        $nx = (int)$this->input->post('nx');
        $ny = (int)$this->input->post('ny');
        $xy_point= $this->input->post('xy_point');
        
        $req_time = date("YmdHis");

        //$fdir = "./data/DFSD/SHRT/VRFY";
        $fd = $this->datafile_dir;
        
        $dHead_arr = explode("_", $data_head);
        $dataType = $dHead_arr[1] . " " . $dHead_arr[2];
        
        $pHead_arr = explode("_", $peri);
        $periType = $pHead_arr[1];
        
        $dir_name = $fd . $dHead_arr[1] . "/VRFY/TEMP/GRD/" . $var_select . "/";
        
        
    // 격자 데이터는 만들때 시간이 걸리고 지점 찍을때마다 같은 파일을 읽기 때문에 데이터 생산 전에 파일이 존재하는지 확인 후 없는 것만 생산하도록 하기 위해 
    // 데이터 이름을 만들어서 확인 작업 해본다.
    // 데이터 확인 후 존재하지 않는 변수들에 대해 다시 배열을 만들어서 넣어둔다. 데이터 생산 쉘에 파라미터값을 보내기 위함.
        $vrfy_arr = array();
        $utc_arr = array();
        $modl_arr = array();
        // TEST
        $notExist_dataNames = array();
        foreach ($vrfy_idx as $v) {
            foreach ($init_hour as $u) {
                foreach ($model_sel as $m) {
                    
                    $tmp_utc = explode("#", $u);
                    
                    $tmpfn = $dir_name . $data_head . $m . '_' . $var_select . '_VRFY_' . $v . '.' . $start_init . $tmp_utc[0] . "_" . $end_init . $tmp_utc[1];

                    if( !file_exists($tmpfn) ) {
                        ( in_array($v, $vrfy_arr) ) ? : array_push($vrfy_arr, $v);
                        $find_u = $tmp_utc[0] . "#" . $tmp_utc[1];
                        ( in_array($find_u, $utc_arr) ) ? : array_push($utc_arr, $find_u);
                        ( in_array($m, $modl_arr) ) ? : array_push($modl_arr, $m);
                    }

                    //$tmpfn = $dir_name "\*_" . $peri . "_" . $data_head . $m . '_' . $var_select . '_VRFY_' . $v . '.' . $start_init . $tmp_utc[0] . "_" . $end_init . $tmp_utc[1];

		    //$exist_list = glob($tmpfn);
		    //if( !$exist_list ) {
                    //    ( in_array($v, $vrfy_arr) ) ? : array_push($vrfy_arr, $v);
                    //    $find_u = $tmp_utc[0] . "#" . $tmp_utc[1];
                    //    ( in_array($find_u, $utc_arr) ) ? : array_push($utc_arr, $find_u);
                    //    ( in_array($m, $modl_arr) ) ? : array_push($modl_arr, $m);
		    //} 

                }
            }
        }
        // TEST
        array_push($notExist_dataNames, $vrfy_arr);
        array_push($notExist_dataNames, $utc_arr);
        array_push($notExist_dataNames, $modl_arr);
        
        
        // 쉘로 보내는 파라메터에 대해 데이터가 모두 생산 된다고 가정하여 ( 안 만들어져 있으면 표출 자체가 안된다. ) 데이터 추출 시 파일명을 가져오기 위해 선언.
        // 기존 데이터 추출 및 가공하는 소스를 재사용하기 위해 같은 형식으로 만들어주기 위함.
        $model_arr_string = "";
        for( $md=0; $md<sizeof($modl_arr); $md++ ) {
            // 마지막에 '#' 안찍기 위함.
            if( $md == sizeof($modl_arr)-1 ) {
                $model_arr_string .= $modl_arr[$md];
            } else {
                $model_arr_string .= $modl_arr[$md] . "#";
            }
        }
        $utc_arr_string = "";
        for( $utc=0; $utc<sizeof($utc_arr); $utc++ ) {
            $tmp = explode("#", $utc_arr[$utc]);
            
            $tmp_utc = "";
            if( $tmp[0] == $tmp[1] ) {
                $tmp_utc = $tmp[0];
            } else {
                $tmp_utc = $tmp[0] . $tmp[1];
            }
            
            // 마지막에 '#' 안찍기 위함.
            if( $utc == sizeof($utc_arr)-1 ) {
                $utc_arr_string .= $tmp_utc;
            } else {
                $utc_arr_string .= $tmp_utc . "#";
            }
        }
        $vrfy_arr_string = "";
        for( $vf=0; $vf<sizeof($vrfy_arr); $vf++ ) {
            // 마지막에 '#' 안찍기 위함.
            if( $vf == sizeof($vrfy_arr)-1 ) {
                $vrfy_arr_string .= $vrfy_arr[$vf];
            } else {
                $vrfy_arr_string .= $vrfy_arr[$vf] . "#";
            }
        }
        
// 파라메터 순서 : 1.단기(SHRT)or중기(MEDM)[$req_time], 2.지점(STN)or격자(GRD)[$req_time],
//            3.발표(ANN)or발효(TARGET)[$periType], 4.요소[$var_select],
//            5.시작시간[$start_init], 6.끝시간[$end_init], 7.검증지수배열('#'으로 구분)[$vrfy_arr_string],
//            8.초기시각배열('#'으로 구분)[$utc_arr_string], 9.모델-기법배열('#'으로 구분)[$model_arr_string],
// 파라메터 순서 : 1.호출시간(년월일시분초 단위)[$req_time], 2.단기(SHRT)or중기(MEDM)[$req_time], 3.지점(STN)or격자(GRD)[$req_time],
//            4.발표(INIT)or발효(TARG)[$periType], 5.요소[$var_select],
//            6.시작시간[$start_init], 7.끝시간[$end_init], 8.검증지수배열('#'으로 구분)[$vrfy_arr_string],
//            9.초기시각배열('#'으로 구분)[$utc_arr_string], 10.모델-기법배열('#'으로 구분)[$model_arr_string],

//        $request_data_str = $dataType . " " . $periType. " " . $var_select . " " .
//            $start_init . " " . $end_init . " " . $vrfy_arr_string . " " .
//            $utc_arr_string . " " . $model_arr_string;

        $request_data_str = $req_time . " " . $dataType . " " . $periType. " " . $var_select . " " .
                            $start_init . " " . $end_init . " " . $vrfy_arr_string . " " .
                            $utc_arr_string . " " . $model_arr_string;
            
         $cmd = "csh /home/dfs/VRFY_SHEL/ts_shell.csh " . $request_data_str;
         $res = shell_exec($cmd);
        
        
        // 쉘로 보내는 파라메터에 대해 데이터가 모두 생산 된다고 가정하여 ( 안 만들어져 있으면 표출 자체가 안된다. ) 데이터 추출 시 파일명을 가져오기 위해 선언.
        // 기존 데이터 추출 및 가공하는 소스를 재사용하기 위해 같은 형식으로 만들어주기 위함.
        $rangeMon = array();
        for( $utc=0; $utc<sizeof($init_hour); $utc++ ) {
            $tmp = explode("#", $init_hour[$utc]);
            
            if( $tmp[0] == $tmp[1] ) {
                
                $monInfo = [
                    'utc_info' => $tmp[0] . "UTC",
                    'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                ];
                array_push($rangeMon, $monInfo);
                
            } else {
                
                $monInfo = [
                    'utc_info' => $tmp[0] . "-" . $tmp[1] . "UTC",
                    'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                ];
                array_push($rangeMon, $monInfo);
            }
            
        }
            
            
        $xyArr = $this->tsgrdcommon_func->calcXYCoordinate($xy_point, $nx, $ny);
        
        $data_head = $req_time . "_" . $periType . "_" . $data_head;
        $fnParam = [
            'dir_name' => $dir_name,
            'data_head' => $data_head,
            // 지점자료(STN)의 예보시간이 적힌 헤드를 읽어 사용하기 위함.
            // TODO : 그냥 격자 데이터에서 뽑아 쓰기로 결정. 2019-10-16
            //'stn_head' => str_replace("GRD", "STN", $data_head),
            'var_select' => $var_select,
            'model_sel' => $model_sel,
            'rangeMon' => $rangeMon,
            'rangeInfo' => $start_init . " ~ " . $end_init,
            'vrfy_idx' => $vrfy_idx,
            'nx' => $nx,
            'ny' => $ny,
            'xyArr' => $xyArr
            //'xy_point' => $xy_point
        ];
        
        $allTargData = $this->tsgrdcommon_func->getArbiData($fnParam);
        
        $finalData = $this->tsgrdcommon_func->arrangeArbiData($allTargData, $fnParam);
        
        
        echo json_encode($finalData);
    }

    
    
//=========================================================================
//====== 격자 - 공간분포 메서드 [임의기간]  =========================================
//=========================================================================
// 임의기간 선택 시. [ 단기 only, 중기는 격자 데이터 없음. ]
// 1. View에서 선택한 값(사용자 선택 값)을 모두 받음.
    public function getArbiGrbFcstNum() {
        
        $data_head = $this->input->post('data_head');
        $peri = $this->input->post('peri');
        $var_select = $this->input->post('var_select');
        $init_hour = $this->input->post('init_hour');
        $model_sel = $this->input->post('model_sel');
        $start_init = $this->input->post('start_init');
        $end_init = $this->input->post('end_init');
        $vrfy_idx = $this->input->post('vrfy_idx');

        $req_time = date("YmdHis");

        //$fdir = "./data/DFSD/SHRT/VRFY";
        $fd = $this->datafile_dir;
        
        $dHead_arr = explode("_", $data_head);
        $dataType = $dHead_arr[1] . " " . $dHead_arr[2];
        
        $pHead_arr = explode("_", $peri);
        $periType = $pHead_arr[1];
        
        $dir_name = $fd . $dHead_arr[1] . "/VRFY/TEMP/GRD/" . $var_select . "/";
        
        
        // 격자 데이터는 만들때 시간이 걸리고 지점 찍을때마다 같은 파일을 읽기 때문에 데이터 생산 전에 파일이 존재하는지 확인 후 없는 것만 생산하도록 하기 위해
        // 데이터 이름을 만들어서 확인 작업 해본다.
        // 데이터 확인 후 존재하지 않는 변수들에 대해 다시 배열을 만들어서 넣어둔다. 데이터 생산 쉘에 파라미터값을 보내기 위함.
        $vrfy_arr = array();
        $utc_arr = array();
        $modl_arr = array();
        // TEST ( 격자 데이터 확인 용 : 확인 후 데이터가 존재하면 생산하지 않기 위함 )
        $notExist_dataNames = array();
        foreach ($vrfy_idx as $v) {
            foreach ($init_hour as $u) {
                foreach ($model_sel as $m) {
                    
                    $tmp_utc = explode("#", $u);
                    
                    $tmpfn = $dir_name . $data_head . $m . '_' . $var_select . '_VRFY_' . $v . '.' . $start_init . $tmp_utc[0] . "_" . $end_init . $tmp_utc[1];
                    
                    if( !file_exists($tmpfn) ) {
                        
                        ( in_array($v, $vrfy_arr) ) ? : array_push($vrfy_arr, $v);
                        $find_u = $tmp_utc[0] . "#" . $tmp_utc[1];
                        ( in_array($find_u, $utc_arr) ) ? : array_push($utc_arr, $find_u);
                        ( in_array($m, $modl_arr) ) ? : array_push($modl_arr, $m);
                    }
                }
            }
        }
        // TEST ( 격자 데이터 확인 용 : 확인 후 데이터가 존재하면 생산하지 않기 위함 )
        array_push($notExist_dataNames, $vrfy_arr);
        array_push($notExist_dataNames, $utc_arr);
        array_push($notExist_dataNames, $modl_arr);
        
        //if( $notExist_dataNames[0] == null ) {

            // 쉘로 보내는 파라메터에 대해 데이터가 모두 생산 된다고 가정하여 ( 안 만들어져 있으면 표출 자체가 안된다. ) 데이터 추출 시 파일명을 가져오기 위해 선언.
            // 기존 데이터 추출 및 가공하는 소스를 재사용하기 위해 같은 형식으로 만들어주기 위함.
            $model_arr_string = "";
            for( $md=0; $md<sizeof($modl_arr); $md++ ) {
                // 마지막에 '#' 안찍기 위함.
                if( $md == sizeof($modl_arr)-1 ) {
                    $model_arr_string .= $modl_arr[$md];
                } else {
                    $model_arr_string .= $modl_arr[$md] . "#";
                }
            }
            $utc_arr_string = "";
            for( $utc=0; $utc<sizeof($utc_arr); $utc++ ) {
                $tmp = explode("#", $utc_arr[$utc]);
                
                $tmp_utc = "";
                if( $tmp[0] == $tmp[1] ) {
                    $tmp_utc = $tmp[0];
                } else {
                    $tmp_utc = $tmp[0] . $tmp[1];
                }
                
                // 마지막에 '#' 안찍기 위함.
                if( $utc == sizeof($utc_arr)-1 ) {
                    $utc_arr_string .= $tmp_utc;
                } else {
                    $utc_arr_string .= $tmp_utc . "#";
                }
            }
            $vrfy_arr_string = "";
            for( $vf=0; $vf<sizeof($vrfy_arr); $vf++ ) {
                // 마지막에 '#' 안찍기 위함.
                if( $vf == sizeof($vrfy_arr)-1 ) {
                    $vrfy_arr_string .= $vrfy_arr[$vf];
                } else {
                    $vrfy_arr_string .= $vrfy_arr[$vf] . "#";
                }
            }
            
            // 파라메터 순서 : 1.단기(SHRT)or중기(MEDM)[$req_time], 2.지점(STN)or격자(GRD)[$req_time],
            //            3.발표(ANN)or발효(TARGET)[$periType], 4.요소[$var_select],
            //            5.시작시간[$start_init], 6.끝시간[$end_init], 7.검증지수배열('#'으로 구분)[$vrfy_arr_string],
            //            8.초기시각배열('#'으로 구분)[$utc_arr_string], 9.모델-기법배열('#'으로 구분)[$model_arr_string],
            //$request_data_str = $dataType . " " . $periType. " " . $var_select . " " .
            //    $start_init . " " . $end_init . " " . $vrfy_arr_string . " " .
            //    $utc_arr_string . " " . $model_arr_string;


	    // 파라메터 순서 : 1.호출시간(년월일시분초 단위)[$req_time], 2.단기(SHRT)or중기(MEDM)[$req_time], 3.지점(STN)or격자(GRD)[$req_time],
	    //            4.발표(INIT)or발효(TARG)[$periType], 5.요소[$var_select],
	    //            6.시작시간[$start_init], 7.끝시간[$end_init], 8.검증지수배열('#'으로 구분)[$vrfy_arr_string],
	    //            9.초기시각배열('#'으로 구분)[$utc_arr_string], 10.모델-기법배열('#'으로 구분)[$model_arr_string],

			$request_data_str = $req_time . " " . $dataType . " " . $periType. " " . $var_select . " " .
                        $start_init . " " . $end_init . " " . $vrfy_arr_string . " " .
                        $utc_arr_string . " " . $model_arr_string;
                
			 //$cmd = "csh /home/dfs/VRFY_SHEL/map_shell.csh " . $request_data_str;        
			 //$cmd = "csh /home/dfs/VRFY_SHEL/map_shell.csh " . $request_data_str . " > /home/dfs/VRFY_SHEL/log";        
			 $cmd = "csh /home/dfs/VRFY_SHEL/map_shell.csh " . $request_data_str;        
                         $res = shell_exec($cmd);
                
        //}
                
        
        // 쉘로 보내는 파라메터에 대해 데이터가 모두 생산 된다고 가정하여 ( 안 만들어져 있으면 표출 자체가 안된다. ) 데이터 추출 시 파일명을 가져오기 위해 선언.
        // 기존 데이터 추출 및 가공하는 소스를 재사용하기 위해 같은 형식으로 만들어주기 위함.
        $rangeMon = array();
        for( $utc=0; $utc<sizeof($init_hour); $utc++ ) {
            $tmp = explode("#", $init_hour[$utc]);
            
            if( $tmp[0] == $tmp[1] ) {
                
                $monInfo = [
                    'utc_info' => $tmp[0] . "UTC",
                    'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                ];
                array_push($rangeMon, $monInfo);
                
            } else {
                
                $monInfo = [
                    'utc_info' => $tmp[0] . "-" . $tmp[1] . "UTC",
                    'range_mon' => $start_init . $tmp[0] . "_" . $end_init . $tmp[1]
                ];
                array_push($rangeMon, $monInfo);
            }
            
        }
        
        
        $stn_sInit = substr($start_init, 0, -2);
        $stn_eInit = substr($end_init, 0, -2);
        $stn_rangeMon = $this->common_func->getDateRangeArr($stn_sInit, $stn_eInit, $init_hour);
        
        $stn_dir_head = $fd . $dHead_arr[1] . "/VRFY";
        $stn_data_head = str_replace("GRD", "STN", $data_head);
        $stn_fnParam = [
            'dir_head' => $stn_dir_head,
            'data_head' => $stn_data_head,
            'peri_type' => $peri,
            'var_select' => $var_select,
            'model_sel' => $model_sel,
            'rangeMon' => $stn_rangeMon,
            'vrfy_idx' => $vrfy_idx,
        ];
        
        // 데이터의 헤더정보 중 예보시간 읽어서 가져오는 함수.
        // TODO: 일단 데이터의 예보시간을 배열로 만드는 메서드가 중복이라 tstb공통함수에 넣었다. 공간분포에서 사용하는 메서드가 많아지면 따로 사용 할 것.
        $fcst_info = $this->mapcommon_func->getFcstInfo($stn_fnParam);
        
        $data_res = [
            'fcst_info' => $fcst_info,
            'rangeInfo' => $start_init . " ~ " . $end_init,
            'date_info' => $rangeMon,
            'arbi_data_head' => $data_head,
            'data_info_comment' => "Use stn monthly data.",
            'data_info' => $stn_fnParam
        ];
        
            
        //echo json_encode($data_res);
        echo json_encode($data_res);
    }
    
    
    
    
    
}
