<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: 
 * @property 
 * @property 
 */

class Ssps_stn extends My_Controller {

    function __construct()
    {
        parent::__construct();
    }



    public function index()
    {
        $this->ssps_stn();
    }

    public function ssps_stn()
    {
        /**
         * 1. $type : SSPS
         * 2. $data_type : SHRT
         * 3. $sub_type : STN
         * 4. $grph_type : map
         **/
        $data_to_template = $this->get_data_ssps_template("SSPS", "SHRT", "STN", "map");

        $this->load->view("templates/header", $data_to_template);
        $this->load->view("navigation/main_nav", $data_to_template);
        $this->load->view('map/import_js', $data_to_template); 
        $this->load->view('map/map_stn', $data_to_template); 
        $this->load->view('map/top_options', $data_to_template); 
        $this->load->view('common/content');
        $this->load->view("templates/footer");
    }










}

