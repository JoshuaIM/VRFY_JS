<!-- // 지점의 id를 매칭하여 지점 이름을 가져온다. 또한 지점 선택 selectBox를 통해 세부 지역을 선택 할 수 있도록 리스팅 한다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_station_name.js');?>"></script>
<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_grph_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 월 자료(기본: 임의기간이 아닌)를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/month_common_func.js');?>"></script>
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>

<!-- // CSV 파일 생성에 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/makeCSVfile.js');?>"></script>

<!-- // 2020년 12월 기준 이전 날짜에는 단기 3시간 자료 이후에는 단기 1시간 자료를 표출하기 위해 사용되는 함수. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/shrt_change_3to1_func.js');?>"></script>
	
<!-- // -->
	<script src="<?php echo base_url('assets/js/vrfy_js/bangjae.js');?>"></script>



<script type="text/javascript">
// 사이트 확인 (month or arbi)
var dateType = "<?php echo $dateType; ?>";

// 그래픽 Title의 검증지수 한글화.
var vrfy_data;
var vrfy_txt;
var vrfy_title;

// 최신 날짜 (디렉터리 검사 후 최신 날짜로 세팅.)
// assets/js/vrfy_js/month_common_func.js - changeDatePicker() 에서 초기화 시 사용.
let currentStrDate = "<?php echo $dataDate; ?>";			
let currentEndDate = "<?php echo $dataDate; ?>";			

// 방재기간 선택 가능 연도 배열.
let springYear = [<?php echo '"'.implode('","', $SPRING).'"' ?>];
let winterYear = [<?php echo '"'.implode('","', $WINTER).'"' ?>];

