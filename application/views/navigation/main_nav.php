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
                            <a href="<?php echo base_url('shrt/ts_stn');?>"> <i class="fa fa-map-marker"></i>지점</a></li>
                                <li class="main_submenu">
                                <a href="<?php echo base_url('shrt/ts_stn'); ?>"> <i class="fa fa-clock-o"></i>시계열</a></li>
                                <li class="main_submenu">
                                <a href="<?php echo base_url('main/shrt_map_stn'); ?>"> <i class="fa fa-picture-o"></i>공간분포</a></li>
                        </ul>
                    </li>
                    
                        
                </ul>
            </div>
        <!-- sidebar main menu end-->
        
        <!-- main content start-->
            <section id="main-content">