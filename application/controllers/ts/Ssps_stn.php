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
        $data_to_template = $this->get_data_ssps_template("SSPS", "SHRT");
        // $data_to_template = $this->get_data_template($d_type, $d_type);

        $this->load->view("templates/header", $data_to_template);
        $this->load->view("navigation/main_nav", $data_to_template);
        $this->load->view('ts/import_js', $data_to_template); 
        $this->load->view('ts/ts_stn', $data_to_template); 
        $this->load->view('ts/top_options', $data_to_template); 
        $this->load->view('common/content');
        $this->load->view("templates/footer");
    }

    public function get_data_ssps_template( $type, $data_type )
    {
        $low_type = strtolower($type);
        $low_data_type = strtolower($data_type);

        $var_name = "T1H";

        $data_head = "DFS_" . $data_type . "_STN_";
        $data_path = $this->datafile_dir . $type ."/" . $this->data_group_dir . $this->mon_dir; 
        
        $vrfy_type = $low_type . "_" . $low_data_type . "_ts_stn";
        
        $data_to_template = array();
        $data_to_template['type'] = $type;
        $data_to_template['vrfyType'] = $vrfy_type;
        $data_to_template['dataHead'] = $data_head;

        $data_to_template['dataDate'] = $this->common_func->getDirectoryDate($data_path);

        $data_to_template['dateType'] = "month";
        $data_to_template['vrfyTypeName'] = $this->common_func->getSspsTypeName($vrfy_type);

        $data_to_template['varName'] = $var_name;

        $data_to_template['varArray'] = $this->ssps_var_array;
        $data_to_template['varnameArray'] = $this->ssps_var_name_array;

        $data_to_template['vrfyTech'] = $this->common_func->getSspsVrfyTech($var_name);
        
        return $data_to_template;
    }












}

