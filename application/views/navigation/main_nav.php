<?php
    $vrfyTypeArr = explode("_", $vrfyType);

    if( $vrfyTypeArr[0] != "gemd" ) {
        $mapType = $vrfyTypeArr[0] . "_" . $vrfyTypeArr[2];
    } else {
        $mapType = $vrfyTypeArr[0] . "_" . $vrfyTypeArr[1];
    }

    // 현재 년도 확인
    $thisYear = date("Y");
?>

<body style="overflow-x:hidden; overflow-y:auto;">

    <section id="container">

        <aside>

        <!--sidebar main menu start-->
            <div id="sidebar" class="nav-collapse">
                <!-- sidebar menu start-->
                <a href="<?php echo base_url('main'); ?>" class="logo">
                    <b>수치예보 가이던스<br><span>검증페이지</span></b>
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
                            <a href="<?php echo base_url('ts/shrt_stn');?>" class="<?php echo ($mapType=='shrt_stn') ? 'active' : '';?>"> <i class="fa fa-map-marker"></i>지점</a></li>
                                <li class="main_submenu">
                                <a href="<?php echo base_url('ts/shrt_stn'); ?>" class="subsub <?php echo ($vrfyType=='shrt_ts_stn') ? 'active' : '';?>"><i class="fa fa-clock-o"></i>시계열</a></li>
                                <li class="main_submenu">
                                <a href="<?php echo base_url('map/map_stn'); ?>" class="subsub <?php echo ($vrfyType=='shrt_map_stn') ? 'active' : '';?>"><i class="fa fa-picture-o"></i>공간분포</a></li>
                        </ul>
                    </li>
                    
                    <!-- 중기 -->		
                    <li class="sub-menu income">
                        <a><i class="fa fa-signal"></i><span>
                		중기</span></a>
                        <ul class="sub">
                            <li class="main_menu">
                            <a href="<?php echo base_url('main/medm_ts_stn'); ?>" class="<?php echo ($mapType=='medm_stn') ? 'active' : '';?>"><i class="fa fa-map-marker"></i>
                    		지점
                    		</a></li>
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('main/medm_ts_stn'); ?>" class="subsub <?php echo ($vrfyType=='medm_ts_stn') ? 'active' : '';?>"><i class="fa fa-clock-o"></i>
                            	시계열</a></li>
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('main/medm_map_stn'); ?>" class="subsub <?php echo ($vrfyType=='medm_map_stn') ? 'active' : '';?>"><i class="fa fa-picture-o"></i>
                            	공간분포</a></li>
						</ul>
					</li>

                    <!-- 단기(산악) -->		
                    <li class="sub-menu income">
                        <a><i class="fa fa-signal"></i><span>
                		산악</span></a>
                        <ul class="sub">
                            <li class="main_menu">
                            <a href="<?php echo base_url('main/ssps_shrt_ts_stn'); ?>" class="<?php echo ($mapType=='ssps_ts' || $mapType=='ssps_map' ) ? 'active' : '';?>"><i class="fa fa-map-marker"></i>
                    		지점
                    		</a></li>
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('main/ssps_shrt_ts_stn'); ?>" class="subsub <?php echo ($vrfyType=='ssps_shrt_ts_stn') ? 'active' : '';?>"><i class="fa fa-clock-o"></i>
                            	시계열</a></li>
                                
                            	<li class="main_submenu">
                            	<a href="<?php echo base_url('main/ssps_shrt_map_stn'); ?>" class="subsub <?php echo ($vrfyType=='ssps_shrt_map_stn') ? 'active' : '';?>"><i class="fa fa-picture-o"></i>
                            	공간분포</a></li>
                               
						</ul>
					</li>
					 
                    <!-- 예보활용도 -->		
                    <li class="sub-menu income">
                        <a><i class="fa fa-signal"></i><span>
                		예보활용도</span></a>
                        <ul class="sub">
                            <li class="main_menu">
                            <a href="<?php echo base_url('main/gemd_ts_similarity'); ?>" class="<?php echo ($mapType=='gemd_ts' || $mapType=='gemd_map' ) ? 'active' : '';?>"><i class="fa fa-map-marker"></i>
                    		지점
                    		</a></li>
                            	<li class="main_submenu">
                            	<a style="padding-left:10px;" href="<?php echo base_url('main/gemd_ts_similarity'); ?>" class="subsub <?php echo ($vrfyType=='gemd_ts_similarity') ? 'active' : '';?>"><i style="padding-right:3px;" class="fa fa-clock-o"></i>
                            	유사도(시계열)</a></li>

                            	<li class="main_submenu">
                            	<a style="padding-left:10px;" href="<?php echo base_url('main/gemd_ts_accuracy'); ?>" class="subsub <?php echo ($vrfyType=='gemd_ts_accuracy') ? 'active' : '';?>"><i style="padding-right:3px;" class="fa fa-clock-o"></i>
                            	정확도(시계열)</a></li>
                                
                            	<li class="main_submenu">
                            	<a style="padding-left:10px;" href="<?php echo base_url('main/gemd_map_utilize'); ?>" class="subsub <?php echo ($vrfyType=='gemd_map_utilize') ? 'active' : '';?>"><i style="padding-right:3px;" class="fa fa-picture-o"></i>
                            	공간분포</a></li>
						</ul>
					</li>
                        
                </ul>
            </div>
        <!-- sidebar main menu end-->
        
        <!-- main content start-->
            <section id="main-content">