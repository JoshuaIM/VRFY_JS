1.
<?php
  if(@$this->session->userdata('logged_in')==TRUE)
  {
 ?>

2. <?php echo $this->session->userdata('username')	?> 님 환영합니다. 
 <a href="/todo/auth/logout"  class="btn">로그아웃</a>


<?php
}else {
?>
	3.	<a href="/todo/auth/" class="btn btn-danger">로그인</a>
<?php  } ?>