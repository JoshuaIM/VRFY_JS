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

	const graphDirHead = "<?php echo "$dataDirectoryHead"; ?>";
	
	const site_url = "<?php echo site_url();?>";

	// 변수 타입 스트링으로 모두 변환하기 위해 json_encod 안씀.
	let BANGJAE = [<?php echo '"'.implode('","', $bangjaeDate).'"' ?>];
	let BANGJAEMAP = <?php echo json_encode($bangjaeArrMap); ?>;

	<?php if( $type === "GEMD" OR $grph_type === "map" ) { ?>
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
    function getDataArray() {
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
			// call_ssps_graph_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
			call_graph_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
		}
		else
		{
			// (UI)모델 및 기법 선택 값 (중복 선택)
			let model_sel = get_model_option();
			call_graph_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri);
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
	function selVar(val) {
		$.ajax({
                type : "POST",
                    data :
                    {
						// "varName" : val.value
						"varName" : val
                    },
                    dataType: "json",
                    url : '<?php echo site_url();?>/main/callVrfyTech',
					// 변수에 저장하기 위함.
                    async:false,
                    success : function(resp)
                    {
// console.log(resp);

                        // 검증지수 체크박스 삭제.
                    	$('#vrfySelect').empty();

                	 	// 단기-격자-시계열(shrt_ts_grd)는 js함수가 다르다.
                		var pType = "<?php echo $vrfyType; ?>";
                    	
            			vrfy_data = resp['data_vrfy'];
            			vrfy_txt = resp['txt_vrfy'];
            			vrfy_title = resp['title_vrfy'];
                		
                		// 검증 지수 셀렉트박스 생성.
						makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);

                    },
                    error : function(error) 
                    {
                        console.log("error Message: ");
                        console.log(error);
                    }
            })

        // 예보시간 초기화
        idx = 0
        // 데이터 표출.
        getDataArray();
	}





