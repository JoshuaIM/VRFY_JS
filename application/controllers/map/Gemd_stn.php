<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: 
 * @property 
 * @property 
 */

class Gemd_stn extends My_Controller {

    function __construct()
    {
        parent::__construct();
    }



    public function index()
    {
        $this->gemd_stn();
    }

    public function gemd_stn()
    {
        /**
         * 1. $type : GEMD
         * 2. $data_type : SHRT, MEDM
         * 3. $sub_type : UTILIZE
         * 4. $grph_type : map
         **/
        $data_to_template = $this->get_data_template("GEMD", "SHRT", "UTILIZE", "map");

        $this->load->view("templates/header", $data_to_template);
        $this->load->view("navigation/main_nav", $data_to_template);
        $this->load->view('map/import_js', $data_to_template); 
        $this->load->view('map/map_stn', $data_to_template); 
        $this->load->view('map/top_options', $data_to_template); 
        $this->load->view('common/content');
        $this->load->view("templates/footer");
    }











}

