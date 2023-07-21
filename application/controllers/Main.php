<?php
class Main extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Common_func');
    }

    public function index()
    {
        redirect('ts/shrt_stn');
    }

    
//=========================================================================
//======= 공통 사용 메서드  ================================================
//=========================================================================

    // View에서 Ajax로 호출하여 요소(변수)별 검증지수 배열 찾기. "getVrfyTech()" 메서드 호출.
    public function callVrfyTech()
    {
        $varName = $this->input->post('varName');
        $vrfyTech = $this->common_func->getVrfyTech($varName);
        
        echo json_encode($vrfyTech);
    }
    
    // 단기용 (통합 필요)
    // View에서 Ajax로 호출하여 요소(변수)별 검증지수 배열 찾기. "getVrfyTech()" 메서드 호출.
    public function callVrfyTechShrt()
    {
        $var_name = $this->input->post('varName');
        $vrfy_tech_list = $this->common_func->getVrfyTechShrt($var_name);

        echo json_encode($vrfy_tech_list);
    }

    // 산악용
    // View에서 Ajax로 호출하여 요소(변수)별 검증지수 배열 찾기. "getVrfyTech()" 메서드 호출.
    public function callSspsVrfyTech()
    {
        $varName = $this->input->post('varName');
        $vrfyTech = $this->common_func->getSspsVrfyTech($varName);
        
        echo json_encode($vrfyTech);
    }


}