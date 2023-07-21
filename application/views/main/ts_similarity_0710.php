<!-- // 지점의 id를 매칭하여 지점 이름을 가져온다. 또한 지점 선택 selectBox를 통해 세부 지역을 선택 할 수 있도록 리스팅 한다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_station_name_similarity.js');?>"></script>
<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_grph_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 월 자료(기본: 임의기간이 아닌)를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/month_common_func.js');?>"></script>
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>

<!-- // highcharts 관련 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/highcharts_frame.js');?>"></script>

<!-- // CSV 파일 생성에 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/makeCSVfile_similarity.js');?>"></script>

<!-- // 2020년 12월 기준 이전 날짜에는 단기 3시간 자료 이후에는 단기 1시간 자료를 표출하기 위해 사용되는 함수. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/shrt_change_3to1_func.js');?>"></script>
	
<!-- // 방재기간 관련 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/bangjae/bangjae_ui_options.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/bangjae_ajax.js');?>"></script>

<!-- // 계절별 관련 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/season_similarity_ajax.js');?>"></script>

<!-- // 전체기간 관련 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/allmonth_similarity_ajax.js');?>"></script>

<!-- // fcst ajax 및 집계표 공통함수 분리 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/fcst_similarity_ajax.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/common/data_table_similarity.js');?>"></script>
	
<!-- // 줌인아웃 기능 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/zoom/zoom.js');?>"></script>
	

	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/common/forecast_selectBox.js');?>"></script>
	
	<script src="<?php echo base_url('assets/js/vrfy_js/showTable/performCompTable.js');?>"></script>



<script type="text/javascript">
// 사이트 확인 (month or arbi)
var dateType = "<?php echo $dateType; ?>";

const vrfyType = "<?php echo $vrfyType; ?>";

// 그래픽 Title의 검증지수 한글화.
var vrfy_data;
var vrfy_txt;
var vrfy_title;

// 최신 날짜 (디렉터리 검사 후 최신 날짜로 세팅.)
// assets/js/vrfy_js/month_common_func.js - changeDatePicker() 에서 초기화 시 사용.
let currentStrDate = "<?php echo $dataDate; ?>";			
let currentEndDate = "<?php echo $dataDate; ?>";			

// 방재기간 선택 가능 연도 배열.
// let SPRING = [<?php //echo '"'.implode('","', $SPRING).'"' ?>];
// let WINTER = [<?php //echo '"'.implode('","', $WINTER).'"' ?>];

// 변수 타입 스트링으로 모두 변환하기 위해 json_encod 안씀.
let BANGJAE = [<?php echo '"'.implode('","', $bangjaeDate).'"' ?>];
let BANGJAEMAP = <?php echo json_encode($bangjaeArrMap); ?>;
let SEASON = [<?php echo '"'.implode('","', $seasonDate).'"' ?>];
let SEASONMAP = <?php echo json_encode($seasonArrMap); ?>;

let glob_data = new Array();

// 최고, 최저기온 공통 예측시간 적용을 위함.
let def_forecast_range = new Array();

// 예측 성능 비교표 표출 시 사용.
let ajax_url = "";
let perform_var = "";
let perform_modl = new Array();
let perform_vrfy = new Array();
let perform_utc = new Array();


