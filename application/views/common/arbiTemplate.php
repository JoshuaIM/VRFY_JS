<?php defined('BASEPATH') OR exit('No direct script access allowed');

    $vrfyTypeArr = explode("_", $vrfyType);
    //$vrfyTypeArr[0] >> shrt or medm
    //$vrfyTypeArr[1] >> ts or map
    //$vrfyTypeArr[2] >> stn or grd
    $mapType = $vrfyTypeArr[0] . "_" . $vrfyTypeArr[2];
    
    // 현재 년도 확인
    $thisYear = date("Y");
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검증페이지</title>
    
    <!-- 현재 년도 확인 -->
    <script type="text/javascript">
        var thisYear = "<?php echo $thisYear;?>";
    </script>
    
    <!-- Favicons -->
    <link href="<?php echo base_url('assets/img/favicon.png');?>" rel="icon">
    <link href="<?php echo base_url('assets/img/apple-touch-icon.png');?>" rel="apple-touch-icon">
    
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('assets/lib/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url('assets/lib/font-awesome/css/font-awesome.css');?>" rel="stylesheet" />
    
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/style.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style-responsive.css');?>" rel="stylesheet">
    
    <!-- datepicker and calendar ->> Using ipop datepicker -->
    <link href="<?php echo base_url('assets/lib/jquery_ipop/css/jquery-ui.css');?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/lib/jquery_ipop/js/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('assets/lib/jquery_ipop/js/jquery-ui.min.js');?>"></script>
    <!-- <script src="<?php echo base_url('assets/lib/jquery_ipop/js/jquery-ui.js');?>"></script>  -->
    
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('assets/lib/bootstrap/js/bootstrap.min.js');?>"></script>
    
    <!-- TODO : 메뉴 아코디언 막음. -->
    <!-- <script src="<?php echo base_url('assets/lib/jquery.dcjqaccordion.2.7.js');?>" class="include" type="text/javascript" ></script>  -->
    
    <script src="<?php echo base_url('assets/lib/jquery.scrollTo.min.js');?>"></script>
    <script src="<?php echo base_url('assets/lib/jquery.nicescroll.js');?>" type="text/javascript"></script>
    <!--script for this page-->
  
    <script src="<?php echo base_url('assets/js/highcharts.js');?>"></script>
    <script src="<?php echo base_url('assets/js/exporting.js');?>"></script>
  
	<link href="<?php echo base_url('assets/css/style-edit-custom.css');?>" rel="stylesheet">
	
</head>

<body style="overflow-x:hidden; overflow-y:auto;">

	<section id="container">
    
        <!--sidebar main menu start-->
        <aside>
            <div id="sidebar" class="nav-collapse">
                <!-- sidebar menu start-->
                <a href="<?php echo base_url('arbitraryExpression'); ?>" class="arbi_logo">
                	<b>동네예보 가이던스<br><span>검증페이지 임의기간</span></b>
                </a>
            
            	<ul class="sidebar-menu" id="nav-accordion">
            	<hr>
            	
                    <!-- leftside(Main Menu) -->		
                    <!-- 단기 -->		
                    <li class="sub-menu income">
                        <a><i class="fa fa-signal"></i><span>
                		단기</span></a>
                        <ul class="sub">
                            <li class="main_menu">
                            <a href="<?php echo base_url('arbitraryExpression'); ?>" class="<?php echo ($mapType=='shrt_stn') ? 'active' : '';?>"><i class="fa fa-map-marker"></i>
                    		지점
                    		</a></li>
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('arbitraryExpression'); ?>" class="subsub <?php echo ($vrfyType=='shrt_ts_stn') ? 'active' : '';?>"><i class="fa fa-clock-o"></i>
                            	시계열</a></li>
                            	<li class="main_submenu">
                                <a href="<?php echo base_url('arbitraryExpression/arbi_shrt_map_stn'); ?>" class="subsub <?php echo ($vrfyType=='shrt_map_stn') ? 'active' : '';?>"><i class="fa fa-picture-o"></i>
                            	공간분포</a></li>

                            <!-- 2021-03-22 잠시 표출 중단 -->
                            <!-- 
                            <li class="main_menu">
                            <a href="<?php echo base_url('arbitraryExpression/arbi_shrt_ts_grd');?>" class="<?php echo ($mapType=='shrt_grd') ? 'active' : '';?>"><i class="fa fa-th"></i>
                        	격자</a></li>
                                <li class="main_submenu">
                                <a href="<?php echo base_url('arbitraryExpression/arbi_shrt_ts_grd');?>" class="subsub <?php echo ($vrfyType=='shrt_ts_grd') ? 'active' : '';?>"><i class="fa fa-clock-o"></i>
                            	시계열</a></li>
                                <li class="main_submenu">
                                <a href="<?php echo base_url('arbitraryExpression/arbi_shrt_map_grd');?>" class="subsub <?php echo ($vrfyType=='shrt_map_grd') ? 'active' : '';?>"><i class="fa fa-picture-o"></i>
                        		공간분포</a></li>
                            -->

						</ul>
					</li>
					
                    <!-- leftside(Main Menu) -->		
                    <!-- 중기 -->		
                    <li class="sub-menu income">
                        <a><i class="fa fa-signal"></i><span>
                		중기</span></a>
                        <ul class="sub">
                            <li class="main_menu">
                            <a href="<?php echo base_url('arbitraryExpression/arbi_medm_ts_stn'); ?>" class="<?php echo ($mapType=='medm_stn') ? 'active' : '';?>"><i class="fa fa-map-marker"></i>
                    		지점
                    		</a></li>
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('arbitraryExpression/arbi_medm_ts_stn'); ?>" class="subsub <?php echo ($vrfyType=='medm_ts_stn') ? 'active' : '';?>"><i class="fa fa-clock-o"></i>
                            	시계열</a></li>
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('arbitraryExpression/arbi_medm_map_stn'); ?>" class="subsub <?php echo ($vrfyType=='medm_map_stn') ? 'active' : '';?>"><i class="fa fa-picture-o"></i>
                            	공간분포</a></li>
						</ul>
					</li>
            	</ul>
			</div>
            <!-- sidebar main menu end-->

        	<section id="main-content">
    		<?php 
    		// 임의기간 자료 사용
        		// 지점: 단기 or 중기 시계열
    		    if($vrfyType == 'shrt_ts_stn' || $vrfyType == 'medm_ts_stn') { 
    		        $this->load->view('arbitraryExpression/arbi_ts_stn');
    		        $this->load->view('common/topMenu/arbi/top_arbi_ts');
    		        $this->load->view('common/content'); 
    		    // 격자: 단기 or 중기 시계열
        		} else if($vrfyType == 'shrt_ts_grd' || $vrfyType == 'medm_ts_grd') {
                    $this->load->view('arbitraryExpression/arbi_ts_grd'); 
                    $this->load->view('common/topMenu/arbi/top_arbi_ts_grb'); 
                    $this->load->view('common/content_grd_ts'); 
    		    // 지점: 단기 or 중기 공간분포
        		} else if($vrfyType == 'shrt_map_stn' || $vrfyType == 'medm_map_stn') {
                    $this->load->view('arbitraryExpression/arbi_map_stn'); 
                    $this->load->view('common/topMenu/arbi/top_arbi_ts'); 
                    $this->load->view('common/content_map'); 
                // 격자: 단기 공간분포 (중기 자료 없음)
                //} else if($vrfyType == 'shrt_ts_grd' || $vrfyType == 'medm_ts_grd') {
        		} else if($vrfyType == 'shrt_map_grd') {
        		    $this->load->view('arbitraryExpression/arbi_map_grd');
        		    $this->load->view('common/topMenu/arbi/top_arbi_ts');
        		    $this->load->view('common/content_map'); 
        		}
            ?>
    		</section>
    		
        </aside>
        <!--sidebar end-->

    <!--main content end-->
	</section>
	
	<script src="<?php echo base_url('assets/lib/common-scripts.js');?>"></script>
 	<script type="text/javascript">
 		/*
      	(function() {    
          	var selected = '<?php echo $selected?>';
          	if(selected.split('_').length>1){
          		$('.' + selected).addClass('active');
          		$('.' + selected.split('_')[0] + ' >a').addClass('active');
          	}else{
          		$('.' + selected  +' >a').addClass('active');
          	}  	    	
     	}());
     	*/
	</script>

</body>

</html>
