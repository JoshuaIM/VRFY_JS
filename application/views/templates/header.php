<?php defined('BASEPATH') OR exit('No direct script access allowed');

    $vrfyTypeArr = explode("_", $vrfyType);

    if( $vrfyTypeArr[0] != "gemd" ) {
        $mapType = $vrfyTypeArr[0] . "_" . $vrfyTypeArr[2];
    } else {
        $mapType = $vrfyTypeArr[0] . "_" . $vrfyTypeArr[1];
    }

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
            <!-- <script src="<?php echo base_url('assets/lib/jquery_ipop/js/jquery-ui.min.js');?>"></script> -->
    <link href="<?php echo base_url('assets/lib/jquery_ipop/css/jquery-ui.css');?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/lib/jquery_ipop/js/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('assets/lib/jquery_ipop/js/jquery-ui.js');?>"></script> 
    
    
    <!-- month_calendar 사용할 것이면 주석 풀것 -->
        <!-- <script src="<?php echo base_url('assets/month_calendar/jquery.mousewheel.min.js');?>"></script> -->
    <!-- <script src="<?php echo base_url('assets/month_calendar/calendar_mon_ahmax.js');?>"></script>
    <link href="<?php echo base_url('assets/month_calendar/calendar_mon_ahmax.css');?>" rel="stylesheet"> -->
    <!-- month_calendar 사용할 것이면 주석 풀것 -->


    
    <!-- month_picker 사용할 것이면 주석 풀것 -->
    <!-- 주석 풀면 안됨 -->
    <!-- <link href="<?php echo base_url('assets/month_picker/stylesheets/stylesheet.css');?>" rel="stylesheet"> -->
    
    <!-- <link href="<?php echo base_url('assets/month_picker/MonthPicker.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/month_picker/examples.css');?>" rel="stylesheet">
    
    <script src="<?php echo base_url('assets/month_picker/jquery-1.12.1.min.js');?>"></script>
    <script src="<?php echo base_url('assets/month_picker/jquery-ui.min.js');?>"></script>
    <script src="<?php echo base_url('assets/month_picker/jquery.maskedinput.min.js');?>"></script>
    <script src="<?php echo base_url('assets/month_picker/MonthPicker.js');?>"></script>
    <script src="<?php echo base_url('assets/month_picker/examples.js');?>"></script> -->
    <!-- month_picker 사용할 것이면 주석 풀것 -->


    
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