// 글로벌 변수로 변경(예측기간 변경 선택 기능 때문). 2023-04-14
let dataFontClass = "";

    // 첫 페이지 기능.
    $(document).ready(function(){
		// 방재기간 선택 옵션 숨기기. - css 로 컨트롤 시 위치 깨짐.
		// bangjaeOFF();
		originalON();
    	readyAndNowFunc();

		// 테스트
		// initCalMon("cal1");

    });


    // 첫 페이지 또는 NOW 버튼 클릭 시 실행.
    function readyAndNowFunc() {
		// assets/js/vrfy_js/month_common_func.js
    	changeDatePicker(null, null);

	 	// 단기-격자-시계열(shrt_ts_grd)는 검증지수 선택이 단일 선택이므로 pType으로 함수 분류.
		var pType = "<?php echo $vrfyType; ?>";
		
    	// PHP array to Javascript array
		if( !vrfy_data ) {
			vrfy_data = [<?php echo '"'.implode('","', $vrfyTech['data_vrfy']).'"' ?>];
			vrfy_txt = [<?php echo '"'.implode('","', $vrfyTech['txt_vrfy']).'"' ?>];
			vrfy_title = [<?php echo '"'.implode('","', $vrfyTech['title_vrfy']).'"' ?>];
		}
			
		// 검증 지수 셀렉트박스 생성.
		// assets/js/vrfy_js/common_func.js
    	makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);
    	
    	getDataArray();
    }


    // 데이터 표출 시 UI 정보 수집 함수.
    function getDataArray() {

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
    	var init_hour = new Array();
    	$("input[name=INIT_HOUR]:checked").each(function() {
    		init_hour.push($(this).val());
    	});
		if( init_hour.length < 1 ) {
			$("input[name=INIT_HOUR][value='00#00']").prop("checked", true);
			init_hour.push("00#00");
		}

    	// (UI)모델 및 기법 선택 값 (중복 선택)
    	var model_sel = new Array();
    	$("input[name=MODEL_TECH]:checked").each(function() {
    		model_sel.push($(this).val());
    	});
		// // 강제로 BEST 만 선택되도록 구현. 임시방편.
		// model_sel.push("BEST_GEMD");
		// $("input[name=MODEL_TECH]").prop("checked", false);
		// $("input[name=MODEL_TECH][value='BEST_GEMD']").prop("checked", true);
    

    	// (UI)지점 선택 값
    	var location =  new Array();
		// 기존 멀티 지점 선택 안되게
		var selLoc = $("select[name=LOCATION]").val(); 
			if( selLoc.length > 1) {
				alert("지점선택은 하나만 가능합니다.");
				return false;
			}
			if( selLoc == "ALL" ) {
				location.push( "AVE" );
			} else if( selLoc == "247ALL" ) {
				location.push( "AVE" );
				data_head = data_head.replace("DFS", "247");
			} else {
				location = selLoc[0].split('#');
			}
console.log('selLoc', selLoc);
		// // 멀티 지점 선택 가능
    	// $("input[name=STATION]:checked").each(function() {
		// 	const selLoc = $(this).val();
		// 	if( selLoc == "ALL" ) {
		// 		location.push( "AVE" );
		// 	} else if( selLoc == "247ALL" ) {
		// 		location.push( "AVE" );
		// 		data_head = data_head.replace("DFS", "247");
		// 	} else {

		// 		// 권역의 전체가 선택 시
		// 		if( $("input[id=loc_ave]").is(":checked") ) {
		// 			const id_val = $("input[id=loc_ave]").val();
		// 			location = id_val.split("#"); 
		// 		} else {
		// 			// 권역평균 선택 시
		// 			if( $("input[id=loc_mean]").is(":checked") ) {
		// 				location = selLoc.split('#');
		// 			// 각각의 지점 선택 시
		// 			} else {
		// 				location.push(selLoc);
		// 			}
		// 		}
		// 	}
    	// });
    
    	// (UI)기간 선택 값
    	var peri = $("select[name=PERIOD]").val();

		// 줌 체크박스 보이기/숨기기.
		setZoomButton(peri);
    
		let start_init = "";
		let end_init = "";
		let bangjae_date = "";
		let season_date = "";
		if( peri === "BANGJAE" ) {

			bangjae_date = $("#select_bangjae_date").val() + $("#select_bangjae_season").val();
			// } else if( peri == "SEASON" ) {
				
				// 	season_date = $("#select_bangjae_date").val() + $("#select_bangjae_season").val();
		} else if( peri === "SEASON" ) {
				
			season_date = $("#select_season_date").val() + $("#select_season_season").val();
		} else {

			// (UI)기간 시작 값
			start_init = $("input:text[name='sInitDate']").val();
			// (UI)기간 끝 값
			end_init = $("input:text[name='eInitDate']").val();
		}

    	// (UI)검증지수 선택 값 (중복 선택)
    	var vrfy_idx = new Array();
    	$("input[name=VRFY_INDEX]:checked").each(function() {
    		vrfy_idx.push($(this).val());
    	});
        	if( vrfy_idx.length < 1 ) {
    			alert("한개 이상의 검증지수를 선택해 주십시오");
    			return false;
        	}

		const DATA_TYPE = "similarity";
        if( peri == "FCST" ) {
    		const ajax_url_fcst = '<?php echo site_url();?>/main/getGemdsimilarityFcstData';
    		// callAjaxFcstData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, map_type);
    		callAjaxFcstData(ajax_url_fcst, data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, peri);
        } else if( peri == "MONTH" ) {
			// callAjaxMonthData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, map_type);
    		callAjaxMonthData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, peri);
        } else if( peri == "BANGJAE" ) {
    		const ajax_url_bangjae = '<?php echo site_url();?>/main/getGemdSimilarityBangjaeData';
			callAjaxBangjaeData(DATA_TYPE, ajax_url_bangjae, data_head, var_select, init_hour, model_sel, location, peri, bangjae_date, vrfy_idx);
        } else if( peri == "SEASON" ) {
    		const ajax_url_season = '<?php echo site_url();?>/main/getGemdSimilaritySeasonData';
			callAjaxSeasonData(ajax_url_season, data_head, var_select, init_hour, model_sel, location, peri, season_date, vrfy_idx);
		} else if( peri === "ALLMONTH" ) {
    		const ajax_url_allmonth = '<?php echo site_url();?>/main/getGemdSimilarityAllmonthData';
			callAjaxAllmonthData(ajax_url_allmonth, data_head, var_select, init_hour, model_sel, location, vrfy_idx, peri);
		}

    }


	// TODO: 2021-05-28 초기시각 UTC 중복 선택 막음. (2021-06-01 Edit)
	function setHour(id) {
        
    	$('input[name=INIT_HOUR]').each(function() {
			this.checked = false;
			// this.checked = true;
		});

    	$('#' + id).prop("checked", true);
		
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
		$stn_split = explode("_", $vrfyType);
		if( $stn_split[0] === "medm" ) {
			$stnData = ["stn" => "medm"];
		} else {
			$stnData = ["stn" => "def"];
		}
		// $this->load->view('common/sideMenu/stationSelectBox', $stnData); 
		$this->load->view('common/sideMenu/stationSelectBoxSIMI', $stnData); 
	?>
			
</div>   

