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

}

/* End of file MY_Controller.php */
/* Location: /application/core/MY_Controller.php */