let glob_data = new Array();
    
    // 첫 페이지 기능.
    $(document).ready(function(){

		// 방재기간 year select box 생성.
		// makeBangjaeYearAjax();

    	readyAndNowFunc();
    });


    // 첫 페이지 또는 NOW 버튼 클릭 시 실행.
    function readyAndNowFunc() {
    	changeDatePicker(null, null);

	 	// 단기-격자-시계열(shrt_ts_grd)는 검증지수 선택이 단일 선택이므로 pType으로 함수 분류.
		var pType = "<?php echo $vrfyType; ?>";
		
    	// PHP array to Javascript array
			vrfy_data = [<?php echo '"'.implode('","', $vrfyTech['data_vrfy']).'"' ?>];
			vrfy_txt = [<?php echo '"'.implode('","', $vrfyTech['txt_vrfy']).'"' ?>];
			vrfy_title = [<?php echo '"'.implode('","', $vrfyTech['title_vrfy']).'"' ?>];

			
		// 검증 지수 셀렉트박스 생성.
    	makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);
    	
    	getDataArray();
    }


    // 데이터 표출 시 UI 정보 수집 함수.
    function getDataArray() {
    	// 데이터 네임 앞 글자. 단기-지점
    	var data_head = "<?php echo $dataHead; ?>";
		// 표출 종류.
    	// var map_type = "ts";
    	
    	// (UI)요소 선택 값
    	var var_select = $("select[name=VAR]").val();
        
		// TODO: 2021-05-28 초기시각 UTC 중복 선택 막음.
    	// // (UI)초기시각 UTC 선택 값 (중복 선택)
    	// var init_hour = new Array();
    	// $("input[name=INIT_HOUR]:checked").each(function() {
    	// 	init_hour.push($(this).val());
    	// });
        // 	if( init_hour.length < 1 ) {
    	// 		alert("한개 이상의 초기시각을 선택해 주십시오");
    	// 		return false;
        // 	}
		// (UI)초기시각 UTC 선택 값 (중복 불가)
    	var init_hour = new Array();
		// 2021-05-28 (잠시동안)
    	// 00 또는 12UTC 하나만 보여주기로 변경.
    	$("input[name=INIT_HOUR]:checked").each(function() {
    		init_hour.push($(this).val());
    	});
    	// $("input[name=INIT_HOUR]:checked").each(function() {
    	// 	init_hour.push($(this).val());
    	// });
        // 	if( init_hour.length < 1 ) {
    	// 		alert("한개 이상의 초기시각을 선택해 주십시오");
    	// 		return false;
        // 	}
    



    	// (UI)모델 및 기법 선택 값 (중복 선택)
    	var model_sel = new Array();
    	$("input[name=MODEL_TECH]:checked").each(function() {
    		model_sel.push($(this).val());
    	});
        	if( model_sel.length < 1 ) {
    			alert("한개 이상의 모델을 선택해 주십시오");
    			return false;
        	}
    
    	// (UI)지점 선택 값
    	var location =  new Array();
		var selLoc = $("select[name=LOCATION]").val(); 
			if( selLoc.length > 1) {
				alert("지점선택은 하나만 가능합니다.");
				return false;
			}
			if( selLoc == "ALL" ) {
				location.push( "AVE" );
			} else {
				location = selLoc[0].split('#');
			}
    
    	// (UI)기간 선택 값
    	var peri = $("select[name=PERIOD]").val();
    
		// if( peri == "SPRING" || peri == "WINTER" ) {
		// 	// assets/js/vrfy_js/bangjae.js
		// 	bangjaeON();
			
		// 	let yearArr = getBangjaeYearAjax(peri);
			
			
			
			
		// } else {
		// 	// assets/js/vrfy_js/bangjae.js
		// 	bangjaeOFF();

		// 	// (UI)기간 시작 값
		// 	var start_init = $("input:text[name='sInitDate']").val()
		// 	// (UI)기간 끝 값
		// 	var end_init = $("input:text[name='eInitDate']").val()
		// }
		
		// (UI)기간 시작 값
		var start_init = $("input:text[name='sInitDate']").val()
		// (UI)기간 끝 값
		var end_init = $("input:text[name='eInitDate']").val()

    	// (UI)검증지수 선택 값 (중복 선택)
    	var vrfy_idx = new Array();
    	$("input[name=VRFY_INDEX]:checked").each(function() {
    		vrfy_idx.push($(this).val());
    	});
        	if( vrfy_idx.length < 1 ) {
    			alert("한개 이상의 검증지수를 선택해 주십시오");
    			return false;
        	}

        if( peri == "FCST" ) {
    		// callAjaxFcstData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, map_type);
    		callAjaxFcstData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx);
        } else if( peri == "MONTH" ) {
			// callAjaxMonthData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, map_type);
    		callAjaxMonthData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx);
        } else if( peri == "SPRING" || peri == "WINTER" ) {
    		//callAjaxSpringData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx);
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

	

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  예측기간별 자료  Ajax 이용 그래픽 표출 메서드.
	// function callAjaxFcstData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, map_type) {
	function callAjaxFcstData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx) {

		// 예측시간이 1시간 또는 3시간 자료인지 확인 후 변수 이름 변경. (shrt_change_3to1_func.js 로 함수 옮김. 2021-03-31)
		var var_change = changeVrfyByVar(start_init, var_select);
		
    	$.ajax({
            type : "POST",
                data :
                {
    				"data_head" : data_head,
    				//"var_select" : var_select,
    				"var_select" : var_change,
    				"init_hour" : init_hour,
    				"model_sel" : model_sel,
    				"location" : location,
    				"start_init" : start_init,
    				"end_init" : end_init,
    				"vrfy_idx" : vrfy_idx,
    				//"map_type" : map_type
    				// "map_type" : "tb"
                },
                dataType: "json",
                url : '<?php echo site_url();?>/main/getStnFcstData',
    			// 변수에 저장하기 위함.
                async: false,
                success : function(resp)
                {
console.log(resp);
// console.log(glob_data);

					// csv 내려받기 기능을 위해 데이터 값 광역변수에 저장.
					glob_data = resp;

					// 그래프 표출 영역 초기화.
                	$('#contValue').empty();
					
					// 그래프 표출 개수 (지점X검증지수)
					for(var vl=0; vl<resp.length; vl++) {

						// 지점X검증지수 가 없을 경우 표출 안하고 넘어감.
						if( resp[vl]['data'].length == 0 ) {
							continue;
						// 지점X검증지수 표출.
						} else {

    						var selCont = "";
							var dataFontClass = "";
    					    
    						// 검증지수 * 지점 개수 만큼 차트DIV 생성.
							// 2021-01-22 add by joshua.
                            //selCont += "<div class='col-lg-11 mb'>";
                            //selCont += "<div class='col-lg-12 mb'>";
                            //selCont += "<div class='col-lg-1h mb'>";

							//selCont += setShrt3H1HDisplay(start_init, end_init, var_select); 
							//selCont += setShrt3H1HDisplay(start_init, end_init, var_change); 
							var selContNdataFont = setShrt3H1HDisplay(start_init, end_init, var_change); 
							var arrContNFont = selContNdataFont.split("||");

							selCont += arrContNFont[0];
							dataFontClass = arrContNFont[1];
							
                            selCont += "<div class='white-panel' >";
                            selCont += "<div class='white-header'>";
    
    						var vftc = resp[vl]['vrfy_loc'].split("_");
							var stn_name = get_station_name( vftc[1] );
							var vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vftc[0]);
							
							// 2021-01-25 add by joshua.
                            //selCont += "<h5><b class='chartName'>" + resp[vl]['var_name'] + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]</b></h5>";
                            selCont += "<h5><b class='chartName'>" + var_change + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]</b></h5>";
                            
                            selCont += "</div>";
    						// 검증지수 * 지점 개수 만큼 차트DIV Header 끝.

						//---- 집계표 테이블 시작
                			selCont += "<table class='fcstTable'>";
        					selCont += "<tr class='tb_head'>";
							selCont += "<td class='td_lg'>년월</td><td class='td_lg'>UTC</td><td class='td_lg'>모델 - 기법</td><td class='td_lg'>자료수</td>";

    	    					// 예측시간 표출
        						for(var u=0; u<resp[vl]['data'][0]['fHeadUtc'].length; u++) {
                					selCont += "<td class='td_sm'>" + resp[vl]['data'][0]['fHeadUtc'][u] + "</td>"
                				}
                        				
    							selCont += "<td class='td_sm'>AVE</td></tr>";
        
    	    					// 예측시간 데이터 표출
    							for(var mm=0; mm<resp[vl]['data'].length; mm++) {

									selCont += "<tr class='tb_data'>";
									selCont += "<td>" + resp[vl]['data'][mm]['month'] + "</td>" +
									"<td>" + resp[vl]['data'][mm]['utc'].replace("UTC","") + "</td>" + 
									"<td>" + resp[vl]['data'][mm]['model'] + "</td>" + 
									"<td>" + resp[vl]['data'][mm]['fDataNum'] + "</td>";
									
            						for(var d=0; d<resp[vl]['data'][mm]['data'].length; d++) {
    									if( resp[vl]['data'][mm]['data'][d] == null ) {
            									selCont += "<td> </td>";
    									} else {
    										// 강수확률은 값의 /100 적용.
    										if( var_select == "POP" ) {
            									selCont += "<td class='" + dataFontClass + "'>" + (resp[vl]['data'][mm]['data'][d]/100).toFixed(3) + "</td>";
    										} else {
            									selCont += "<td class='" + dataFontClass + "'>" + resp[vl]['data'][mm]['data'][d] + "</td>";
												
												// // TODO: 2021-06-01 소수점 둘째자리 반올림
            									// selCont += "<td class='" + dataFontClass + "'>" + resp[vl]['data'][mm]['data'][d].toFixed(1) + "</td>";
    										}
    									}
                    				}
        
        							selCont += "</tr>";
    							} // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)" 

    						selCont += "</table>";    						
						//---- 집계표 테이블 끝

						//---- 시계열 차트 시작
							selCont += "<div id='" + resp[vl]['vrfy_loc'] + "_div' class='cht_div'></div>";
    						
                            selCont += "</div></div>";
                            
        					// 검증지수 * 지점 개수 만큼 차트DIV 생성 끝.
        					$('#contValue').append(selCont);

							var sp_vl = resp[vl]['vrfy_loc'].split("_");
							var vrfy_id = sp_vl[0];

							var var_unit = get_grph_unit(var_select, vrfy_id);
        						var vUnit = var_unit.split("#");
    						var unitName = vUnit[0];
    						var unitSymb = vUnit[1];

    						// 그래픽 Y축 Title 정보.
							if( unitSymb ) {
								var yaxis_title = unitName + " ( " + unitSymb + " )";
							} else {
								var yaxis_title = unitName;
							}
    						
        					// 하이차트 표출. (데이터가 없는 껍데기)
                            $('#' + resp[vl]['vrfy_loc'] + "_div").highcharts({
        						chart: {
        							defaultSeriesType: 'line',
        							renderTo: 'container'
        						},                                	
                            	   title: {
                            	        text: ''
                            	    },
                            	    subtitle: {
                            	        text: ''
                            	    },
                            	    yAxis: {
                            	        title: {
                            	            text: yaxis_title
                            	        }
                            	    },
                            	    xAxis: {
                            	        title: {
                            	            text: 'time(H)'
                            	        },
                            	        categories: resp[vl]['data'][0]['fHeadUtc']
                            	    },
                            	    legend: {
                            	        layout: 'horizontal',
                            	        // align: 'center',
                            	        align: 'left',
                            	        verticalAlign: 'bottom'
                            	    },
                            
                            	    plotOptions: {
                            	        series: {
                            	            label: {
                            	                connectorAllowed: false
                            	            },
                            	        }
                            	    },
                            		tooltip: {
										valueSuffix: " " + unitSymb,
        								crosshairs: true,                              		
        								shared: true
                            		},
                            	    series: [{}],
                            	    responsive: {
                            	        rules: [{
                            	            condition: {
                            	                maxWidth: 500
                            	            },
                            	            chartOptions: {
                            	                legend: {
                            	                    align: 'center',
                            	                    verticalAlign: 'bottom',
                            	                    layout: 'horizontal',
                            	                }
                            	            }
                            	        }]
                            	    },
                            	    exporting: {
                            			enabled: false
                                 }
                             });
					

							// 모델X초기시각X기간 값을 하이차트에 Append 하기 위해 객체 생성.
                    		var chart = $('#' + resp[vl]['vrfy_loc'] + "_div").highcharts();
        
        					// 하나의 차트에 들어갈 데이터 라인의 수.
        					var cht_line_num = resp[vl]['data'].length;

        					// TODO : 데이터가 하나도 없을 시. ( 위에 "if( resp[vl]['data'].length == 0 )" 를 추가했으므로 필요없을 수 있음 )
        					if( cht_line_num == 0 ) {
                				chart.series[0].name= "No Data";
                    			chart.series[0].setData([], true);
							// 데이터가 하나 일 경우 series의 name이 제대로 기입이 안되므로 억지로 집어넣어준다.
        					} else if( cht_line_num == 1 ) {

                    			// 강수확률은 값의 /100 적용.
								if( var_select == "POP" ) {
									// IE에서 .map() 함수 사용 못함.
    								var chtdata = new Array();
    								var pop_d = resp[vl]['data'][0]['data'];
    									for(var x=0; x<pop_d.length; x++) {
											if( pop_d[x] ) {
												chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
											} else {
												chtdata.push( pop_d[x] );
											}
    									}
								} else {
									var chtdata = resp[vl]['data'][0]['data'];
								}
            					
                            	// // 집계표에 사용되는 AVE값까지 포함되어 있으므로 시계열 표출 시 삭제 함. 
                            	// chtdata.pop();
                            	// chtdata.splice(chtdata.length - 1);
								// 원본 배열 값이 같이 삭제되므로 아래 필터함수 사용해서 사용 2023-01-12
								let length = chtdata.length;
								let chtdata2 = chtdata.filter((number, index) => {
									return index < length-1;
								});
								chtdata = chtdata2;
                            	
                    			var lineName = resp[vl]['data'][0]['month'] + "_" + resp[vl]['data'][0]['utc'] + "_" + resp[vl]['data'][0]['model'];
								// 모델 컬러 추가. 2023-01-11
								let modl_color = resp[vl]['data'][0]['modl_color'];
        						
                    			chart.series[0].setData( chtdata, true );
								// 모델 컬러 추가. 2023-01-11
                				chart.series[0].update({name: lineName, color: modl_color}, false);
                    			chart.redraw();

        					} else {

                            	for(var mm=0; mm<resp[vl]['data'].length; mm++) {

                        			// 강수확률은 값의 /100 적용.
    								if( var_select == "POP" ) {
	    								var chtdata = new Array();
    									var pop_d = resp[vl]['data'][mm]['data'];
        									for(var x=0; x<pop_d.length; x++) {
												if( pop_d[x] ) {
    												chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
												} else {
    												chtdata.push( pop_d[x] );
												}
        									}
    								} else {
                                    	var chtdata = resp[vl]['data'][mm]['data'];
    								}
                                	
                                	// 집계표에 사용되는 AVE값까지 포함되어 있으므로 시계열 표출 시 삭제 함. 
                                	// chtdata.pop();
									// chtdata.splice(chtdata.length - 1);
									// chtdata.splice(chtdata.length - 1);
									// 원본 배열 값이 같이 삭제되므로 아래 필터함수 사용해서 사용 2023-01-12
									let length = chtdata.length;
									let chtdata2 = chtdata.filter((number, index) => {
										return index < length-1;
									});
									chtdata = chtdata2;

                        			var lineName = resp[vl]['data'][mm]['month'] + "_" + resp[vl]['data'][mm]['utc'] + "_" + resp[vl]['data'][mm]['model'];
									// 모델 컬러 추가. 2023-01-11
									let modl_color = resp[vl]['data'][mm]['modl_color'];
									
                        			if( mm == 0 ) {
										chart.series[0].name= lineName;
										// 모델 컬러 추가. 2023-01-11
										chart.series[0].color= modl_color;
                            			chart.series[0].setData(chtdata, false);
                        			} else if( mm == (resp[vl]['data'].length -1) ) {
										// 모델 컬러 추가. 2023-01-11
                            			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, true);
                        			} else {
										// 모델 컬러 추가. 2023-01-11
                            			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, false);
                        			}
                            	
                            	}

							}

						} // End of "if( resp[vl]['data'].length == 0 )"
                        
					} // End of "for(var vl=0; vl<resp.length; vl++)"
					
                }, // End of "success : function(resp)"
                error : function(error) 
                {
                    alert("data error");
                    console.log(error);
                }
        })
		
	}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  월별 자료  Ajax 이용 그래픽 표출 메서드.
	// function callAjaxMonthData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx, map_type) {
	function callAjaxMonthData(data_head, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx) {

		// 예측시간이 1시간 또는 3시간 자료인지 확인 후 변수 이름 변경.
		var var_change = changeVrfyByVar(start_init, var_select);
		

    	$.ajax({
            type : "POST",
                data :
                {
    				"data_head" : data_head,
    				//"var_select" : var_select,
    				"var_select" : var_change,
    				"init_hour" : init_hour,
    				"model_sel" : model_sel,
    				"location" : location,
    				"start_init" : start_init,
    				"end_init" : end_init,
    				"vrfy_idx" : vrfy_idx,
    				// "map_type" : map_type
                },
                dataType: "json",
                url : '<?php echo site_url();?>/main/getStnMonthData',
    			// 변수에 저장하기 위함.
                async: false,
                success : function(resp)
                {
// console.log(resp);

                	$('#contValue').empty();

					for(var vl=0; vl<resp.length; vl++) {

						var chkDataExist = 0;
							for( var t=0; t<resp[vl]['data'][0]['data'].length; t++ ) {
								if( resp[vl]['data'][0]['data'][t] ) {
									chkDataExist = chkDataExist +1;
								}
							}

						// 지점X검증지수 가 없을 경우 표출 안하고 넘어감. 
						if( chkDataExist < 1 ) {
							continue;
						// 지점X검증지수 표출.
						} else {
							
    						var selCont = "";

    						// 검증지수 * 지점 개수 만큼 차트DIV 생성.
                            selCont += "<div class='col-lg-11 mb'>";
                            selCont += "<div class='white-panel' >";
                            selCont += "<div class='white-header'>";
    
    						var vftc = resp[vl]['vrfy_loc'].split("_");
    						var stn_name = get_station_name( vftc[1] );
    						var vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vftc[0]);
    						
                            selCont += "<h5><b class='chartName'>" + resp[vl]['var_name'] + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]</b></h5>";
                            
                            selCont += "</div>";
    						// 검증지수 * 지점 개수 만큼 차트DIV Header 끝.
    						
						//---- 집계표 테이블 시작
                			selCont += "<table class='monTable'>";
        					selCont += "<tr class='tb_head'>";
							selCont += "<td class='td_lg'>모델 - 기법</td><td class='td_lg'>UTC</td>";

    							for(var d=0; d<resp[vl]['data'][0]['mon_range'].length; d++) {
									selCont += "<td class='td_lg'>"+ resp[vl]['data'][0]['mon_range'][d] +"</td>";
    							}

							selCont += "</tr>";

							for(var m=0; m<resp[vl]['data'].length; m++) {

								// 그래픽당 모든 배열값이 null일 경우 표출 안하기 위함.
								var chk_num = 0;
								for(var chk=0; chk<resp[vl]['data'][m]['data'].length; chk++) {
									if( resp[vl]['data'][m]['data'][chk] != null ) {
										chk_num = chk_num +1;
									}
								}
								
								if( chk_num < 1 ) {
									continue;
								} else {

            						selCont += "<tr class='tb_data'>";
    
    									 selCont += "<td>" + resp[vl]['data'][m]['model'] + "</td>" + 
        											 "<td>" + resp[vl]['data'][m]['utcInfo'].replace("UTC","") + "</td>"; 
    
            							for(var d=0; d<resp[vl]['data'][m]['data'].length; d++) {
        									if( resp[vl]['data'][m]['data'][d] == null ) {
            										selCont += "<td> </td>";
        									} else {
        										// 강수확률은 값의 /100 적용.
        										if( var_select == "POP" ) {
                									selCont += "<td>" + (resp[vl]['data'][m]['data'][d]/100).toFixed(3) + "</td>";
        										} else {
                									selCont += "<td>" + resp[vl]['data'][m]['data'][d] + "</td>";

													// // TODO: 2021-06-01 소수점 둘째자리 반올림
													// selCont += "<td>" + resp[vl]['data'][m]['data'][d].toFixed(1) + "</td>";
        										}
        									}
            							}
								}
								
    							selCont += "</tr>";
							} // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)" 
    	    						
    						selCont += "</table>";
						//---- 집계표 테이블 끝
    						
						//---- 시계열 차트 시작
							selCont += "<div id='" + resp[vl]['vrfy_loc'] + "_div' class='cht_div'></div>";
    						
                            selCont += "</div></div>";
                            
        					// 검증지수 * 지점 개수 만큼 차트DIV 생성 끝.
        					$('#contValue').append(selCont);
        					
							var sp_vl = resp[vl]['vrfy_loc'].split("_");
							var vrfy_id = sp_vl[0];

							var var_unit = get_grph_unit(var_select, vrfy_id);
        						var vUnit = var_unit.split("#");
    						var unitName = vUnit[0];
    						var unitSymb = vUnit[1];

    						// 그래픽 Y축 Title 정보.
							if( unitSymb ) {
								var yaxis_title = unitName + " ( " + unitSymb + " )";
							} else {
								var yaxis_title = unitName;
							}
        					
							// 하이차트 표출. (데이터가 없는 껍데기)
                            $('#' + resp[vl]['vrfy_loc'] + "_div").highcharts({
        						chart: {
        							defaultSeriesType: 'line',
        							renderTo: 'container'
        						},                                	
                            	   title: {
                            	        text: ''
                            	    },
                            	    subtitle: {
                            	        text: ''
                            	    },
                            	    yAxis: {
                            	        title: {
                            	            text: yaxis_title
                            	        }
                            	    },
                            	    xAxis: {
                            	        title: {
                            	            text: 'Date (YYYYMM)'
                            	        },
                            	        categories: resp[vl]['data'][0]['mon_range']
                            	    },
                            	    legend: {
                            	        layout: 'horizontal',
                            	        align: 'center',
                            	        verticalAlign: 'bottom'
                            	    },
                            
                            	    plotOptions: {
                            	        series: {
                            	            label: {
                            	                connectorAllowed: false
                            	            },
                            	        }
                            	    },
                            		tooltip: {
										valueSuffix: " " + unitSymb,
        								crosshairs: true,                              		
        								shared: true
                            		},
                            	    series: [{}],
                            	    responsive: {
                            	        rules: [{
                            	            condition: {
                            	                maxWidth: 500
                            	            },
                            	            chartOptions: {
                            	                legend: {
                            	                    layout: 'horizontal',
                            	                    align: 'center',
                            	                    verticalAlign: 'bottom'
                            	                }
                            	            }
                            	        }]
                            	    },
                            	    exporting: {
                            			enabled: false
                                 }
                             }); // End of 

         					
                     		// 모델X초기시각X기간 값을 하이차트에 Append 하기 위해 객체 생성.
                    		var chart = $('#' + resp[vl]['vrfy_loc'] + "_div").highcharts();

        					// 하나의 차트에 들어갈 데이터 라인의 수.
        					var cht_line_num = resp[vl]['data'].length;
        					var cht_arr_num = new Array();
        					
								// 그래픽당 모든 배열값이 null일 경우 표출 안하기 위함.
        						for(var li=0; li<resp[vl]['data'].length; li++) {
            						var cht_line_chk = 0;
									for(var li_ck=0; li_ck<resp[vl]['data'][li]['data'].length; li_ck++) {
    									if( resp[vl]['data'][li]['data'][li_ck] != null ) {
    										cht_line_chk = cht_line_chk +1;
    									}
									}
									// 그래프 당 값 개수를 파악하여 표출을 할지 안할지 파악.
									cht_arr_num.push(cht_line_chk);			
									// 배열 값 모두 null이면 표출 그래프 개수에서 -1
									if( cht_line_chk < 1 ) {
										cht_line_num = cht_line_num-1;
									}
        						}

								
        					if( cht_line_num == 0 ) {
                				chart.series[0].name= "No Data";
                    			chart.series[0].setData([], true);
        					} else if( cht_line_num == 1 ) {

                    			// 강수확률은 값의 /100 적용.
								if( var_select == "POP" ) {
    								//var chtdata = resp[vl]['data'][0]['data'].map( x=>parseFloat( (x/100).toFixed(3) ));
									var chtdata = new Array();
									var pop_d = resp[vl]['data'][0]['data'];
    									for(var x=0; x<pop_d.length; x++) {
    										chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
    									}
								} else {
            						var chtdata = resp[vl]['data'][0]['data'];
								}
        						
                    			var lineName = resp[vl]['data'][0]['utcInfo'] + "_" + resp[vl]['data'][0]['model'];
								// 모델 컬러 추가. 2023-01-11
								let modl_color = resp[vl]['data'][0]['modl_color'];
        						
                    			chart.series[0].setData(chtdata, true);
								// 모델 컬러 추가. 2023-01-11
                				chart.series[0].update({name: lineName, color: modl_color}, false);
                    			chart.redraw();
                    			
        					} else {

                            	for(var mm=0; mm<resp[vl]['data'].length; mm++) {

                                	if( cht_arr_num[mm] < 1 ) {
                                    	continue;
                                	} else {
                            			// 강수확률은 값의 /100 적용.
        								if( var_select == "POP" ) {
            								var chtdata = new Array();
            								var pop_d = resp[vl]['data'][mm]['data'];
            									for(var x=0; x<pop_d.length; x++) {
													chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
            									}
        								} else {
                    						var chtdata = resp[vl]['data'][mm]['data'];
        								}
                            
                            			var lineName = resp[vl]['data'][mm]['utcInfo'] + "_" + resp[vl]['data'][mm]['model'];
										// 모델 컬러 추가. 2023-01-11
										let modl_color = resp[vl]['data'][mm]['modl_color'];
										
                            			if( mm == 0 ) {
											chart.series[0].name= lineName;
											// 모델 컬러 추가. 2023-01-11
                            				chart.series[0].color= modl_color;
                                			chart.series[0].setData(chtdata, false);
                            			} else if( mm == cht_line_num -1 ) {
											// 모델 컬러 추가. 2023-01-11
                                			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, true);
                            			} else {
											// 모델 컬러 추가. 2023-01-11
                                			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, false);
                            			}
                                	} // End of "if( cht_arr_num[mm] < 1 )"
                                	
                            	} // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)"
        
        					} // End of "if( cht_line_num == 0 )"

						} // End of "if( chkDataExist < 1 )"        
                        
					} // End of "for(var vl=0; vl<resp.length; vl++)"
					
                }, // End of "success : function(resp)"
                error : function(error) 
                {
                    alert("error");
                    console.log(error);
                }
        })
		
	}

