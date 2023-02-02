<!-- // 지점의 id를 매칭하여 지점 이름을 가져온다. 또한 지점 선택 selectBox를 통해 세부 지역을 선택 할 수 있도록 리스팅 한다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_station_name.js');?>"></script>
<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_grph_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>

<!-- // 2020년 12월 기준 이전 날짜에는 단기 3시간 자료 이후에는 단기 1시간 자료를 표출하기 위해 사용되는 함수. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/shrt_change_3to1_func.js');?>"></script>


<script type="text/javascript">
//사이트 확인 (month or arbi)
var dateType = "<?php echo $dateType; ?>";

// 그래픽 Title의 검증지수 한글화.
var vrfy_data;
var vrfy_txt;
var vrfy_title;

//최신 날짜 (디렉터리 검사 후 최신 날짜로 세팅.)
var currentStrDate = "<?php echo $dataDate; ?>";			
var currentEndDate = "<?php echo $dataDate; ?>";
    
    // 첫 페이지 자료표출을 위해 "getDataArray()" 메서드 실행.
    $(document).ready(function(){
    	readyAndNowFunc();
    	// 목표 시간 표출 하느냐 안하느냐.
    	add_remove_peri();
    });
    
    function readyAndNowFunc() {
    	changeDatePicker(null, null);

	 	// 단기-격자-시계열(shrt_ts_grd)는 검증지수 선택이 단일 선택이므로 pType으로 함수 분류.
		var pType = "<?php echo $vrfyType; ?>";
		
    	// PHP array to Javascript array
			vrfy_data = [<?php echo '"'.implode('","', $vrfyTech['data_vrfy']).'"' ?>];
			vrfy_txt = [<?php echo '"'.implode('","', $vrfyTech['txt_vrfy']).'"' ?>];
			vrfy_title = [<?php echo '"'.implode('","', $vrfyTech['title_vrfy']).'"' ?>];
			
		// 검증 지수 셀렉트 박스 생성.
    	makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);
    }
    
    // DatePicker 적용 함수.
    function changeDatePicker(setStrDate, setEndDate) {
    	var strDate = setStrDate;
    	var endDate = setEndDate;
    	
    	if( !setStrDate || !setEndDate ) {
            strDate = new Date(currentStrDate);
            endDate = new Date(currentEndDate);
    	}
    
    	// datepicker 초기화 ( default date 초기화를 위함. )
    	$('#sInitDate').datepicker( "destroy" );
    	$('#eInitDate').datepicker( "destroy" );
    	
        $('#sInitDate').datepicker({
            dateFormat:'yymmdd', 
            changeYear: true, 
            autoclose: true, 
            yearRange: "2018:" + thisYear,
            defaultDate: strDate
        });
    	$('#sInitDate').datepicker('setDate', strDate);
    	
      	$('#eInitDate').datepicker({
    	  	dateFormat:'yymmdd', 
    	  	changeYear: true, 
    	  	autoclose: true, 
            yearRange: "2018:" + thisYear,
    	  	defaultDate: endDate
      	});
    	$('#eInitDate').datepicker('setDate', endDate);
    }

    
    
    // 캘린더 아이콘 클릭 시 표출.
    function openSCalendar(){
    	$("#sInitDate").datepicker("show");
    }
    // 캘린더 아이콘 클릭 시 표출.
    function openECalendar(){
    	$("#eInitDate").datepicker("show");
    }

	// 임의기간과 구별하기 위한 함수. (월자료는 선택 즉시 작동 But 임의기간은 PLOT 버튼 클릭 시 작동)	
	function listingSubLoc(subLocVal) {
		setSubLocation(subLocVal);
	}

    function getDataArray() {
    	// 데이터 네임 앞 글자. 단기-지점
    	var data_head = "<?php echo $dataHead; ?>";
    	
    	// 요소 선택 값
    	var var_select = $("select[name=VAR]").val();
//console.log(var_select);
    
    	// 초기시각 UTC hour 선택 값
    	var init_hour = new Array();
    	//var init_hour = $("input:checkbox[name='INIT_HOUR']:checked").val(); 
    	$("input[name=INIT_HOUR]:checked").each(function() {
    		init_hour.push($(this).val());
    	});
        	if( init_hour.length < 1 ) {
    			alert("한개 이상의 초기시각을 선택해 주십시오");
    			return false;
        	}
// console.log(init_hour);
    
    	// 모델 선택 값 배열
    	var model_sel = new Array();
    	$("input[name=MODEL_TECH]:checked").each(function() {
    		model_sel.push($(this).val());
    	});
        	if( model_sel.length < 1 ) {
    			alert("한개 이상의 모델을 선택해 주십시오");
    			return false;
        	}
// console.log(model_sel);
    
    	// 지점 선택 값 배열
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
//console.log(location);
    
    	// 기간 선택 값
    	var peri = $("select[name=PERIOD]").val();
//console.log(peri);
    
    	// 기간 시작 값
    	var start_init = $("input:text[name='sInitDate']").val()
//console.log("||||| " + start_init);
    	// 기간 끝 값
    	var end_init = $("input:text[name='eInitDate']").val()
//console.log(end_init);
    
    	// 검증지수 선택 값 배열
    	var vrfy_idx = new Array();
    	$("input[name=VRFY_INDEX]:checked").each(function() {
    		vrfy_idx.push($(this).val());
    	});
        	if( vrfy_idx.length < 1 ) {
    			alert("한개 이상의 검증지수를 선택해 주십시오");
    			return false;
        	}
//console.log(vrfy_idx);

		callAjaxArbiData(data_head, peri, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx);

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
					// main에 있는 메서드 공통 사용.
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
                    	makeUTCopt(val.value, dateType);

                    	// 목표 시간 표출 하느냐 안하느냐.
                    	add_remove_peri();
						
                    },
                    error : function(error) 
                    {
                        console.log("error Message: ");
                        console.log(error);
                    }
            })

	}



