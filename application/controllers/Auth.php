<?php

class Auth extends CI_Controller {


function __construct()
{
    parent::__construct();
    $this->load->model('auth_m');
    //CSRF 방지
    $this->load->helper('form');
}


//주소에서 메서드가 생략되었을 때 실행되는 기본 메서드
public function index()
{
    $this->login();
}


/*
사이트 헤더, 푸터가 자동으로 추가된다.

*/
public function _remap($method)
{
    // //헤더 include
    // $this->load->view('auth/header_v');

    if(method_exists($this, $method))
    {
        $this->{"{$method}"}();
    }

}

/*
*	로그인 처리

*/

 public function login()
 {
     //폼 검증 라이브러리 로드
     $this->load->library('form_validation');
     $this->load->helper('alert');

     //폼 검증할 필드와 규칙 사전 정의
     $this->form_validation->set_rules('username', '아이디', 'required|alpha_numeric');
     $this->form_validation->set_rules('password', '비밀번호', 'required');

     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

     if($this->form_validation->run()==TRUE)
     {

         $auth_data=array(
             'username' => $this->input->post('username', TRUE), 
             'password' =>$this->input->post('password', TRUE)
         );

         $result =$this->auth_m->login($auth_data);

         if($result)
         {
             //세션 생성
             $newdata = array(
                 'username' => $result->username, 
                 'logged_in'=>TRUE		
             );

             $this->session->set_userdata($newdata);

             alert('로그인 되었습니다.', '/VRFY/main');
             exit;
         }
         else
         {
             //실패 시
             alert('아이디나 비밀번호를  확인해 주세요.', '/VRFY/auth');
         }
     }
     else
     {
         //쓰기 폼 view 호출
         $this->load->view('auth/login_v');
     }

 }


}