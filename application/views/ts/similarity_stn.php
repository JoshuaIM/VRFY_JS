<script type="text/javascript">

// 사이트 확인 (month or arbi)
	const type = "<?php echo $type; ?>";
	const sub_type = "<?php echo $sub_type; ?>";
	var dateType = "<?php echo $dateType; ?>";
	const data_type = "<?php echo $vrfyType; ?>";
	const grph_type = "<?php echo $grph_type; ?>";

// 그래픽 Title의 검증지수 한글화.
	let vrfy_data = <?php echo json_encode($vrfyTech['data_vrfy']); ?>;
	let vrfy_txt = <?php echo json_encode($vrfyTech['txt_vrfy']); ?>;
	let vrfy_title = <?php echo json_encode($vrfyTech['title_vrfy']); ?>;

// 최신 날짜 (디렉터리 검사 후 최신 날짜로 세팅.)
	// assets/js/vrfy_js/common_func.js - changeDatePicker() 에서 초기화 시 사용.
	let currentStrDate = "<?php echo $dataDate; ?>";			
	let currentEndDate = "<?php echo $dataDate; ?>";			

// 변수 타입 스트링으로 모두 변환하기 위해 json_encode 안씀.
	<?php 
	if ( $type != "SSPS" )
	{
	?>
		let BANGJAE = [<?php echo '"'.implode('","', $bangjaeDate).'"' ?>];
		let SEASON = [<?php echo '"'.implode('","', $seasonDate).'"' ?>];
		let BANGJAEMAP = <?php echo json_encode($bangjaeArrMap); ?>;
		let SEASONMAP = <?php echo json_encode($seasonArrMap); ?>;
	<?php
	}
	?>

	let glob_data = new Array();

// (예보활용도-정확도) 수치예보 가이던스 자료 공통 예측시간 적용을 위함.
	let def_forecast_range = new Array();

// 예측 성능 비교표 표출 시 사용.
	const site_url = "<?php echo site_url();?>";
	let perform_var = "";
	let perform_modl = new Array();
	let perform_vrfy = new Array();
	let perform_utc = new Array();

// 글로벌 변수로 변경(예측기간 변경 선택 기능 때문). 2023-04-14
	let dataFontClass = "";


	// 첫 페이지
	$(document).ready(function()
	{
		// TOP-메뉴 예측기간, 월별, 방재, 계절, 전체기간 선택에 따른 date select box 형식 제어
		originalON();

		// 첫 페이지 또는 NOW 버튼 클릭 시.
		// assets/js/vrfy_js/common/ready_and_now.js
		readyAndNowFunc();
	});


    // 데이터 표출 시 UI 정보 수집 함수.
    function getDataArray()
	{

		// 데이터 네임 앞 글자. 단기-지점
		let data_head = "<?php echo $dataHead; ?>";

    	// (UI)요소 선택 값
		let var_select = new Array();
    	$("input[name=VARIABLE]:checked").each(function() {
			if( $(this).val() != "ALL" ) {
				var_select.push($(this).val());
			}
    	});
		
		// (UI)초기시각 UTC 선택 값 (중복 선택)
    	let init_hour = get_init_hour_option();
  
		// TODO : 멀티 지점 적용 못함.
		// // (UI)지점 및 표준검증지점에 따른 data_head 값 변경.
    	// let get_loc_dhead = get_location_datahead_option(data_head);
    	// // (UI)지점 선택 값
		// let location = get_loc_dhead.get("location");
		// // 247(표준검증지점)의 경우 글로벌 변수(data_head)값 변경.
		// data_head = get_loc_dhead.get("datahead");
		// (UI)지점 선택 값

    	// let location =  new Array();
		// let selLoc = $("select[name=LOCATION]").val(); 
		// 	if( selLoc.length > 1) {
		// 		alert("지점선택은 하나만 가능합니다.");
		// 		return false;
		// 	}
		// 	if( selLoc == "ALL" ) {
		// 		location.push( "ST308" );
		// 	} else if( selLoc == "247ALL" ) {
		// 		location.push( "ST247" );
		// 	} else {
		// 		location = selLoc[0].split('#');
		// 	}

    	// let location =  new Array();
	// 	let selLoc = $("select[name=STATION]").val(); 
	// console.log('selLoc', selLoc);

		// 	if( selLoc.length > 1) {
		// 		alert("지점선택은 하나만 가능합니다.");
		// 		return false;
		// 	}
		// 	if( selLoc == "ALL" ) {
		// 		location.push( "ST308" );
		// 	} else if( selLoc == "247ALL" ) {
		// 		location.push( "ST247" );
		// 	} else {
		// 		location = selLoc[0].split('#');
		// 	}

		// (UI)지점 및 표준검증지점에 따른 data_head 값 변경.
		// assets/js/vrfy_js/common/get_option_value.js
    	let get_loc_dhead =  get_location_datahead_option(data_head);
    	// (UI)지점 선택 값
		let location = get_loc_dhead.get("location");
		// 247(표준검증지점)의 경우 글로벌 변수(data_head)값 변경.
		// data_head = get_loc_dhead.get("datahead");

		// (UI)검증지수 선택 값 (중복 선택)
		let vrfy_idx = get_vrfy_option();

    	// (UI)기간 선택 값
    	let peri = $("select[name=PERIOD]").val();

		// 줌 체크박스 보이기/숨기기.
		setZoomButton(peri);
    
		// (UI)모델 및 기법 선택 값 (중복 선택)
		let model_sel = get_model_option();

		// assets/js/vrfy_js/ts_stn/call_timeseries_ajax.js
		call_timeseries_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
		// call_similarity_timeseries_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
    }

	// TODO :
	// function selVar(val) : 요소선택박스가 없다 = 기존 시계열과의 차이점.

</script>


<div id="sidebar_sub">

	<!-- 사이드 서브 메뉴 상위 네이게이션 -->
	<?php $this->load->view('common/sideMenu/navigation', $vrfyTypeName); ?>
			
    <!-- 사이드 서브 메뉴 선택 박스 시작 -->
	<!-- 요소 선택 -->
	<?php 
		$varData =[
			"varArray" => $varArray,
			"varnameArray" => $varnameArray
		];
		$this->load->view('common/sideMenu/varSelectBoxGEMD', $varData);
	?>
	
	<!-- 모델 선택 -->
	<?php
		$modlData = [
			"vrfyTypeName" =>$vrfyTypeName,
			"modltech_info" => $modltech_info
		];
		$this->load->view('common/sideMenu/modlSelectBoxGEMD', $modlData);
	?>

	<!-- 초기시각 선택 -->
	<?php $this->load->view('common/sideMenu/utcSelectBox'); ?>

	<!-- 지점 선택 -->
	<?php 
		$stnData = ["stn" => "def"];
		// $this->load->view('common/sideMenu/stationSelectBoxSIMI', $stnData);
		$this->load->view('common/sideMenu/stationSelectBox', $stnData);
	?>
			
</div>   

