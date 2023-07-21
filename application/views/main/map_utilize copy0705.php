<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_variable_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 월 자료(기본: 임의기간이 아닌)를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/month_common_func.js');?>"></script>
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>

<!-- // 공간분포 예측기간 slider 타일 생성 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/map_slider/make_map_slider.js');?>"></script>

<!-- // 2020년 12월 기준 이전 날짜에는 단기 3시간 자료 이후에는 단기 1시간 자료를 표출하기 위해 사용되는 함수. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/shrt_change_3to1_func.js');?>"></script>

<!-- // 방재기간 옵션에 대한 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/bangjae/bangjae_ui_options.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/highcharts/bangjae_ajax.js');?>"></script>


<script type="text/javascript">
//사이트 확인 (month or arbi)
var dateType = "<?php echo $dateType; ?>";

// 그래픽 Title의 검증지수 한글화.
var vrfy_data;
var vrfy_txt;
var vrfy_title;

var currentStrDate = "<?php echo $dataDate; ?>";			
var currentEndDate = "<?php echo $dataDate; ?>";		

const graphDirHead = "<?php echo $dataDirectoryHead; ?>";
const vrfyGraphDirHead = "<?php echo $vrfyDataDirectoryHead; ?>";
const bangjaeGraphDirHead = "<?php echo $bangjaeDataDirectoryHead; ?>";

const utilizeTech = <?php echo json_encode($utilizeTech); ?>;

// 변수 타입 스트링으로 모두 변환하기 위해 json_encod 안씀.
let BANGJAE = [<?php echo '"'.implode('","', $bangjaeDate).'"' ?>];
let BANGJAEMAP = <?php echo json_encode($bangjaeArrMap); ?>;

var idx = 0;
var maxstep = 0;

