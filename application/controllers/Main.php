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

    // View에서 Ajax로 호출하여 요소(변수)별 검증지수 배열 찾기. "get_vrfy_tech()" 메서드 호출.
    public function callVrfyTech()
    {
        $varName = $this->input->post('varName');
        $vrfyTech = $this->common_func->get_vrfy_tech($varName);
        echo json_encode($vrfyTech);
    }

}