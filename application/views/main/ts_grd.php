<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_grph_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 격자 시계열 자료(기본: 임의기간 포함)를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/tsgrd_common_func.js');?>"></script>
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>


<script type="text/javascript">
//사이트 확인 (month or arbi)
var dateType = "<?php echo $dateType; ?>";

// 그래픽 Title의 검증지수 한글화.
var vrfy_data;
var vrfy_txt;
var vrfy_title;

var currentStrDate = "<?php echo $dataDate; ?>";			
var currentEndDate = "<?php echo $dataDate; ?>";			


//첫 페이지 Canvas 기능 표출을 위해 "makeCanvas()" 메서드 실행.
    $(document).ready(function(){
    	readyAndNowFunc("yymm");
    	
    	makeCanvas(null, null);
    	
    });
    
 // 시작페이지의 일정 선택 datepicker 일반 or 임의기간 포멧으로 초기화. 
	function readyAndNowFunc(dFormat) {

		changeDatePicker(null, null, dFormat);
		
	 	// 단기-격자-시계열(shrt_ts_grd)는 js함수가 다르다.
		var pType = "<?php echo $vrfyType; ?>";
			
    	// PHP array to Javascript array
		vrfy_data = [<?php echo '"'.implode('","', $vrfyTech['data_vrfy']).'"' ?>];
		vrfy_txt = [<?php echo '"'.implode('","', $vrfyTech['txt_vrfy']).'"' ?>];
		vrfy_title = [<?php echo '"'.implode('","', $vrfyTech['title_vrfy']).'"' ?>];
		
		// 검증지수 셀렉션 만들기.
    	makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);
	}

    function allowOnlyNumericInput(evt){
    	if((event.keyCode<48)||(event.keyCode>57)) event.returnValue=false;
    }

    function getDataArray() {
    	// 데이터 네임 앞 글자. 단기-지점
    	var data_head = "<?php echo $dataHead; ?>";
    	
    	// 요소 선택 값
    	var var_select = $("select[name=VAR]").val();
    
    	// 초기시각 UTC hour 선택 값
    	var init_hour = new Array();
    	$("input[name=INIT_HOUR]:checked").each(function() {
    		init_hour.push($(this).val());
    	});
        	if( init_hour.length < 1 ) {
    			alert("한개 이상의 초기시각을 선택해 주십시오");
    			return false;
        	}
    
    	// 모델 선택 값 배열
    	var model_sel = new Array();
    	$("input[name=MODEL_TECH]:checked").each(function() {
    		model_sel.push($(this).val());
    	});
        	if( model_sel.length < 1 ) {
    			alert("한개 이상의 모델을 선택해 주십시오");
    			return false;
        	}
    
    	// 기간 선택 값
    	var peri = $("select[name=PERIOD]").val();
    
    	// 기간 시작 값
    	var start_init = $("input:text[name='sInitDate']").val()
    	// 기간 끝 값
    	var end_init = $("input:text[name='eInitDate']").val()
    
    	// 검증지수 선택 값 배열
    	var vrfy_idx = new Array();
    	$("input[name=VRFY_INDEX]:checked").each(function() {
    		vrfy_idx.push($(this).val());
    	});
        	if( vrfy_idx.length < 1 ) {
    			alert("한개 이상의 검증지수를 선택해 주십시오");
    			return false;
        	}

    	var map_type = "ts";

    	// 지도 위 격자 지점을 찍으면 실행.
    	if( userSpecificPoints.length > 0 ) {
    		callAjaxFcstGrph(data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, map_type);
    	}
    	
    }