var keydown_cont = 0;


    // 첫 페이지 자료표출을 위해 "getDataArray()" 메서드 실행.
    $(document).ready(function(){
		// 방재기간 선택 옵션 숨기기. - css 로 컨트롤 시 위치 깨짐.
		// bangjaeOFF();
		originalON();
    	readyAndNowFunc();
    });
    
    function readyAndNowFunc() {
    	changeDatePicker(null, null);

	 	// 단기-격자-시계열(shrt_ts_grd)는 js함수가 다르다.
		var pType = "<?php echo $vrfyType; ?>";
		
    	// PHP array to Javascript array
		vrfy_data = [<?php echo '"'.implode('","', $vrfyTech['data_vrfy']).'"' ?>];
		vrfy_txt = [<?php echo '"'.implode('","', $vrfyTech['txt_vrfy']).'"' ?>];
		vrfy_title = [<?php echo '"'.implode('","', $vrfyTech['title_vrfy']).'"' ?>];
		
    	// 활용도 셀렉트박스 생성.
		makeVrfySelectUtilizeMap(utilizeTech['data_vrfy'], utilizeTech['txt_vrfy'], pType, dateType);

    	// 검증 지수 셀렉트박스 생성.
    	makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);
    	
    	getDataArray();
    }
    
    function getDataArray() {
    	// 데이터 네임 앞 글자. 단기-지점
    	var data_head = "<?php echo $dataHead; ?>";
    	
    	// 요소 선택 값
    	var var_select = $("select[name=VAR]").val();
    
		// 시간강수량의 경우 검증지수 개수가 너무 많아서 표출 영역 늘림.
		if( var_select === "RN1" ) {
			// $(".wrapper").css("margin-top", "60px");
			$(".top_wrapper").css("margin-bottom", "90px");
		} else if( var_select === "SN1" ) {
			$(".top_wrapper").css("margin-bottom", "45px");
		} else {
			$(".top_wrapper").css("margin-bottom", "30px");
		}

    	// 초기시각 UTC hour 선택 값
    	var init_hour = new Array();
    	//var init_hour = $("input:checkbox[name='INIT_HOUR']:checked").val(); 
    	// 2021-02-25
    	// 1시간 단기 공간분포 표출 시 00 또는 12UTC 하나만 보여주기로 변경.
    	$("input[name=INIT_HOUR]:checked").each(function() {
    		init_hour.push($(this).val());
    	});
//         	if( init_hour.length < 1 ) {
//     			alert("한개 이상의 초기시각을 선택해 주십시오");
//     			return false;
//         	}
    	
    
    	// 모델 선택 값 배열
    	var model_sel = new Array();
    	$("input[name=MODEL_TECH]:checked").each(function() {
    		model_sel.push($(this).val());
    	});
        	if( model_sel.length < 1 ) {
    			alert("한개 이상의 모델을 선택해 주십시오");
    			return false;
        	}
		// model_sel.push("BEST_GEMD");
		// $("input[name=MODEL_TECH]").prop("checked", false);
		// $("input[name=MODEL_TECH][value='BEST_GEMD']").prop("checked", true);
    
    	// 기간 선택 값
    	var peri = $("select[name=PERIOD]").val();
    
		let start_init = "";
		let end_init = "";
		let bangjae_date = "";

		if( peri == "BANGJAE" ) {

			bangjae_date = $("#select_bangjae_date").val() + $("#select_bangjae_season").val();
		} else {

			// (UI)기간 시작 값
			start_init = $("input:text[name='sInitDate']").val();
			// (UI)기간 끝 값
			end_init = $("input:text[name='eInitDate']").val();
		}
		
    	// 검증지수 선택 값 배열
    	var vrfy_idx = new Array();
    	$("input[name=VRFY_INDEX]:checked").each(function() {
    		vrfy_idx.push($(this).val());
    	});
        	// if( vrfy_idx.length < 1 ) {
    		// 	alert("한개 이상의 검증지수를 선택해 주십시오");
    		// 	return false;
        	// }

		if( peri == "FCST") {
			const ajax_url = '<?php echo site_url();?>/main/getStnFcstNum';
			callAjaxUtcInfo(peri, ajax_url, data_head, var_select, init_hour, model_sel, start_init, end_init, bangjae_date, vrfy_idx);
		}  else if( peri == "BANGJAE" ) {
			const ajax_url = '<?php echo site_url();?>/main/getStnBangjaeNum';
			callAjaxUtcInfo(peri, ajax_url, data_head, var_select, init_hour, model_sel, start_init, end_init, bangjae_date, vrfy_idx);
			// callAjaxBangjaeInfo(ajax_url_bangjae, data_head, var_select, init_hour, model_sel, vrfy_idx, peri, bangjae_date, vrfy_idx);
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

	// 2023-05-30
	// 활용도 하나만 보여주기
    function setUtilize(val) {
    	$('input[name=UTILIZE_INDEX]').each(function() {
			this.checked = false;
		});

    	$("input[name=UTILIZE_INDEX][value='" + val + "']").prop("checked", true);
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
                		
                		// 활용도 셀렉트박스 생성.
						makeVrfySelectUtilizeMap(utilizeTech['data_vrfy'], utilizeTech['txt_vrfy'], pType, dateType);

                		// 검증 지수 셀렉트박스 생성.
						makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);

                    	// 초기시각 세팅.
						// 1시간 단기 자료 표출로 인하여 00+12UTC는 표출 안하고, 멀티 선택 막음. 2021-02-25
						//makeUTCopt(val.value, dateType);
						
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
//  예측기간별 자료  Ajax 이용 그래픽 표출 메서드.
	function callAjaxUtcInfo(peri, ajax_url, data_head, var_select, init_hour, model_sel, start_init, end_init, bangjae_date, vrfy_idx) {

console.log('data_head', data_head);

		var sd = start_init.substr(0,4) + "-" + start_init.substr(4,2) + "-01";

		// 예측시간이 1시간 또는 3시간 자료인지 확인 후 변수 이름 변경. (shrt_change_3to1_func.js 로 함수 옮김. 2021-03-31)
		// var var_change = changeVrfyByVar(start_init, var_select);
		var var_change = var_select;

		let data_arr = new Array();
		if( peri === "FCST" ) {
			data_arr = {
				"data_head" : data_head,
				"var_select" : var_change,
				"init_hour" : init_hour,
				"model_sel" : model_sel,
				"start_init" : start_init,
				"end_init" : end_init,
				"vrfy_idx" : vrfy_idx,
				"peri" : peri
			};
		} else if( peri === "BANGJAE" ) {
			data_arr = {
				"data_head" : data_head,
				"var_select" : var_change,
				"init_hour" : init_hour,
				"model_sel" : model_sel,
				"vrfy_idx" : vrfy_idx,
				"peri" : peri,
				"bangjae_date" : bangjae_date
			}
		}

    	$.ajax({
            type : "POST",
                data :
                // {
    			// 	"data_head" : data_head,
    			// 	//"var_select" : var_select,
    			// 	"var_select" : var_change,
    			// 	"init_hour" : init_hour,
    			// 	"model_sel" : model_sel,
    			// 	"start_init" : start_init,
    			// 	"end_init" : end_init,
    			// 	"vrfy_idx" : vrfy_idx,
    			// 	"peri" : peri
                // },
				data_arr,
                dataType: "json",
                url : ajax_url,
    			// 변수에 저장하기 위함.
                async: false,
                success : function(resp)
                {

// console.log(data_head);
console.log('resp', resp);
console.log('vrfy_idx', vrfy_idx);
console.log('vrfy_title', vrfy_title);

					// 그래프 표출 영역 초기화.
                	$('#fcstValue').empty();
                	$('#contValue').empty();

					let viewTable = "";
					const thead_txt = ["수치예보 가이던스", "예보편집", "차이분포<br>(수치예보가이던스 - 예보편집)", "활용도"];

					let utc_spilit = init_hour[0].split("#");
					let utc = utc_spilit[0] + "UTC";

					viewTable += "<table style='border: 1px solid #6E7783; /*width:90%;*/ margin-left: 45px; margin-top: 30px;'>";
					
					viewTable += "<tr>";
						// viewTable += "<thead style='text-align: center; height:70px; background-color:#4F86C6; font-size: medium; font-weight:bold; '>";
						viewTable += "<thead style='text-align: center; height:70px; background-color:#84B1ED; font-size: medium; font-weight:bold; '>";
							for( let t=0; t<thead_txt.length; t++ ) {
								if( t == 0 ) {
									viewTable += "<td style='border: 1px solid #6E7783;'>";
									viewTable += "</td>";
								}
								viewTable += "<td style='border: 1px solid #6E7783;'>";
								viewTable += thead_txt[t];
								viewTable += "</td>";
							}
						viewTable += "</thead>";
					viewTable += "<tr>";

					for( let mon=0; mon<resp['date_info'].length; mon++ ) {
						for( let vrfy=0; vrfy<vrfy_idx.length; vrfy++ ) {
							for( let modl=0; modl<model_sel.length; modl++ ) {
								
								let modl_split = model_sel[modl].split("_");
								let modl_txt = modl_split[0];

								viewTable += "<tr>";
								
								for( let i=0; i<4; i++ ) {
									if( i == 0 ) {
										// viewTable += "<td style='border: 1px solid #6E7783; width: 100px; height:390px; text-align:center; font-size: small; font-weight:bold; '>";
										viewTable += "<td style='border: 1px solid #6E7783; width: 100px; height:510px; text-align:center; font-size: small; font-weight:bold; '>";
										// viewTable += vrfy_idx[vrfy] + "<br>" + resp['date_info'][mon]['ymInfo'] + "<br>" + model_sel[modl];
										viewTable += 
													// resp['date_info'][mon]['ymInfo'] + "<br><br>" + 
													// vrfy_idx[vrfy] + "<br><br>" + 
													// utc + "<br><br>" + 
													// modl_txt;
													resp['date_info'][mon]['ymInfo'] + "<br><br>" + 
													modl_txt + "<br><br>" + 
													$("[name=VAR] option:selected").text() + "<br>" + 
													// assets/js/vrfy_js/common_func.js
													get_vrfy_title(vrfy_data, vrfy_title, vrfy_idx[vrfy]) + "<br><br>" + 
													utc + "<br><br>";
										viewTable += "</td>";
									}
									viewTable += "<td style='border: 1px solid #6E7783; text-align: center; width:340px;'>";
									// // viewTable += vrfy_idx[vrfy] + "<br>" + resp['date_info'][mon]['ymInfo'] + "<br>" + model_sel[modl];
									// viewTable += resp['date_info'][mon]['ymInfo'] + "<br>" + vrfy_idx[vrfy] + "<br>" + model_sel[modl];

									// 첫번째 수치예보 가이던스 이미지는 단기 공간분포 이미지.
									// const head_dir_path = (i == 0) ? vrfyGraphDirHead.split("./")[1] : graphDirHead.split("./")[1];

									let head_dir_path = "";
									if( peri === "FCST" ) {
										head_dir_path = graphDirHead.split("./")[1];
									} else if( peri === "BANGJAE" ) {
										head_dir_path = bangjaeGraphDirHead.split("./")[1];
									}

									const graph_url = "<?php echo base_url('/'); ?>" + head_dir_path;
									const tail_path = resp['date_info'][mon]['ymInfo'] + "/" + var_select + "/";

									const directory_full_path = graph_url + tail_path;

									const utilize = $("input[name=UTILIZE_INDEX]:checked").val();
									const data_date = resp['date_info'][mon]['data'];
									const graph_name = getGraphName(i, data_head, model_sel[modl], var_select, vrfy_idx[vrfy], utilize, data_date);

console.log("+++++++++++++++++++++++");
// console.log('graphDirHead', graphDirHead);
// console.log('data_head', data_head);
// console.log('model_sel', model_sel[modl]);
// console.log('var_select', var_select);
// console.log('vrfy_idx', vrfy_idx);
// console.log('utilize', utilize);
// console.log('data_date', data_date);
// console.log('graph_name', graph_name);
console.log('full name', directory_full_path + graph_name);

									// const nameOfgrph = 
									// 					resp['date_info'][mon]['ymInfo'] + "_" + 
									// 					vrfy_idx[vrfy] + "_" + 
									// 					utc + "_" + 
									// 					model_sel[modl] + "_" +
									// 					(i+1) + "_AVE.png";
									// const nameOfgrph = data_head + model_sel[m] + "_" + var_select + "_VRFY_" + vrfy_idx[v] + "." + resp['date_info'][d]['data'] + "_" +  resp['fcst_info']['utc_idx'][idx] + ".png";
// console.log("resp['date_info'][i]['ymInfo']", resp['date_info'][i]['ymInfo']);
// console.log('nameOfgrph', nameOfgrph);
									// viewTable += "<img class='grph_img' style='width:330px; height:380px;' src='" + "<?php //echo base_url('/'); ?>" + graphDirHead + resp['date_info'][mon]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
									viewTable += "<img class='grph_img' style='width:330px; height:500px;' src='" + directory_full_path + graph_name + "' onerror='no_image(this);' />" ;


									viewTable += "</td>";
								}
								
								viewTable += "</tr>";
							}
						}
					}					
					
					viewTable += "</table>";

					$('#contValue').append(viewTable);



					// // 표출 항목이 있을 경우 start.
					// if( resp["fcst_info"] ) {

					// 	//fcstTable = "<div class='col-lg-10'>";
					// 	fcstTable = "";
						
					// 	fcstTable += "<table class='map_utc_table' >";
						
					// 	// 2021-02-24 add by joshua.
					// 	if( shrtMonthCheck(sd) == true || ( var_change == "TMX" || var_change == "TMN" ) ) {

					// 		fcstTable += "<tr>";
					// 		for(var i=0; i<resp['fcst_info']['utc_txt'].length; i++) {
					// 				fcstTable += "<td class='sliderLabel' id='ImageL_" + i + "'>" + resp['fcst_info']['utc_txt'][i] + "</td>";
					// 			}
					// 		fcstTable += "</tr>";
							
					// 		fcstTable += "<tr>";
					// 			for(var i=0; i<resp['fcst_info']['utc_txt'].length; i++) {
					// 				fcstTable += "<td class='slider' id='Image_" + i + "' title='" + resp['fcst_info']['utc_txt'][i] + "' > &nbsp; </td>";
					// 			}
					// 		fcstTable += "</tr>";
							
					// 		fcstTable += "</table>";
							
					// 		fcstTable += "</div>";

					// 	// 2021-02-24 add by joshua.
					// 	// 2020-12월 이 후 자료일 경우.	
					// 	} else {

					// 		// assets/js/vrfy_js/map_slider/make_map_slider.js
					// 		if( data_head === "DFS_SHRT_STN_" ) {
					// 			fcstTable += makeShrtSliderTile(init_hour, resp['fcst_info']['utc_txt']);
					// 		} else if( data_head === "DFS_MEDM_STN_" ) {
					// 			fcstTable += makeMedmSliderTile(init_hour, resp['fcst_info']['utc_txt']);
					// 		}
					// 	}

						
					// 	$('#fcstValue').append(fcstTable);

					// 	maxstep = resp['fcst_info']['utc_txt'].length -1;
						
					// 	// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
					// 	display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);

						
					// 	//setSlideAt(0);
					// 	// 슬라이드바를 직접 클릭할때 호출되는 함수
					// 	$("td[id^='Image_']").click(function() {
					// 		idx = $(this).attr("id").substr(6);
					// 		ImgIndex = idx;
							
					// 		// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
					// 		display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);
					// 	});

					// 	// 한번 클릭 시 여러번 실행되는 문제(여러번 콜했을 경우)를 방지.
					// 	$("body").off('keydown');
					// 	// 좌우 화살표 키
					// 	$("body").keydown(function(e) {
					// 		if(e.keyCode == 37) { // left
					// 			idx = idx*1 -1;
					// 			if (idx < 0){
					// 				idx = maxstep;
					// 			}
					// 			// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
					// 			display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);
								
					// 		} else if(e.keyCode == 39) { // right
					// 			idx = idx*1 +1;

					// 			if (idx > maxstep ){
					// 				idx = 0;
					// 			}
					// 			// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
					// 			display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);
					// 		}
					// 	});
						
					// }
					// // 표출 항목이 있을 경우 End of if.

                }, // End of "success : function(resp)"
                error : function(error) 
                {
                    alert("error");
                    console.log(error);
                }
        })
		
	}


	function getGraphName(number, data_head, model, var_select, vrfy_idx, utilize, data_date) {

		const split_model = model.split("_");
		let def_model = "";
		if( split_model[0] === "BEST" ) {
			def_model = split_model[0] + "_MERG";
		} else {
			def_model = split_model[0] + "_NPPM";
		}

		let graph_name = data_head;
		switch (number) {
			case 0 : graph_name += def_model + "_" + var_select + "_VRFY_" + vrfy_idx + "." + data_date + "_AVE.png";		break;
			case 1 : graph_name += split_model[1] + "_" + var_select + "_VRFY_" + vrfy_idx + "." + data_date + "_AVE.png";	break;
			case 2 : graph_name += model + "_" + var_select + "_DIFF_" + vrfy_idx + "." + data_date + "_AVE.png";			break;
			case 3 : graph_name += model + "_" + var_select + "_" + utilize + "." + data_date + "_AVE.png";					break;
		}

		return graph_name;
	}









///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  방재기간 자료  Ajax 이용 그래픽 표출 메서드.
	function callAjaxBangjaeInfo(ajax_url_bangjae, data_head, var_select, init_hour, model_sel, vrfy_idx, peri, bangjae_date, vrfy_idx) {

console.log('data_head', data_head);
console.log('bangjae', bangjae_date);

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

console.log('resp', resp);
console.log('vrfy_idx', vrfy_idx);
console.log('vrfy_title', vrfy_title);

					// 그래프 표출 영역 초기화.
                	$('#fcstValue').empty();
                	$('#contValue').empty();

					let viewTable = "";
					const thead_txt = ["수치예보 가이던스", "예보편집", "차이분포<br>(수치예보가이던스 - 예보편집)", "활용도"];

					let utc_spilit = init_hour[0].split("#");
					let utc = utc_spilit[0] + "UTC";

					viewTable += "<table style='border: 1px solid #6E7783; /*width:90%;*/ margin-left: 45px; margin-top: 30px;'>";
					
					viewTable += "<tr>";
						viewTable += "<thead style='text-align: center; height:70px; background-color:#84B1ED; font-size: medium; font-weight:bold; '>";
							for( let t=0; t<thead_txt.length; t++ ) {
								if( t == 0 ) {
									viewTable += "<td style='border: 1px solid #6E7783;'>";
									viewTable += "</td>";
								}
								viewTable += "<td style='border: 1px solid #6E7783;'>";
								viewTable += thead_txt[t];
								viewTable += "</td>";
							}
						viewTable += "</thead>";
					viewTable += "<tr>";

					for( let mon=0; mon<resp['date_info'].length; mon++ ) {
						for( let vrfy=0; vrfy<vrfy_idx.length; vrfy++ ) {
							for( let modl=0; modl<model_sel.length; modl++ ) {
								
								let modl_split = model_sel[modl].split("_");
								let modl_txt = modl_split[0];

								viewTable += "<tr>";
								
								for( let i=0; i<4; i++ ) {
									if( i == 0 ) {
										viewTable += "<td style='border: 1px solid #6E7783; width: 100px; height:510px; text-align:center; font-size: small; font-weight:bold; '>";
										viewTable += 
													resp['date_info'][mon]['ymInfo'] + "<br><br>" + 
													modl_txt + "<br><br>" + 
													$("[name=VAR] option:selected").text() + "<br>" + 
													// assets/js/vrfy_js/common_func.js
													get_vrfy_title(vrfy_data, vrfy_title, vrfy_idx[vrfy]) + "<br><br>" + 
													utc + "<br><br>";
										viewTable += "</td>";
									}
									viewTable += "<td style='border: 1px solid #6E7783; text-align: center; width:340px;'>";

									// 첫번째 수치예보 가이던스 이미지는 단기 공간분포 이미지.
									const head_dir_path = bangjaeGraphDirHead.split("./")[1];
									const graph_url = "<?php echo base_url('/'); ?>" + head_dir_path;
									const tail_path = resp['date_info'][mon]['ymInfo'] + "/" + var_select + "/";

									const directory_full_path = graph_url + tail_path;

									const utilize = $("input[name=UTILIZE_INDEX]:checked").val();
									const data_date = resp['date_info'][mon]['data'];
									const graph_name = getGraphName(i, data_head, model_sel[modl], var_select, vrfy_idx[vrfy], utilize, data_date);

console.log("+++++++++++++++++++++++");
// console.log('graphDirHead', graphDirHead);
// console.log('data_head', data_head);
// console.log('model_sel', model_sel[modl]);
// console.log('var_select', var_select);
// console.log('vrfy_idx', vrfy_idx);
// console.log('utilize', utilize);
// console.log('data_date', data_date);
// console.log('graph_name', graph_name);
console.log('full name', directory_full_path + graph_name);

									viewTable += "<img class='grph_img' style='width:330px; height:500px;' src='" + directory_full_path + graph_name + "' onerror='no_image(this);' />" ;


									viewTable += "</td>";
								}
								
								viewTable += "</tr>";
							}
						}
					}					
					
					viewTable += "</table>";

					$('#contValue').append(viewTable);

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
		$modlData = [
			"vrfyTypeName" =>$vrfyTypeName,
			"modltech_info" => $modltech_info
		];
		$this->load->view('common/sideMenu/modlSelectBoxGEMD', $modlData);
	?>

	<!-- 초기시각 선택 -->
	<?php $this->load->view('common/sideMenu/utcSelectBoxMap'); ?>
	
</div>   

