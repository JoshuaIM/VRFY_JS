<script type="text/javascript">

// 사이트 확인 (month or arbi)
	const type = "<?php echo $type; ?>";
	const sub_type = "<?php echo $sub_type; ?>";
	const dateType = "<?php echo $dateType; ?>";
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

	// 캘린더 클릭 위치 추적용 - id 저장.
	let click_date_from = "";

	const graphDirHead = "<?php echo $dataDirectoryHead; ?>";
	const bangjae_graph_dir = "<?php echo $bangjaeDataDirectoryHead; ?>";
	const season_graph_dir = "<?php echo $seasonDataDirectoryHead; ?>";
	const allmonth_graph_dir = "<?php echo $allmonthDataDirectoryHead; ?>";
	
	const site_url = "<?php echo site_url();?>";

	// 변수 타입 스트링으로 모두 변환하기 위해 json_encod 안씀.
	let BANGJAE = [<?php echo '"'.implode('","', $bangjaeDate).'"' ?>];
	let SEASON = [<?php echo '"'.implode('","', $seasonDate).'"' ?>];
	let BANGJAEMAP = <?php echo json_encode($bangjaeArrMap); ?>;
	let SEASONMAP = <?php echo json_encode($seasonArrMap); ?>;

	<?php if( $type === "GEMD" && $grph_type === "map" ) { ?>
		const utilizeTech = <?php echo json_encode($utilizeTech); ?>;
	<?php }	?>

	let idx = 0;
	let maxstep = 0;
	let keydown_cont = 0;


    // 첫 페이지 자료표출을 위해 "getDataArray()" 메서드 실행.
    $(document).ready(function(){
		// TOP-메뉴 예측기간, 월별, 방재, 계절, 전체기간 선택에 따른 date select box 형식 제어
		originalON();

		// 첫 페이지 또는 NOW 버튼 클릭 시.
		// assets/js/vrfy_js/common/ready_and_now.js
		readyAndNowFunc();
    });
    
    
	// 데이터 표출 시 UI 정보 수집 함수.
    function getDataArray()
	{
		// 캘린더 클릭 위치 추적용 - 초기화
		click_date_from = "";

    	// 데이터 네임 앞 글자. 단기-지점
    	var data_head = "<?php echo $dataHead; ?>";
    	
    	// 요소 선택 값
    	var var_select = $("select[name=VAR]").val();
    
		// 시간강수량의 경우 검증지수 개수가 너무 많아서 표출 영역 늘림.
		if( type === "GEMD" )
		{
			if( var_select === "RN1" || var_select === "SN3" )
			{
				$(".top_wrapper").css("margin-bottom", "90px");
			}
			else
			{
				$(".top_wrapper").css("margin-bottom", "30px");
			}
		}
		else
		{
			if( var_select === "RN1" )
			{
				$(".top_wrapper").css("margin-bottom", "60px");
			}
			else if( var_select === "SN1" )
			{
				$(".top_wrapper").css("margin-bottom", "13px");
			}
			else
			{
				$(".top_wrapper").css("margin-bottom", "0px");
			}
		}

    	// 초기시각 UTC hour 선택 값
    	// 1시간 단기 공간분포 표출 시 00 또는 12UTC 하나만 보여주기로 변경.
    	var init_hour = new Array();
    	$("input[name=INIT_HOUR]:checked").each(function() {
    		init_hour.push($(this).val());
    	});
    
		// (UI)검증지수 선택 값 (중복 선택)
		// assets/js/vrfy_js/common/get_option_value.js
		let vrfy_idx = get_vrfy_option();

    	// (UI)기간 선택 값
    	let peri = $("select[name=PERIOD]").val();

		// 메인 동작 함수.
		if ( type === "SSPS" )
		{
			let model_sel = ["SSPS"];
			call_graph_ajax(data_head, var_select, model_sel, init_hour, vrfy_idx, peri);
		}
		else
		{
			// (UI)모델 및 기법 선택 값 (중복 선택)
			let model_sel = get_model_option();
			call_graph_ajax(data_head, var_select, model_sel, init_hour, vrfy_idx, peri);
		}

    }


	// 2021-02-25
	// 1시간 단기 공간분포 표출 시 00 또는 12UTC 하나만 보여주기로 변경. 
    function setHour(id) {
        
    	$('input[name=INIT_HOUR]').each(function() {
			this.checked = false;
		});

    	$('#' + id).prop("checked", true);
		
    }
    

////////////////////////////////////////////
// 요소선택 변경 시 검증지수 체크박스를 다시 세팅하기 위한 메서드.
	function select_var_ajax(var_value)
	{
		const url = "<?php echo site_url();?>/main/callVrfyTech";
		// assets/js/vrfy_js/common_func.js
		set_vrfy_list(url, var_value);
	}


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
		$this->load->view('common/sideMenu/varSelectBox', $varData);
	?>
	
	<!-- 모델 선택 -->
	<?php
		if ( $type != "SSPS" )
		{
			$modlData = [
				"vrfyTypeName" =>$vrfyTypeName,
				"modltech_info" => $modltech_info
			];
			if ( $type === "GEMD" )
			{
				$this->load->view('common/sideMenu/modlSelectBoxGEMD', $modlData);
			}
			else
			{
				$this->load->view('common/sideMenu/modlSelectBox', $modlData);
			}
		}
	?>

	<!-- 초기시각 선택 -->
	<?php $this->load->view('common/sideMenu/utcSelectBoxMap'); ?>
	
</div>   

