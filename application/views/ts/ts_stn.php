<script type="text/javascript">

// 사이트 확인 (month or arbi)
	const type = "<?php echo $type; ?>";
	const sub_type = "<?php echo $sub_type; ?>";
	var dateType = "<?php echo $dateType; ?>";
	const data_type = "<?php echo $vrfyType; ?>";

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
    	const var_select = $("select[name=VAR]").val();
        
		// 시간강수량의 경우 검증지수 개수가 너무 많아서 표출 영역 늘림.
		if( var_select === "RN1" ) {
			$(".top_wrapper").css("margin-bottom", "60px");
		} else if( var_select === "SN3" && data_head === "DFS_SHRT_STN_" ) {
			$(".top_wrapper").css("margin-bottom", "60px");
		} else {
			$(".top_wrapper").css("margin-bottom", "0px");
		}
		
		// (UI)초기시각 UTC 선택 값 (중복 선택)
    	let init_hour = get_init_hour_option();
  
		// (UI)지점 및 표준검증지점에 따른 data_head 값 변경.
		// assets/js/vrfy_js/common/get_option_value.js
    	let get_loc_dhead =  get_location_datahead_option(data_head);
    	// (UI)지점 선택 값
		let location = get_loc_dhead.get("location");
		// 247(표준검증지점)의 경우 글로벌 변수(data_head)값 변경.
		data_head = get_loc_dhead.get("datahead");

		// (UI)검증지수 선택 값 (중복 선택)
		let vrfy_idx = get_vrfy_option();

    	// (UI)기간 선택 값
    	let peri = $("select[name=PERIOD]").val();

		// 줌 체크박스 보이기/숨기기.
		setZoomButton(peri);

		// 메인 동작 함수.
		if ( type === "SSPS" )
		{
			let model_sel = ["SSPS"];
			call_ssps_timeseries_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
		}
		else
		{
			// (UI)모델 및 기법 선택 값 (중복 선택)
			let model_sel = get_model_option();
			call_timeseries_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
		}
    }


////////////////////////////////////////////
// 요소선택 변경 시 검증지수 체크박스를 다시 세팅하기 위한 메서드.
	function selVar(var_name)
	{

		// 단기 시간적설이 SN3로 변경되며 검증지수가 증가하였는데 중기와 중복되지 않기 위해 함수를 분리.
		let url_address = "";
		if ( type === "SHRT" || type === "GEMD" )
		{
			url_address = '<?php echo site_url();?>/main/callVrfyTechShrt'
		}
		else if ( type === "SSPS" )
		{
			url_address = '<?php echo site_url();?>/main/callSspsVrfyTech'
		}
		else
		{
			url_address = '<?php echo site_url();?>/main/callVrfyTech'
		}

		$.ajax({
                type : "POST",
                    data :
                    {
						"varName" : var_name
                    },
                    dataType: "json",
                    url : url_address,
					// 변수에 저장하기 위함.
                    async:false,
                    success : function(resp)
                    {
// console.log('selVar(resp) :', resp);

                        // 검증지수 체크박스 삭제.
                    	$('#vrfySelect').empty();

                	 	// 단기-격자-시계열(shrt_ts_grd)는 검증지수 선택이 단일 선택이므로 pType으로 함수 분류.
                		var pType = "<?php echo $vrfyType; ?>";

            			vrfy_data = resp['data_vrfy'];
            			vrfy_txt = resp['txt_vrfy'];
            			vrfy_title = resp['title_vrfy'];

                		// 검증 지수 셀렉트박스 생성.
						makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);

                    	// 초기시각 세팅.
                    	//makeUTCopt(val.value, dateType);
                    },
                    error : function(error) 
                    {
                        console.log("error Message: ");
                        console.log(error);
                    }
            })

        // 데이터 표출.
        getDataArray();
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
			$this->load->view('common/sideMenu/modlSelectBox', $modlData);
		}
	?>

	<!-- 초기시각 선택 -->
	<?php $this->load->view('common/sideMenu/utcSelectBox'); ?>

	<!-- 지점 선택 -->
	<?php 
		if ( $type === "MEDM" )
		{
			$stnData = ["stn" => "medm"];
		}
		else if ( $type === "SSPS" )
		{
			$stnData = ["stn" => "ssps"];
		}
		else
		{
			$stnData = ["stn" => "def"];
		}
		// $this->load->view('common/sideMenu/stationSelectBox', $stnData); 
		$this->load->view('common/sideMenu/stationSelectBox', $stnData); 
	?>
			
</div>   