////////////////////////////////////////////
// 요소선택 변경 시 검증지수 체크박스를 다시 세팅하기 위한 메서드.
	function selVar(val) {
		$.ajax({
                type : "POST",
                    data :
                    {
						"varName" : val.value
                    },
                    dataType: "json",
                    url : '<?php echo site_url();?>/main/callVrfyTech',
					// 변수에 저장하기 위함.
                    async:false,
                    success : function(resp)
                    {

                        // 검증지수 체크박스 삭제.
                    	$('#vrfySelect').empty();

                	 	// 단기-격자-시계열(shrt_ts_grd)는 js함수가 다르다.
                		var pType = "<?php echo $vrfyType; ?>";
                    	
            			vrfy_data = resp['data_vrfy'];
            			vrfy_txt = resp['txt_vrfy'];
            			vrfy_title = resp['title_vrfy'];
                		
                		// 검증 지수 셀렉트박스 생성.
						makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);

                    	// 초기시각 세팅.
                    	makeUTCopt(val.value, dateType);

    					// 그래프 표출 영역 초기화.
                    	$('#cht_area').empty();

						// canvas 선택 영역 초기화.
                    	userSpecificPoints = [];
                    	tiles_array = [];

                    	
                    },
                    error : function(error) 
                    {
                        console.log("error Message: ");
                        console.log(error);
                    }
            })

        // 데이터 표출.
        makeCanvas(null, null);
        console.log(userSpecificPoints); 
        console.log(tiles_array); 
	}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  예측기간별 자료  Ajax 이용 그래픽 표출 메서드.
	function callAjaxFcstGrph(data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, map_type) {
    	$.ajax({
            type : "POST",
                data :
                {
    				"data_head" : data_head,
    				"var_select" : var_select,
    				"init_hour" : init_hour,
    				"model_sel" : model_sel,
    				"start_init" : start_init,
    				"end_init" : end_init,
    				"vrfy_idx" : vrfy_idx,
    				"map_type" : map_type,
    				"nx" : nx,
    				"ny" : ny,
    				"xy_point" : userSpecificPoints
                },
                dataType: "json",
                url : '<?php echo site_url();?>/main/getGrdFcstGrph',
    			// 변수에 저장하기 위함.
                async: false,
                success : function(resp) {

console.log( resp );
					// 그래프 표출 영역 초기화.
                	$('#cht_area').empty();

					// 그래프 표출 개수 (모델, 초기시각, 예측월, 검증지수 단일 선택으로 막을 것임.)
					for(var vl=0; vl<resp.length; vl++) {

						// 시계열 차트(DIV) 이름.
						var chtDiv_name = resp[vl]['data_info'] + "_" + resp[vl]['point_info'] + "_div";
						var chtDiv_main = chtDiv_name + "_main";
						
						var selCont = "";
                            selCont += "<div class='col-lg-12 mb' id='" + chtDiv_main + "'>";
                            selCont += "<div class='white-panel' >";
                            selCont += "<div class='white-header'>";
                            
                            selCont += "<a href='#' name='" + chtDiv_main + "' class='close-window' style='float:right; color:white; width:35px; padding-top:7px;'>";
                            selCont += "<i class='fa fa-times'></i>";
                            selCont += "</a>";

                     	var dinfo_arr = resp[vl]['data_info'].split("_");
                     	var v_name = dinfo_arr[0];
                     	var vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, dinfo_arr[1]);
    						selCont += "<h5><b class='chartName'>" + v_name + "_" + vrfy_name + " [" + resp[vl]['point_title'] + "]</b></h5>";
    
                         	selCont += "</div>";
    
    					//---- 집계표 테이블 시작
                 			selCont += "<table class='fcstTable'>";
             					selCont += "<tr class='tb_head'>";
            	    					selCont += "<td class='td_lg'>년월</td><td class='td_lg'>UTC</td><td class='td_lg'>모델 - 기법</td>";
            
            	    					// 예측시간 표출
             						for(var u=0; u<resp[vl]['fHeadUtc'].length; u++) {
                     					selCont += "<td class='td_sm'>" + resp[vl]['fHeadUtc'][u] + "</td>"
                     				}
                             				
        						selCont += "</tr>";
             
            						
    	    					// 예측시간 데이터 표출
    							for(var mm=0; mm<resp[vl]['xyData'].length; mm++) {
        							// 모델 + 초기시각 + 월별 개수 만큼 테이블 증가.
            						selCont += "<tr class='tb_data'>";
            						
									var infoArr = resp[vl]['xyData'][mm]['dataInfo'].split("_");
									var mon = infoArr[1];
									var utc = infoArr[2].replace("UTC","");
									var modl = infoArr[3] + "_" + infoArr[4];
        							
    								selCont += "<td class='tdinfo_txt'>" + mon + "</td><td class='tdinfo_txt'>" + utc + "</td><td class='tdinfo_txt'>" + modl + "</td>";

									for(var d=0; d<resp[vl]['xyData'][mm]['data'].length; d++) {
										if( resp[vl]['xyData'][mm]['data'][d] == null ) {
    										selCont += "<td> </td>";
										} else {
    										selCont += "<td>" + resp[vl]['xyData'][mm]['data'][d] + "</td>";
										}
									}
            						selCont += "</tr>";
    							} 
            						
    						selCont += "</table>";    						
    					//---- 집계표 테이블 끝

						//---- 시계열 차트 시작
							selCont += "<div id='" + chtDiv_name + "' class='cht_div'></div>";
							
                        	selCont += "</div></div>";

        					// 검증지수 * 지점 개수 만큼 차트DIV 생성 끝.
        					$('#cht_area').append(selCont);

							var sp_vl = resp[vl]['data_info'].split("_");
							var var_id = sp_vl[0];
							var vrfy_id = sp_vl[1];

							var var_unit = get_grph_unit(var_id, vrfy_id);
        						var vUnit = var_unit.split("#");
    						var unitName = vUnit[0];
    						var unitSymb = vUnit[1];
    
    					//하이차트 표출. (데이터가 없는 껍데기)
                            $('#' + chtDiv_name).highcharts({
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
                            	            text: unitName + " ( " + unitSymb + " )"
                            	        }
                            	    },
                            	    xAxis: {
                            	        title: {
                            	            text: 'time(H)'
                            	        },
                            	        categories: resp[vl]['fHeadUtc']
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
                    		var chart = $('#' + chtDiv_name).highcharts();

        					// 하나의 차트에 들어갈 데이터 라인의 수.
        					var cht_line_num = resp[vl]['xyData'].length;

            					if( cht_line_num == 0 ) {
                    				chart.series[0].name= "No Data";
                        			chart.series[0].setData([], true);
            					} else if( cht_line_num == 1 ) {
            						var chtdata = resp[vl]['xyData'][0]['data'];

                                	var lineNameArray = resp[vl]['xyData'][0]['dataInfo'].split("_");
                                	var lineName = "";
                                		for(var ln=1; ln<lineNameArray.length; ln++) {
                                    		lineName += lineNameArray[ln];
                                        		if(ln != lineNameArray.length-1) {
                                        			lineName += "_";
                                        		}
                                		}
            						
                        			chart.series[0].setData(chtdata, true);
                    				chart.series[0].update({name: lineName}, false);
                        			chart.redraw();
                        			
            					} else {    						
                					
                                	for(var mm=0; mm<resp[vl]['xyData'].length; mm++) {

                                    	var chtdata = resp[vl]['xyData'][mm]['data'];

                                    	var lineNameArray = resp[vl]['xyData'][mm]['dataInfo'].split("_");
                                    	var lineName = "";
                                    		for(var ln=1; ln<lineNameArray.length; ln++) {
                                        		lineName += lineNameArray[ln];
                                            		if(ln != lineNameArray.length-1) {
                                            			lineName += "_";
                                            		}
                                    		}
                            
                            			if( mm == 0 ) {
                            				chart.series[0].name= lineName;
                                			chart.series[0].setData(chtdata, false);
                            			} else if( mm == (resp[vl]['xyData'].length -1) ) {
                                			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}}, true);
                            			} else {
                                			chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}}, false);
                            			}
                                	
                                	} // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)"

            					}
                            
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
		<select class="eleSelBox" name="VAR" onchange="selVar(this);">
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
        				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][$md] . "_" . $modltech_info['tech_id'][$md][$tc] ;?>" onclick="getDataArray();" <?php echo ($md==0 && $tc==0) ? "checked" : "";?>>
        				 <?php echo $modltech_info['tech_name'][$md][$tc]; ?></td>
			<?php 
    		    } else {
			?>
        				<td>
        				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][$md] . "_" . $modltech_info['tech_id'][$md][$tc] ;?>" onclick="getDataArray();">
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
				<td><input type="checkbox" name="INIT_HOUR" value="00#00" onclick="getDataArray()" checked > 00UTC (09KST)</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="INIT_HOUR" value="12#12" onclick="getDataArray()" > 12UTC (21KST)</td>
			</tr>
			<tbody>
            </tbody>
		</table>
	</div>
	
</div>   

