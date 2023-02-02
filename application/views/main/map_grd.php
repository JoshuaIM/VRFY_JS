<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/get_variable_unit.js');?>"></script>
<!-- // 시계열 표출 시 "좌측 메뉴 접기/펼치기" 기능을 표현. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_common_func.js');?>"></script>
<!-- // 월 자료(기본: 임의기간이 아닌)를 사용하는 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/month_common_func.js');?>"></script>
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


    	callAjaxUtcInfo(data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, peri);
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
	function callAjaxUtcInfo(data_head, var_select, init_hour, model_sel, start_init, end_init, vrfy_idx, map_type, peri) {
    	$.ajax({
            type : "POST",
                data :
                {
                    // 지점 자료의 예측시간 헤더를 읽기 위해 기존 격자 자료의 파일 이름 "DFS_SHRT_GRD_" 이 아닌 지점 자료의 파일 이름 사용.
    				"data_head" : "DFS_SHRT_STN_",
    				"var_select" : var_select,
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

					// 그래프 표출 영역 초기화.
                	$('#fcstValue').empty();

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
                        
                        display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
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
							display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
							
						} else if(e.keyCode == 39) { // right
							idx = idx*1 +1;

                            if (idx > maxstep ){
								idx = 0;
							}
                            display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
						}
                    });
                    
					
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
							
                			grph_div += "<img class='grph_img' src='" + "<?php echo base_url('GIFD/SHRT');?>" + "/" + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
                			
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

