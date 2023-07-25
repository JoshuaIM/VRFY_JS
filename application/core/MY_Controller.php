<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**
 * Class MY_Controller
 * @property 
 */
class MY_Controller extends CI_Controller {

    function __construct()
    {
        date_default_timezone_set('Asia/Seoul');
        parent::__construct();

        $this->load->library('Common_func');
        $this->load->library('Tstbcommon_func');
        $this->load->library('Tsgrdcommon_func');
        $this->load->library('Mapcommon_func');
        $this->load->library('Bangjae_func');

    }

    protected $datafile_dir = "./data/";

    protected $data_group_dir = "CALC/DAOU/";
    protected $grph_group_dir = "POST/GIFD/";

    protected $mon_dir = "MOND/";
    protected $bangjae_dir = "BNGJ/";
    protected $season_dir = "SEAS/";
    protected $allmonth_dir = "ALLD/";

    protected $pngfile_dir = "./GIFD/";

    protected $bangjae_season = [
        "05" => ["0515", "1015"],
        "11" => ["1115", "0315"]
    ];

    protected $allmonth_start = "20211201";
    
    protected $shrt_var_array = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN3"];
    protected $shrt_var_name_array = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "3시간 적설"];

    protected $medm_var_array = ["T3H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN3", "SN3"];
    protected $medm_var_name_array = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "3시간 강수량", "3시간 적설"];

    protected $ssps_var_array = ["T1H", "REH", "VEC", "WSD", "PTY", "RN1"];
    protected $ssps_var_name_array = ["기온", "습도",  "풍향", "풍속", "강수유무", "시간 강수량"];

    protected $gemd_var_array = ["T1H", "TMX", "TMN", "REH", "WSD", "SKY", "PTY", "RN1", "SN3"];
    protected $gemd_var_name_array = ["기온", "최고기온", "최저기온" ,"습도", "풍속", "하늘상태", "강수유무", "시간강수량", "3시간적설"];


    /**
     * $type : SHRT, MEDM, GEMD
     * $data_type : SHRT, MEDM
     * $sub_type : STN, ACCURACY
     **/
    protected function get_data_template( $type, $data_type, $sub_type )
    {
        $low_type = strtolower($type);
        $low_sub_type = strtolower($sub_type);

        if ( $type === "MEDM" )
        {
            $var_name = "T3H";
        }
        else
        {
            $var_name = "T1H";
        }

        $data_head = "DFS_" . $data_type . "_STN_";
        $data_path = $this->datafile_dir . $type ."/" . $this->data_group_dir . $this->mon_dir; 
        $vrfy_type = $low_type . "_ts_" . $low_sub_type;
        $bangjae_data_path = $this->datafile_dir . $type . "/" .  $this->data_group_dir . $this->bangjae_dir; 
        $bangjae_date = $this->common_func->getDateDirectoryArray($bangjae_data_path);
        $season_data_path = $this->datafile_dir . $type . "/" .  $this->data_group_dir . $this->season_dir; 
        $season_date = $this->common_func->getDateDirectoryArray($season_data_path);
        
        $data_to_template = array();
        $data_to_template['type'] = $type;
        $data_to_template['vrfyType'] = $vrfy_type;
        $data_to_template['dataHead'] = $data_head;
        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($data_path);

        if ( $low_sub_type === "accuracy" )
        {
            $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox("shrt");
            $data_to_template['vrfyTypeName'] = $this->common_func->getGemdTypeName($vrfy_type);
        }
        else
        {
            $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox($low_type);
            $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfy_type);
        }

        $data_to_template['dateType'] = "month";
        $data_to_template['varName'] = $var_name;

        if ( $type === "SHRT" )
        {
            $data_to_template['varArray'] = $this->shrt_var_array;
            $data_to_template['varnameArray'] = $this->shrt_var_name_array;
        }
        else if ( $type === "MEDM" )
        {
            $data_to_template['varArray'] = $this->medm_var_array;
            $data_to_template['varnameArray'] = $this->medm_var_name_array;
        }
        else if ( $type === "GEMD" )
        {
            $data_to_template['varArray'] = $this->gemd_var_array;
            $data_to_template['varnameArray'] = $this->gemd_var_name_array;
        }
        
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($var_name);

        $data_to_template['bangjaeDate'] = $bangjae_date;
        $data_to_template['seasonDate'] = $season_date;

        // YYYY:selectBox 와 MM(방재기간 또는 계절에 따라 naming 변경):selectBox 분리를 위해서
        $data_to_template['bangjaeArrMap'] = $this->bangjae_func->getDateSelctBoxArray($bangjae_date);
        $data_to_template['seasonArrMap'] = $this->bangjae_func->getDateSelctBoxArray($season_date);

        return $data_to_template;
    }


    // view에서 AJAX 호출
    // 데이터 READ 및 추출
    // 결과값 RETURN
    protected function get_ts_stn_data($post_data)
    {
        $data_head = $post_data['data_head'];
        $var_select = $post_data['var_select'];
        $init_hour = $post_data['init_hour'];
        $model_sel = $post_data['model_sel'];
        $location = $post_data['location'];
        $start_init = $post_data['start_init'];
        $range_date = $post_data['range_date'];
        $end_init = $post_data['end_init'];
        $vrfy_idx = $post_data['vrfy_idx'];
        $peri = $post_data['peri'];
        $type = $post_data['type'];

        /////////////////////////////////////////////////////////////////////
        // 00UTC+12UTC의 경우 00#12
        $infoUTC = array();
        foreach ( $init_hour as $utc )
        {
            $targUTC = explode("#" , $utc);
            array_push($infoUTC, $targUTC[0]);
        }
        /////////////////////////////////////////////////////////////////////

        $fdir = $this->get_data_directory_path($peri, $data_head, $type); 

        $range_mon = $this->get_month_range($peri, $start_init, $end_init, $range_date, $fdir, $var_select);

        $fnParam = [
            'dir_head' => $fdir,
            'data_head' => $data_head,
            'var_select' => $var_select,
            'infoUTC' => $infoUTC,
            'rangeMon' => $range_mon,
            'vrfy_idx' => $vrfy_idx,
            'location' => $location,
        ];

        if ( $type === "GEMD" ) 
        {
            $gemd_fdir = $this->get_data_directory_path($peri, $data_head, "GEMD_REAL");

            $fnParam['gemd_dir_head'] = $gemd_fdir;

            $model_gemd = array();
            array_push($model_gemd, "GEMD");
            foreach ( $model_sel as $m )
            {
                array_push($model_gemd, $m);
            }
            $fnParam['model_sel'] = $model_gemd;
        }
        else
        {
            $fnParam['model_sel'] = $model_sel;
        }

        // 데이터 수집 및 표출 위한 정리.
        $all_data = $this->get_data($peri, $fnParam);
        $arrange_data = $this->get_arrange_data($peri, $location[0], $all_data, $fnParam);
        
        return $arrange_data;
    }




    // 데이터 디렉터리 경로.
    protected function get_data_directory_path($peri, $data_head, $type)
    {
        $type_dir = "";
        if ( $type === "SSPS" )
        {
            $type_dir = "SSPS";
        }
        else if ( $type === "GEMD_REAL" )
        {
            $type_dir = "GEMD";
        }
        else
        {
            $data_head_exp_arr = explode("_", $data_head);
            $type_dir = $data_head_exp_arr[1];
        }

        $tail_dir = "";
        if ( $peri === "FCST" OR $peri === "MONTH" )
        {
            $tail_dir = $this->mon_dir;
        }
        else if ( $peri === "BANGJAE" )
        {
            $tail_dir = $this->bangjae_dir;
        }
        else if ( $peri === "SEASON" )
        {
            $tail_dir = $this->season_dir;
        }
        else if ( $peri === "ALLMONTH" )
        {
            $tail_dir = $this->allmonth_dir;
        }

        $fdir = $this->datafile_dir . $type_dir . "/" . $this->data_group_dir . $tail_dir; 
        return $fdir;
    }


    // 월 범위.
    protected function get_month_range($peri, $start_init, $end_init, $range_date, $fdir, $var_select)
    {
        $range_mon = array();
        if ( $peri === "BANGJAE" )
        {
            $range_mon = $this->bangjae_func->getDateBangjae($range_date, $this->bangjae_season);
        }
        else if ( $peri === "SEASON" )
        {
            $range_mon = $this->bangjae_func->getDateSeason($range_date);
        }
        else if ( $peri === "ALLMONTH" )
        {
            $range_mon = $this->common_func->getAllMonthDateRangeArr($fdir, $var_select, $this->allmonth_start);
        }
        else
        {
            $range_mon = $this->common_func->getDateRangeArr($start_init, $end_init);
        }

        return $range_mon;
    }


    // 데이터 추출.
    protected function get_data($peri, $file_param)
    {
        $data = array();
        if ( $peri === "MONTH" )
        {
            $data = $this->tstbcommon_func->getMonData($file_param);
        }
        else
        {
            $data = $this->tstbcommon_func->getFcstData($file_param);
        }
        return $data;
    }


    // 표출을 위해 데이터 정리 - 표출 순서 및 하나의 그래프 표출 OR 독립적인 그래프 표출 정리.
    protected function get_arrange_data($peri, $location, $data, $file_param)
    {
        $arrange_data = array();
        $param = $file_param;
        if ( $location === "mean" )
        {
            $param['location'] = ["mean"];
        }

        if ( $peri === "MONTH" )
        {
            $arrange_data = $this->tstbcommon_func->arrangeMonData($data, $param);
        }
        else
        {
            $arrange_data = $this->tstbcommon_func->arrangeFcstData($data, $param);
        }

        return $arrange_data;
    }





}

/* End of file MY_Controller.php */
/* Location: /application/core/MY_Controller.php */
