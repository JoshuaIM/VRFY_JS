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
    
    <!-- month picker and calendar -->
    <link href="<?php echo base_url('assets/month_picker_plug/jquery-ui.css');?>" rel="stylesheet">
    <script src="<?php echo base_url('assets/month_picker_plug/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('assets/month_picker_plug/jquery-ui.min.js');?>"></script>
    <script src="<?php echo base_url('assets/month_picker_plug/month_picker.js');?>"></script>
    
    <!-- <link href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet"> -->
    <!-- <script src="https://code.jquery.com/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> -->

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('assets/lib/bootstrap/js/bootstrap.min.js');?>"></script>
    
    <script src="<?php echo base_url('assets/lib/jquery.scrollTo.min.js');?>"></script>
    <script src="<?php echo base_url('assets/lib/jquery.nicescroll.js');?>" type="text/javascript"></script>
    <!--script for this page-->
  
    <script src="<?php echo base_url('assets/js/highcharts.js');?>"></script>
    <script src="<?php echo base_url('assets/js/exporting.js');?>"></script>
  
	<link href="<?php echo base_url('assets/css/style-edit-custom.css');?>" rel="stylesheet">
	
</head>