</script>


<div id="sidebar_sub">

    <div class="sidebar-toggle-box" style="float:left; margin-top:10px; margin-left: 7%;">
    	<div class="fa fa-bars tooltips" data-placement="right" onclick="hideNview();"> 좌측 메뉴 접기/펼치기</div>
    	<div class="subMenu_nav">
        	<b><?php echo $vrfyTypeName[0]; ?> <i class="fa fa-link" style="font-size:13px"></i> <?php echo $vrfyTypeName[1]; ?>(<?php echo $vrfyTypeName[2]; ?>)</b>
    	</div>
    </div>
			
    <!-- 사이드 서브 메뉴 선택 박스 영역 hr 라인 -->
    <ul class="sidebar-menu" id="nav-accordion">
    	<hr>
    </ul>
			
    <!-- 사이드 서브 메뉴 선택 박스 시작 -->
	<div class="selBoxPn-panel pn">
		<p class="submenu_p"><i class="fa fa-tasks" style="font-size:16px; color:#52616a;"></i> 요소선택</p>
		<hr class="submenu_hr">
		<!-- 2021.03.25 Edit.
			<select class="eleSelBox" name="VAR" onchange="selVar(this);">
		-->
		<select class="eleSelBox" name="VAR" onchange="selVar(this.value);">
		<?php 
		for ($i=0; $i<sizeof($varArray); $i++) {
		    if($varArray[$i] == $varName) {
        ?>
    		<option value="<?php echo $varArray[$i]; ?>" selected><?php echo $varnameArray[$i]; ?></option>	
        <?php 
		    } else {
        ?>
    		<option value="<?php echo $varArray[$i]; ?>" ><?php echo $varnameArray[$i]; ?></option>	
        <?php 
		    }
		}
		?>
		</select>
	</div>
	
	<?php 
        if ($vrfyTypeName[0] == "단기") {
    ?>
		<div class="shrtModelTech-panel pn">
	<?php 
	   } else if ($vrfyTypeName[0] == "중기") {
    ?>
    	<div class='medmModelTech-panel pn'>
	<?php
	   }
    ?>
		<p class="submenu_p"><i class="fa fa-sitemap" style="font-size:16px; color:#52616a;"></i> 모델선택</p>
		<hr class="submenu_hr">
		<table class="chk_select">
		
			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][0];?> </b></td>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][1];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][0] . "_" . $modltech_info['tech_id'][0][0] ;?>" onclick="getDataArray();" checked >
				<?php echo $modltech_info['tech_name'][0][0]; ?></td>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][1] . "_" . $modltech_info['tech_id'][1][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][1][0]; ?></td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][2];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][2] . "_" . $modltech_info['tech_id'][2][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][2][0]; ?></td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][3];?> </b></td>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][4];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][3] . "_" . $modltech_info['tech_id'][3][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][3][0]; ?></td>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][4] . "_" . $modltech_info['tech_id'][4][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][4][0]; ?></td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][5];?> </b></td>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][6];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][5] . "_" . $modltech_info['tech_id'][5][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][5][0]; ?></td>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][6] . "_" . $modltech_info['tech_id'][6][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][6][0]; ?></td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][7];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][7] . "_" . $modltech_info['tech_id'][7][0] ;?>" onclick="getDataArray();" >
				<?php echo $modltech_info['tech_name'][7][0]; ?></td>
			</tr>




		</table>
	</div>
	<div class="js1-panel pn">
		<p class="submenu_p"><i class="fa fa-clock-o" style="font-size:17px; color:#52616a;"></i> 초기시각</p>
		<hr class="submenu_hr">
		<table id="CHECK_SELECT" class="chk_select">
			<tr>
				<!-- 
				<td><input type="checkbox" name="INIT_HOUR" value="00#00" onclick="getDataArray()" checked > 00UTC (09KST)</td>
				-->
				<td><input type="checkbox" id="00utc" name="INIT_HOUR" value="00#00" onclick="setHour(this.id); getDataArray()" checked > 00UTC (09KST)</td>
			</tr>
			<tr>
				<!-- 
				<td><input type="checkbox" name="INIT_HOUR" value="12#12" onclick="getDataArray()" > 12UTC (21KST)</td>
				-->
				<td><input type="checkbox" id="12utc" name="INIT_HOUR" value="12#12" onclick="setHour(this.id); getDataArray()" > 12UTC (21KST)</td>
			</tr>
			<tbody>
            </tbody>
		</table>
	</div>
	
	<div class="js2-panel pn">
		<p class="submenu_p"><i class="fa fa-globe" style="font-size:17px; color:#52616a;"></i> 지점선택</p>
		<hr class="submenu_hr">
		<!-- js/vrfy_js/month_common_func.js -->
		<select class="selectMainLoc" onchange="listingSubLoc(this);">
			<option value="ALL" selected>전체지점</option>
			<option value="47003#47005#47008#47014#47016#47020#47022#47025#47028#47031#47035#47037#47039#47041#47046#47050#47052#47055#47058#47060#47061#47065#47067#47068#47069#47070#47075" >북한</option>
			<option value="47098#47099#47102#47108#47112#47119#47201#47202#47203" >서울경기</option>
			<option value="47090#47095#47100#47101#47104#47105#47106#47114#47121#47211#47212#47216#47217" >강원도</option>
			<option value="47115#47130#47136#47137#47138#47143#47271#47272#47273#47277#47278#47279#47281#47283#00096" >경상북도</option>
			<option value="47152#47155#47159#47162#47192#47255#47284#47285#47288#47289#47294#47295" >경상남도</option>
			<option value="47127#47131#47135#47221#47226" >충청북도</option>
			<option value="47129#47133#47177#47232#47235#47236#47238" >충청남도</option>
			<option value="47140#47146#47172#47243#47244#47245#47247#47248" >전라북도</option>
			<option value="47156#47165#47168#47169#47170#47174#47259#47260#47261#47262#47268" >전라남도</option>
			<option value="47184#47185#47188#47189#47299" >제주도</option>
			<option value="47092#47110#47113#47128#47151#47163#47167#47139#47142#47153#47158#47161#47182" >공항</option>
		</select>
		<select id="subLocation" name="LOCATION" class="selectMulti" size="9" onclick="getDataArray();" multiple >
			<option value="ALL" selected>&#128440; 전체지점</option>
		</select>
	</div>
			
</div>   