////////////////////////////////////////////
// 임의 기간 중 "목표 시간"은 중기-지점 자료의 "최고기온", "최저기온" 에서만 표출 한다.
	function add_remove_peri() {
		var vari = $('.eleSelBox option:checked').val();
    	var peri_num = $('#data_period option').size();
		var dataType = "<?php echo $vrfyType ?>";
		var dt_arr = dataType.split("_");

		if( (dt_arr[0] == "medm" && dt_arr[2] == "stn" && vari == "TMX") || (dt_arr[0] == "medm" && dt_arr[2] == "stn" && vari == "TMN") ) {
			if( peri_num == 1 ) {
				$('#data_period').append("<option value='ARBI_TARG'>목표시간</option>");    								
			}
		} else {
			if( peri_num > 1 ) {
				$('#data_period option:last').remove();    								
			}
		}
	}



	// 예측시간이 1시간 또는 3시간 자료인지 확인 후 변수 이름 변경.
	function changeVrfyByVar(start_init, var_select) {
		
		// 2021-01-25 add by joshua.
		var var_change = "";

		var sd = start_init.substr(0,4) + "-" + start_init.substr(4,2) + "-01";
		
		if( var_select == "T1H" || var_select == "T3H" ) {
			if( shrtMonthCheck(sd) ) {
				var_change = "T3H";
			} else {
				var_change = "T1H";
			}
		} else if( var_select == "RN1" || var_select == "RN3" ) {
			if( shrtMonthCheck(sd) ) {
				var_change = "RN3";
			} else {
				var_change = "RN1";
			}
		} else if( var_select == "SN1" || var_select == "SN3" ) {
			if( shrtMonthCheck(sd) ) {
				var_change = "SN3";
			} else {
				var_change = "SN1";
			}
		} else {
			var_change = var_select;
		}
		// 2021-01-25 add by joshua.		

		return var_change;
	}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  임의기간(발표) 자료  Ajax 이용 그래픽 표출 메서드.
	function callAjaxArbiData(data_head, peri, var_select, init_hour, model_sel, location, start_init, end_init, vrfy_idx) {
		
		// 기온 1시간 또는 3시간 자료인지 확인.
		var var_change = changeVrfyByVar(start_init, var_select);

    	$.ajax({
            type : "POST",
                data :
                {
    				"data_head" : data_head,
    				"peri" : peri,
    				// "var_select" : var_select,
    				"var_select" : var_change,
    				"init_hour" : init_hour,
    				"model_sel" : model_sel,
    				"location" : location,
    				"start_init" : start_init,
    				"end_init" : end_init,
    				"vrfy_idx" : vrfy_idx,
                },
                dataType: "json",
                url : '<?php echo site_url();?>/arbitraryExpression/getArbiStnFcstData',
    			// 변수에 저장하기 위함.
                async: true,
                success : function(resp)
                {

console.log("Ajax 시작 (Clicked PLOT Button) - ");

console.log( resp );

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
                            // selCont += "<div class='col-lg-11 mb'>";
                            // selCont += "<div class='white-panel' >";
                            // selCont += "<div class='white-header'>";
                    
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
                            // selCont += "<h5><b class='chartName'>" + resp[vl]['var_name'] + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]</b></h5>";
                            selCont += "<h5><b class='chartName'>" + var_change + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]</b></h5>";
                            
                            selCont += "</div>";
                    		// 검증지수 * 지점 개수 만큼 차트DIV Header 끝.
                    
                    	//---- 집계표 테이블 시작
                    		selCont += "<table class='fcstTable'>";
                    		selCont += "<tr class='tb_head'>";
                    			selCont += "<td class='td_lg'>UTC</td><td class='td_lg'>모델 - 기법</td><td class='td_lg'>자료수</td>";
                    
                    			// 예측시간 표출
                    			for(var u=0; u<resp[vl]['data'][0]['fHeadUtc'].length; u++) {
                    				selCont += "<td class='td_sm'>" + resp[vl]['data'][0]['fHeadUtc'][u] + "</td>"
                    			}
                        				
                    			selCont += "<td class='td_sm'>AVE</td></tr>";
                    
                    			// 예측시간 데이터 표출
                    			for(var mm=0; mm<resp[vl]['data'].length; mm++) {
                    				
                    				selCont += "<tr class='tb_data'>";
                    					// selCont += "<td>" + resp[vl]['data'][mm]['utc'] + "</td>" + 
                    					selCont += "<td>" + resp[vl]['data'][mm]['utc'].replace("UTC","") + "</td>" + 
                    								 "<td>" + resp[vl]['data'][mm]['model'] + "</td>" + 
                    								 "<td>" + resp[vl]['data'][mm]['fDataNum'] + "</td>";
                    
                    					for(var d=0; d<resp[vl]['data'][mm]['data'].length; d++) {
    										// // 강수확률은 값의 /100 적용.
    										// if( var_select == "POP" ) {
            								// 	selCont += "<td>" + (resp[vl]['data'][mm]['data'][d]/100).toFixed(3) + "</td>";
    										// } else {
            								// 	selCont += "<td>" + resp[vl]['data'][mm]['data'][d] + "</td>";
    										// }

											if( resp[vl]['data'][mm]['data'][d] == null ) {
            									selCont += "<td> </td>";
    										} else {
												// 강수확률은 값의 /100 적용.
												if( var_select == "POP" ) {
            										selCont += "<td class='" + dataFontClass + "'>" + (resp[vl]['data'][mm]['data'][d]/100).toFixed(3) + "</td>";
												} else {
													selCont += "<td class='" + dataFontClass + "'>" + resp[vl]['data'][mm]['data'][d] + "</td>";
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
							
                    		// 임의기간에서는 월별 표출을 안하므로 AVE 값도 함께 표출하도록 한다.
							var xCate = resp[vl]['data'][0]['fHeadUtc'];
							xCate.push("AVE");
                            
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
                            	        categories: xCate
                            	    },
                            	    legend: {
                            	        layout: 'horizontal',
                            	        //align: 'right',
                            	        align: 'center',
                            	        //verticalAlign: 'middle'
                            	        verticalAlign: 'bottom'
                            	    },
                            
                            	    plotOptions: {
                            	        series: {
                            	            label: {
                            	                connectorAllowed: false
                            	            },
                            	            //pointStart: 2010
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
                             });
                    
                    
                    		// 모델X초기시각X기간 값을 하이차트에 Append 하기 위해 객체 생성.
                    		var chart = $('#' + resp[vl]['vrfy_loc'] + "_div").highcharts();
//console.log( "resp[vl]['data'].length: " + resp[vl]['data'].length);
                    
                    		// 하나의 차트에 들어갈 데이터 라인의 수.
                    		var cht_line_num = resp[vl]['data'].length;
                    
                    		// TODO : 데이터가 하나도 없을 시. ( 위에 "if( resp[vl]['data'].length == 0 )" 를 추가했으므로 필요없을 수 있음 )
                    		if( cht_line_num == 0 ) {
                    			chart.series[0].name= "No Data";
                    			chart.series[0].setData([], true);
//console.log( "차트 수 = 0" );
                    		// 데이터가 하나 일 경우 series의 name이 제대로 기입이 안되므로 억지로 집어넣어준다.
                    		} else if( cht_line_num == 1 ) {

                    			// 강수확률은 값의 /100 적용.
								if( var_select == "POP" ) {
									// IE에서 .map() 함수 사용 못함.
    								//var chtdata = resp[vl]['data'][0]['data'].map( x=>parseFloat( (x/100).toFixed(3) ));
    								var chtdata = new Array();
    								var pop_d = resp[vl]['data'][0]['data'];
    									for(var x=0; x<pop_d.length; x++) {
											chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
    									}
								} else {
            						var chtdata = resp[vl]['data'][0]['data'];
								}
								
                    			var lineName = resp[vl]['data'][0]['utc'] + "_" + resp[vl]['data'][0]['model'];
        						
                    			chart.series[0].setData( chtdata, true );
                				chart.series[0].update({name: lineName}, false);
                    			chart.redraw();
                    			
//console.log( "차트 수 = 1" );
                    		} else {
                    
                            	for(var mm=0; mm<resp[vl]['data'].length; mm++) {
                                	var chtdata = resp[vl]['data'][mm]['data'];
                        
                        			var lineName = resp[vl]['data'][mm]['utc'] + "_" + resp[vl]['data'][mm]['model'];
                    
                        			if( mm == 0 ) {
                        				chart.series[0].name= lineName;
                            			chart.series[0].setData(chtdata, false);
                        			} else if( mm == (resp[vl]['data'].length -1) ) {
                            			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}}, true);
                        			} else {
                            			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}}, false);
                        			}
                            	} // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)"
                    
                    		}
                    
                    	} // End of "if( resp[vl]['data'].length == 0 )"
                        
                    } // End of "for(var vl=0; vl<resp.length; vl++)"
					
                } // End of "success : function(resp)"

				, beforeSend: function () {
                			// 그래프 표출 영역 초기화.
			                $('#contValue').empty();
					// plot 버튼 중복 방지.
					$('#plotBtn').hide();

					// 로딩 이미지 표출.
                    var imgSrc = "<?php echo base_url('assets/img/data_loading/data_loading.gif');?>";
                    $('#contValue').append("<div id='div_ajax_load_image'><img id='img_load' src=" + imgSrc + "></div>");
				}

				, complete: function () {
					// plot 버튼 중복 방지.
					$('#plotBtn').show();
				}
                
                , error : function(error) 
                {
                    alert("error");
                    
                	// 그래프 표출 영역 초기화.
                	$('#contValue').empty();
                	
                    console.log(error);
                }
        })
		
	}
