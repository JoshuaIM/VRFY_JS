<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_variable_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 월 자료를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/month_common_func.js');?>"></script>
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>

<script type="text/javascript">

    var currentStrDate = "2018-08-01";			
    var currentEndDate = "2018-08-01";			

	var idx = 0;
	var maxstep = 0;

	var keydown_cont = 0;
    
    // 첫 페이지 자료표출을 위해 "getDataArray()" 메서드 실행.
    $(document).ready(function(){
    	readyAndNowFunc();
    	// 목표 시간 표출 하느냐 안하느냐.
    	//add_remove_peri();
    });
    
    function readyAndNowFunc() {
    	changeDatePicker(null, null);
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
            yearRange: "2018:2020",
            defaultDate: strDate
        });
    	$('#sInitDate').datepicker('setDate', strDate);
    	
      	$('#eInitDate').datepicker({
    	  	dateFormat:'yymmdd', 
    	  	changeYear: true, 
    	  	autoclose: true, 
            yearRange: "2018:2020",
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

		callAjaxArbiData(data_head, peri, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx);
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
					// main에 있는 메서드 공통 사용.
                    url : '<?php echo site_url();?>/main/callVrfyTech',
					// 변수에 저장하기 위함.
                    async:false,
                    success : function(resp)
                    {
                        // 검증지수 체크박스 삭제.
                    	$('#vrfySelect').empty();
                        // 요소선택에 따른 검증지수 체크박스 다시 생성.
                    	var vrfyTxt = "<b>검증지수:</b>";
    						for(var $j=0; $j<resp.length; $j++) {
        						if( $j == 0 ) {
    								vrfyTxt += "<input name='VRFY_INDEX' value='" + resp[$j] + "' type='checkbox' checked onclick='chkVrfy();'>" + resp[$j];
        						} else {
    								vrfyTxt += "<input name='VRFY_INDEX' value='" + resp[$j] + "' type='checkbox' onclick='chkVrfy();'>" + resp[$j];
        						}
    						}                    	
                    	$('#vrfySelect').append(vrfyTxt);

                    	// 초기시각 세팅.
                    	arbiUTCopt(val.value);

                    	// 목표 시간 표출 하느냐 안하느냐.
                    	//add_remove_peri();
						
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
// 	function add_remove_peri() {
// 		var vari = $('.eleSelBox option:checked').val();
//     	var peri_num = $('#data_period option').size();
		var dataType = "<?php echo $vrfyType ?>";
// 		var dt_arr = dataType.split("_");

// 		if( (dt_arr[0] == "medm" && dt_arr[2] == "stn" && vari == "TMX") || (dt_arr[0] == "medm" && dt_arr[2] == "stn" && vari == "TMN") ) {
// 			if( peri_num == 1 ) {
// 				$('#data_period').append("<option value='ARBI_TARG'>목표시간</option>");    								
// 			}
// 		} else {
// 			if( peri_num > 1 ) {
// 				$('#data_period option:last').remove();    								
// 			}
// 		}
// 	}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  임의기간(발표) 자료  Ajax 이용 그래픽 표출 메서드.
	function callAjaxArbiData(data_head, peri, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx) {
		
    	$.ajax({
            type : "POST",
                data :
                {
    				"data_head" : data_head,
    				"peri" : peri,
    				"var_select" : var_select,
    				"init_hour" : init_hour,
    				"model_sel" : model_sel,
    				"start_init" : start_init,
    				"end_init" : end_init,
    				"vrfy_idx" : vrfy_idx
                },
                dataType: "json",
                url : '<?php echo site_url();?>/arbitraryExpression/getArbiGrbFcstNum',
    			// 변수에 저장하기 위함.
                async: true,
                success : function(resp)
                {

console.log("Ajax 시작 - ");

console.log( resp );

					if( resp['fcst_info'] ) {
						
    					// 그래프 표출 영역 초기화.
                        $('#fcstValue').empty();
                        
                        //fcstTable = "<div class='col-lg-10'>";
                        fcstTable = "";
                        
                        fcstTable += "<table class='map_utc_table' >";
                        
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
                        
                        $('#fcstValue').append(fcstTable);
                    
    	               	maxstep = resp['fcst_info']['utc_txt'].length -1;
    
    					display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
    
    					
                    	//setSlideAt(0);
                        // 슬라이드바를 직접 클릭할때 호출되는 함수
                        $("td[id^='Image_']").click(function() {
                            idx = $(this).attr("id").substr(6);
                            ImgIndex = idx;
                            
                            display_grph(var_select, model_sel, vrfy_idx, resp, idx, resp['arbi_data_head']);
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
    							display_grph(var_select, model_sel, vrfy_idx, resp, idx, resp['arbi_data_head']);
    							
    						} else if(e.keyCode == 39) { // right
    							idx = idx*1 +1;
    
                                if (idx > maxstep ){
    								idx = 0;
    							}
                                display_grph(var_select, model_sel, vrfy_idx, resp, idx, resp['arbi_data_head']);
    						}
                        });

                        
					} else {
    					// 그래프 표출 영역 초기화.
                        $('#fcstValue').empty();
					}
                    
					
                } // End of "success : function(resp)"

				, beforeSend: function () {
					// plot 버튼 중복 방지.
					$('#plotBtn').hide();

					// 로딩 이미지 표출.
                    var imgSrc = "<?php echo base_url('assets/img/data_loading/data_loading.gif');?>";
                    $('#fcstValue').append("<div id='div_ajax_load_image'><img id='img_load' src=" + imgSrc + "></div>");
				}

				, complete: function () {
					// plot 버튼 중복 방지.
					$('#plotBtn').show();
				}
                
                , error : function(error) 
                {
                    alert("error");
                    
                	// 그래프 표출 영역 초기화.
                	$('#fcstValue').empty();
                	$('#contValue').empty();
                	
                    console.log(error);
                }
        })
		
	}


	// 일기도 이미지 표출.
	function display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head) {

        $("td[id^='Image_']").removeClass("sliderSelected");
        var frameIdx = (idx * 1);
        $("#Image_"+frameIdx).addClass("sliderSelected");
		
		$('#contValue').empty();
		
                var peri = $("select[name=PERIOD]").val();
                var peri_arr = peri.split("_");

		var grph_div = "";
        for(var m=0; m<model_sel.length; m++) {
            for(var v=0; v<vrfy_idx.length; v++) {
                for(var d=0; d<resp['date_info'].length; d++) {
            		grph_div += "<div class='img_area col-lg-3' >";
                		grph_div += "<div class='map_header'>";
                			grph_div += var_select + "_" + model_sel[m] + "_" + vrfy_idx[v] + " " + resp['date_info'][d]['utc_info'];
                		grph_div += "</div>";
                		grph_div += "<div class='map_content'>";
                		
//							var nameOfgrph = data_head + model_sel[m] + "_" + var_select + "_VRFY_" + vrfy_idx[v] + "." + resp['date_info'][d]['range_mon'] + "_" +  resp['fcst_info']['utc_idx'][idx] + ".png";
//
//							var dt_arr = data_head.split("_");
//                			grph_div += "<img class='grph_img' src='" + "<?php echo base_url('GIFD/');?>" + dt_arr[1] + "/TEMP/" + nameOfgrph + "' onerror='no_image(this);' />" ;
                			

var nameOfgrph = peri_arr[1] + "_" + data_head + model_sel[m] + "_" + var_select + "_VRFY_" + vrfy_idx[v] + "." + resp['date_info'][d]['range_mon'] + "_" +  resp['fcst_info']['utc_idx'][idx] + ".png";

                                                        var dt_arr = data_head.split("_");
                                        grph_div += "<img class='grph_img' src='" + "<?php echo base_url('GIFD/');?>" + dt_arr[1] + "/TEMP/GRD/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;


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
			
</div>   