///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  방재기간 자료  Ajax 이용 그래픽 표출 메서드.
	function callAjaxBangjaeInfo(ajax_url_bangjae, data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, peri, bangjae_date, vrfy_idx) {

		var sd = start_init.substr(0,4) + "-" + start_init.substr(4,2) + "-01";
		var var_change = var_select;

    	$.ajax({
            type : "POST",
                data :
                {
    				"data_head" : data_head,
    				"var_select" : var_change,
    				"init_hour" : init_hour,
    				"model_sel" : model_sel,
    				"vrfy_idx" : vrfy_idx,
    				"peri" : peri,
					"bangjae_date" : bangjae_date
                },
                dataType: "json",
                url : ajax_url_bangjae,
    			// 변수에 저장하기 위함.
                async: false,
                success : function(resp)
                {

					// 그래프 표출 영역 초기화.
                	$('#fcstValue').empty();
                	$('#contValue').empty();

					// 표출 항목이 있을 경우 start.
					if( resp["fcst_info"] ) {

						//fcstTable = "<div class='col-lg-10'>";
						fcstTable = "";
						
						fcstTable += "<table class='map_utc_table' >";
						
						// 2021-02-24 add by joshua.
						// if( shrtMonthCheck(sd) == true || ( var_change == "TMX" || var_change == "TMN" ) ) {
						if( ( var_change == "TMX" || var_change == "TMN" ) ) {

							fcstTable += "<tr>";
							for(var i=0; i<resp['fcst_info']['utc_txt'].length; i++) {
									fcstTable += "<td class='sliderLabel' id='ImageL_" + i + "'>" + resp['fcst_info']['utc_txt'][i] + "</td>";
								}
							fcstTable += "</tr>";
							
							fcstTable += "<tr>";
								for(var i=0; i<resp['fcst_info']['utc_txt'].length; i++) {
									fcstTable += "<td class='slider' id='Image_" + i + "' title='" + resp['fcst_info']['utc_txt'][i] + "' > &nbsp; </td>";
								}
							fcstTable += "</tr>";
							
							fcstTable += "</table>";
							
							fcstTable += "</div>";

						// 2021-02-24 add by joshua.
						// 2020-12월 이 후 자료일 경우.	
						} else {

							// assets/js/vrfy_js/map_slider/make_map_slider.js
							if( data_head === "DFS_SHRT_STN_" ) {
								fcstTable += makeShrtSliderTile(init_hour, resp['fcst_info']['utc_txt']);
							} else if( data_head === "DFS_MEDM_STN_" ) {
								fcstTable += makeMedmSliderTile(init_hour, resp['fcst_info']['utc_txt']);
							}
						}

						
						$('#fcstValue').append(fcstTable);

						maxstep = resp['fcst_info']['utc_txt'].length -1;
						
						// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
						display_grph_bangjae(var_change, model_sel, vrfy_idx, resp, idx, data_head);

						
						//setSlideAt(0);
						// 슬라이드바를 직접 클릭할때 호출되는 함수
						$("td[id^='Image_']").click(function() {
							idx = $(this).attr("id").substr(6);
							ImgIndex = idx;
							
							// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
							display_grph_bangjae(var_change, model_sel, vrfy_idx, resp, idx, data_head);
						});

						// 한번 클릭 시 여러번 실행되는 문제(여러번 콜했을 경우)를 방지.
						$("body").off('keydown');
						// 좌우 화살표 키
						$("body").keydown(function(e) {
							if(e.keyCode == 37) { // left
								idx = idx*1 -1;
								if (idx < 0){
									idx = maxstep;
								}
								// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
								display_grph_bangjae(var_change, model_sel, vrfy_idx, resp, idx, data_head);
								
							} else if(e.keyCode == 39) { // right
								idx = idx*1 +1;

								if (idx > maxstep ){
									idx = 0;
								}
								// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
								display_grph_bangjae(var_change, model_sel, vrfy_idx, resp, idx, data_head);
							}
						});
						
					}
					// 표출 항목이 있을 경우 End of if.

                }, // End of "success : function(resp)"
                error : function(error) 
                {
                    alert("error");
                    console.log(error);
                }
        })
		
	}



	function display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head) {

        $("td[id^='Image_']").removeClass("sliderSelected");
        var frameIdx = (idx * 1);
        $("#Image_"+frameIdx).addClass("sliderSelected");
		
		$('#contValue').empty();
		
		var grph_div = "";
        for(var m=0; m<model_sel.length; m++) {
            for(var v=0; v<vrfy_idx.length; v++) {
                for(var d=0; d<resp['date_info'].length; d++) {
            		grph_div += "<div class='img_area col-lg-3' >";
                		grph_div += "<div class='map_header'>";
                			var vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vrfy_idx[v]);
                			grph_div += model_sel[m] + "_" + vrfy_name + "_" + resp['date_info'][d]['ymInfo'] + "_" + resp['date_info'][d]['utcInfo'];
                		grph_div += "</div>";
                		grph_div += "<div class='map_content'>";
                		
							var nameOfgrph = data_head + model_sel[m] + "_" + var_select + "_VRFY_" + vrfy_idx[v] + "." + resp['date_info'][d]['data'] + "_" +  resp['fcst_info']['utc_idx'][idx] + ".png";

							var dt_arr = data_head.split("_");
                			// grph_div += "<img class='grph_img' src='" + "<?php echo base_url('GIFD/');?>" + dt_arr[1] + "/" + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
                			grph_div += "<img class='grph_img' src='" + "<?php echo base_url('/'); ?>" + graphDirHead + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
                			
                		grph_div += "</div>";
            		
            		grph_div += "</div>";
                } 
            }
        }
        
		$('#contValue').append(grph_div);
	}


	// TODO : 급하게 함수 복사 함. 병합 예정
	function display_grph_bangjae(var_select, model_sel, vrfy_idx, resp, idx, data_head) {

		const grphDirHeadBangjae = graphDirHead.replace('MOND', 'BNGJ');

		// setBangjaeInitDateMap(resp['date_info'][0]['data']);
		setBangjaeInitDate(resp['date_info'][0]['data']);

        $("td[id^='Image_']").removeClass("sliderSelected");
        var frameIdx = (idx * 1);
        $("#Image_"+frameIdx).addClass("sliderSelected");
		
		$('#contValue').empty();
		
		var grph_div = "";
        for(var m=0; m<model_sel.length; m++) {
            for(var v=0; v<vrfy_idx.length; v++) {
                for(var d=0; d<resp['date_info'].length; d++) {
            		grph_div += "<div class='img_area col-lg-3' >";
                		grph_div += "<div class='map_header'>";
                			var vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vrfy_idx[v]);
                			grph_div += model_sel[m] + "_" + vrfy_name + "_" + resp['date_info'][d]['ymInfo'] + "_" + resp['date_info'][d]['utcInfo'];
                		grph_div += "</div>";
                		grph_div += "<div class='map_content'>";
                		
							var nameOfgrph = data_head + model_sel[m] + "_" + var_select + "_VRFY_" + vrfy_idx[v] + "." + resp['date_info'][d]['data'] + "_" +  resp['fcst_info']['utc_idx'][idx] + ".png";

							var dt_arr = data_head.split("_");
                			grph_div += "<img class='grph_img' src='" + "<?php echo base_url('/'); ?>" + grphDirHeadBangjae + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
                			
                		grph_div += "</div>";
            		
            		grph_div += "</div>";
                } 
            }
        }
        
		$('#contValue').append(grph_div);
	}

	

    function no_image(th) {
        console.log(th);
		$(th).attr("src", "<?php echo base_url('assets/img/nodata.gif');?>");
    }



	// 2023-05-30
	// 활용도 하나만 보여주기
    function setUtilize(val) {
    	$('input[name=UTILIZE_INDEX]').each(function() {
			this.checked = false;
		});

    	$("input[name=UTILIZE_INDEX][value='" + val + "']").prop("checked", true);
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

