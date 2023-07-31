<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: 
 * @property 
 * @property 
 */

class Medm_stn extends My_Controller {

    function __construct()
    {
        parent::__construct();
    }



    public function index()
    {
        $this->medm_stn();
    }

    public function medm_stn()
    {
        /**
         * 1. $type : SHRT, MEDM, GEMD
         * 2. $data_type : SHRT, MEDM
         * 3. $sub_type : STN, ACCURACY, SIMILARITY
         * 4. $grph_type : ts, map
         **/
        $data_to_template = $this->get_data_template("MEDM", "MEDM", "STN", "map");

        $this->load->view("templates/header", $data_to_template);
        $this->load->view("navigation/main_nav", $data_to_template);
        $this->load->view('map/import_js', $data_to_template); 
        $this->load->view('map/map_stn', $data_to_template); 
        $this->load->view('map/top_options', $data_to_template); 
        $this->load->view('common/content');
        $this->load->view("templates/footer");
    }


    public function ajax_map_stn_data()
    {
        $finalData = $this->get_map_stn_data($_POST);

        echo json_encode($finalData);
    }













}

