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
    
    protected $var_array = ["T1H", "TMX", "TMN", "REH", "VEC", "WSD", "SKY", "PTY", "POP", "RN1", "SN3"];
    protected $var_name_array = ["기온", "최고기온", "최저기온", "습도",  "풍향", "풍속", "하늘상태", "강수유무", "강수확률", "시간 강수량", "3시간 적설"];



    protected function get_data_template( $type, $data_type )
    {
        $low_type = strtolower($type);

        if ( $type === "MEDM" )
        {
            $var_name = "T3H";
        }
        else
        {
            $var_name = "T1H";
        }

        $vrfy_type = $low_type . "_ts_stn";
        $data_head = "DFS_" . $type . "_STN_";
        $data_path = $this->datafile_dir . $type ."/" . $this->data_group_dir . $this->mon_dir; 

        $bangjae_data_path = $this->datafile_dir . $type . "/" .  $this->data_group_dir . $this->bangjae_dir; 
        $bangjae_date = $this->common_func->getDateDirectoryArray($bangjae_data_path);

        $season_data_path = $this->datafile_dir . $type . "/" .  $this->data_group_dir . $this->season_dir; 
        $season_date = $this->common_func->getDateDirectoryArray($season_data_path);
        
        $data_to_template = array();
        $data_to_template['vrfyType'] = $vrfy_type;
        $data_to_template['dataHead'] = $data_head;

        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($data_path);

        $data_to_template['modltech_info'] = $this->common_func->setModelCheckbox($low_type);

        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyTypeName'] = $this->common_func->getVrfyTypeName($vrfy_type);
        $data_to_template['varName'] = $var_name;
        $data_to_template['varArray'] = $this->var_array;
        $data_to_template['varnameArray'] = $this->var_name_array;
        $data_to_template['vrfyTech'] = $this->common_func->getVrfyTech($var_name);
        
        $data_to_template['bangjaeDate'] = $bangjae_date;
        $data_to_template['seasonDate'] = $season_date;

        // YYYY:selectBox 와 MM(방재기간 또는 계절에 따라 naming 변경):selectBox 분리를 위해서
        $data_to_template['bangjaeArrMap'] = $this->bangjae_func->getDateSelctBoxArray($bangjae_date);
        $data_to_template['seasonArrMap'] = $this->bangjae_func->getDateSelctBoxArray($season_date);
        
        $data_to_template['main_content'] = "shrt/ts_stn";

        return $data_to_template;
    }

}

/* End of file MY_Controller.php */
/* Location: /application/core/MY_Controller.php */
