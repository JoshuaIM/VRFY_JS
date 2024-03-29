<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: 
 * @property 
 * @property 
 */

class Shrt_stn extends My_Controller {

    function __construct()
    {
        parent::__construct();
    }



    public function index()
    {
        // $this->shrt_stn("SHRT");
        $this->shrt_stn();
    }

    // public function shrt_stn($d_type)
    public function shrt_stn()
    {
        /**
         * 1. $type : SHRT, MEDM, GEMD
         * 2. $data_type : SHRT, MEDM
         * 3. $sub_type : STN, ACCURACY, SIMILARITY
         * 4. $grph_type : ts, map
         **/
        $data_to_template = $this->get_data_template("SHRT", "SHRT", "STN", "ts");
        // $data_to_template = $this->get_data_template($d_type, $d_type);

        $this->load->view("templates/header", $data_to_template);
        $this->load->view("navigation/main_nav", $data_to_template);
        $this->load->view('ts/import_js', $data_to_template); 
        $this->load->view('ts/ts_stn', $data_to_template); 
        $this->load->view('ts/top_options', $data_to_template); 
        $this->load->view('common/content');
        $this->load->view("templates/footer");
    }


    public function ajax_ts_stn_data()
    {
        $finalData = $this->get_ts_stn_data($_POST);

        echo json_encode($finalData);
    }













}