</script>


<div id="sidebar_sub">

    <div class="sidebar-toggle-box" style="float:left; margin-top:10px; margin-left: 10%;">
    	<!--  <div class="fa fa-bars tooltips" data-placement="right" onclick="hideNview();"> 좌측 메뉴 접기/펼치기</div> -->
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
		<!-- 2020.03.26 edit
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
		
		<?php 
		for($md=0; $md<sizeof($modltech_info['modl_id']); $md++) {
        ?>
			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][$md];?> </b></td>
			</tr>
    		<?php 
    		for($tc=0; $tc<sizeof($modltech_info['tech_id'][$md]); $tc++) {
    		    if( $tc % 2 == 0) {
            ?>
        			<tr>
        				<td>
        				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][$md] . "_" . $modltech_info['tech_id'][$md][$tc] ;?>" <?php echo ($md==0 && $tc==0) ? "checked" : "";?>>
        				 <?php echo $modltech_info['tech_name'][$md][$tc]; ?></td>
			<?php 
    		    } else {
			?>
        				<td>
        				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][$md] . "_" . $modltech_info['tech_id'][$md][$tc] ;?>" >
        				 <?php echo $modltech_info['tech_name'][$md][$tc]; ?></td>
        			</tr>
    	    <?php
    		    }
    		}
    		?>
	    <?php
		}
		?>
		</table>
	</div>
	<div class="js1-panel pn">
		<p class="submenu_p"><i class="fa fa-clock-o" style="font-size:17px; color:#52616a;"></i> 초기시각</p>
		<hr class="submenu_hr">
		<table id="CHECK_SELECT" class="chk_select">
			<tr>
				<td><input type="checkbox" name="INIT_HOUR" value="00#00" checked > 00UTC (09KST)</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="INIT_HOUR" value="12#12" > 12UTC (21KST)</td>
			</tr>
			<tbody>
			<!-- // 요소선택의 초기값은 기온(T3H)이므로 00+12UTC는 표출 안함.
			<tr>
				<td><input type="checkbox" name="INIT_HOUR" value="00#12" onclick="getDataArray()"> 00UTC + 12UTC</td>
			</tr>
            -->
            </tbody>
		</table>
	</div>
	
	<div class="js2-panel pn">
		<p class="submenu_p"><i class="fa fa-globe" style="font-size:17px; color:#52616a;"></i> 지점선택</p>
		<hr class="submenu_hr">
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
		<select id="subLocation" name="LOCATION" class="selectMulti" size="9" multiple >
			<option value="ALL" selected>&#128440; 전체지점</option>
		</select>
	</div>
			
</div>   

