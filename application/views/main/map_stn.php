<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_variable_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 월 자료(기본: 임의기간이 아닌)를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/month_common_func.js');?>"></script>
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

var currentStrDate = "<?php echo $dataDate; ?>";			
var currentEndDate = "<?php echo $dataDate; ?>";			

var idx = 0;
var maxstep = 0;

var keydown_cont = 0;


    // 첫 페이지 자료표출을 위해 "getDataArray()" 메서드 실행.
    $(document).ready(function(){
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
		
    	// 검증 지수 셀렉트박스 생성.
    	makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType);
    	
    	getDataArray();
    }
    
    function getDataArray() {
    	// 데이터 네임 앞 글자. 단기-지점
    	var data_head = "<?php echo $dataHead; ?>";
    	
    	// 요소 선택 값
    	var var_select = $("select[name=VAR]").val();
    
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

    	callAjaxUtcInfo(data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, peri);

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
	function callAjaxUtcInfo(data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, peri) {

		// 기온 1시간 또는 3시간 자료인지 확인.
		// 2021-02-19 add by joshua.
		// var var_change = "";

		var sd = start_init.substr(0,4) + "-" + start_init.substr(4,2) + "-01";
		
		// if( var_select == "T1H" || var_select == "T3H" ) {
		// 	if( shrtMonthCheck(sd) ) {
		// 		var_change = "T3H";
		// 	} else {
		// 		var_change = "T1H";
		// 	}
		// } else if( var_select == "RN1" || var_select == "RN3" ) {
		// 	if( shrtMonthCheck(sd) ) {
		// 		var_change = "RN3";
		// 	} else {
		// 		var_change = "RN1";
		// 	}
		// } else if( var_select == "SN1" || var_select == "SN3" ) {
		// 	if( shrtMonthCheck(sd) ) {
		// 		var_change = "SN3";
		// 	} else {
		// 		var_change = "SN1";
		// 	}
		// } else {
		// 	var_change = var_select;
		// }
		// 2021-02-19 add by joshua.		

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
    				"start_init" : start_init,
    				"end_init" : end_init,
    				"vrfy_idx" : vrfy_idx,
    				"peri" : peri
                },
                dataType: "json",
                url : '<?php echo site_url();?>/main/getStnFcstNum',
    			// 변수에 저장하기 위함.
                async: false,
                success : function(resp)
                {

// console.log(resp);
					// 그래프 표출 영역 초기화.
                	$('#fcstValue').empty();
                	$('#contValue').empty();

					// 표출 항목이 있을 경우 start.
					if( resp["fcst_info"] ) {

						//fcstTable = "<div class='col-lg-10'>";
						fcstTable = "";
						
						fcstTable += "<table class='map_utc_table' >";
						
						// 2021-02-24 add by joshua.
						if( shrtMonthCheck(sd) == true || ( var_change == "TMX" || var_change == "TMN" ) ) {

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

							// 3시간 단위 라벨 기입.					
							fcstTable += "<tr>";
								for(var i=0; i<24; i++) {
									var fi = i + 1;
									if( fi%3 == 0 ) {
										fcstTable += "<td class='sliderLabel' >" + fi + "</td>";
									} else {
										fcstTable += "<td class='sliderLabel' ></td>";
									}
								}
							fcstTable += "<td class='sliderLabel' >AVE</td>";
							fcstTable += "</tr>";

							var slider_num = 0;
							// 1시간 단기 자료 slider 생성.
							// for(var i=0; i<120; i++) {
							for(var i=0; i<144; i++) {
								var fi = i + 1;
								
								if( fi%24 == 1 ) {
									fcstTable += "<tr>";
								}

									// 00 UTC
									// if( (init_hour == "00#00" && fi < 15) || (init_hour == "00#00" && fi > 108) ) {
									if( (init_hour == "00#00" && fi < 15) || (init_hour == "00#00" && fi > 144) ) {
										fcstTable += "<td class='sl_black'> &nbsp; </td>";
										
									// } else if( (init_hour == "12#12" && fi < 2) || (init_hour == "12#12" && fi > 96) ) {
									} else if( (init_hour == "12#12" && fi < 3) || (init_hour == "12#12" && fi > 132) ) {
										fcstTable += "<td class='sl_black'> &nbsp; </td>";
										
									} else {
										slider_num = slider_num +1;
										
										//fcstTable += "<td class='slider' id='Image_" + i + "' title='" + resp['fcst_info']['utc_txt'][i] + "' > &nbsp; </td>";
										fcstTable += "<td class='slider' id='Image_" + slider_num + "' title='" + resp['fcst_info']['utc_txt'][slider_num] + "' > &nbsp; </td>";
									} 

								
								// if( fi%24 == 0 && fi != 120 ) {
								if( fi%24 == 0 && fi != 144 ) {
									fcstTable += "</tr>";
								// } else if( fi%24 == 0 && fi == 120 ) {
								} else if( fi%24 == 0 && fi == 144 ) {
									fcstTable += "<td class='slider' id='Image_0' title='" + resp['fcst_info']['utc_txt'][0] + "' > &nbsp; </td>";
									fcstTable += "</tr>";
								}
							}
							
							fcstTable += "</table>";
							
							fcstTable += "</div>";

							
						}

						
						$('#fcstValue').append(fcstTable);

						maxstep = resp['fcst_info']['utc_txt'].length -1;
						
						// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
						display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);

						
						//setSlideAt(0);
						// 슬라이드바를 직접 클릭할때 호출되는 함수
						$("td[id^='Image_']").click(function() {
							idx = $(this).attr("id").substr(6);
							ImgIndex = idx;
							
							// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
							display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);
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
								display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);
								
							} else if(e.keyCode == 39) { // right
								idx = idx*1 +1;

								if (idx > maxstep ){
									idx = 0;
								}
								// display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
								display_grph(var_change, model_sel, vrfy_idx, resp, idx, data_head);
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
                			grph_div += "<img class='grph_img' src='" + "<?php echo base_url('GIFD/');?>" + dt_arr[1] + "/" + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
// console.log( "<?php echo base_url('GIFD/');?>" + dt_arr[1] + "/" + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph );
                			
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
				<td><input type="checkbox" id="00utc" name="INIT_HOUR" value="00#00" onclick="setHour(this.id); getDataArray()" checked > 00UTC (09KST)</td>
			</tr>
			<tr>
				<td><input type="checkbox" id="12utc" name="INIT_HOUR" value="12#12" onclick="setHour(this.id); getDataArray()" > 12UTC (21KST)</td>
			</tr>
			<tbody>
            </tbody>
		</table>
	</div>
	
</div>   

