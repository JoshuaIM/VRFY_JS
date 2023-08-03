<script type="text/javascript">

// 2020년 12월 부터 1시간 자료 표출.
var changeDateFormat = "2020-12-01";


    // 캘린더 날짜 선택 호출 메서드.
    function chkCalendar(calendarID) {
    	var sDate = $('#sInitDate').val();
    	var eDate = $('#eInitDate').val();
		var sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2);
		var eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2);

// 2021-01-22 1시간 자료 표출을 위해 추가.
		var startD = "";
		var endD = "";
		
    	var strDate;
    	var endDate;
    	if( sDate > eDate ) {
        	// 2021-01-25 edit by joshua.
    		//$('#sInitDate').val(eDate)
            //strDate = new Date(eDateFormat);
            strDate = new Date(sDateFormat);
    		endDate = strDate;
    	} else {
            strDate = new Date(sDateFormat);
    		endDate = new Date(eDateFormat);
    	}

		var changeD = new Date(changeDateFormat);
		
		if( strDate < changeD ) {
			if( endDate >= changeD ) {
				alert("끝 날짜를 2020년 12월 이전의 날짜로 맞춰 주세요.");
				endDate = strDate;
			}
		}

		changeDatePicker(strDate, endDate);

		
        // onchange 이 후 텍스트 박스에서 커서 끔.
		calendarID.blur();
		
		//getDataArray();

		
		// 2021-03-25 : 시간 강수량의 경우 2020-12 이 전과 이 후의 검증지수가 변경되므로 selVar()함수를 통해 검증지수 세팅을 다시 해줘야 함.
		var temp_select_var = $("select[name=VAR]").val();
		
			if( temp_select_var == "RN1" || temp_select_var == "RN3" ) {

				var temp_start_init = $("input:text[name='sInitDate']").val();
				// var temp_var = changeVrfyByVar(temp_start_init, temp_select_var);
				var temp_var = temp_select_var;				

				selVar(temp_var);
			} else {
				getDataArray();
			}

    }

</script>

<section class="top_wrapper">
    <div class="containter" style="height:100%;">
        <div class="dateSelect" >

        	<select id="data_period" name="PERIOD" class="dateSelBox" onChange="changeBangjaeType(this.value); getDataArray();">
        		<option value="FCST" selected>예측기간(월별)</option>	
				<?php 
				if ( $type != "SSPS" )
				{
				?>
					<option value="SEASON">계절별</option>	
					<option value="BANGJAE">방재기간별</option>	
					<option value="ALLMONTH">전체기간</option>	
				<?php
				}
				?>
        	</select>

			<!-- 방재기간 선택 시 ON -->
        	<select id="select_bangjae_date" name="SELYEAR" class="dateSelBox bangjae_date" onChange=" makeBangJaeSeasonOptions(BANGJAEMAP); getDataArray();" ></select>
        	<select id="select_bangjae_season" name="SELSEASON" class="dateSelBox bangjae_date" onChange="getDataArray();" ></select>
            <div class="btn-group  bangjae_date" >
            	<input class="dateBox" id="bangjae_startD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 방재기간 선택 시 ON -->

			<!-- 계절별 선택 시 ON -->
        	<select id="select_season_date" name="SELYEAR" class="dateSelBox season_date" onChange=" makeSeasonSeasonOptions(SEASONMAP); getDataArray();" ></select>
        	<select id="select_season_season" name="SELSEASON" class="dateSelBox season_date" onChange="getDataArray();" ></select>
            <div class="btn-group  season_date" >
            	<input class="dateBox" id="season_startD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 계절별 선택 시 ON -->

			<!-- 전체기간 선택 시 ON -->
            <div class="btn-group  allmonth_date" >
            	<input class="dateBox" id="allmonth_startD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 전체기간 선택 시 ON -->

            <div class="btn-group original_date" >
				<input class="dateBox" id="sInitDate" name="sInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" />
            	<!-- <input class="dateBox" id="cal1" /> -->
            </div>
            <div class="btn-group original_date" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openSCalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div>
        
        	<b class="date_wave">~</b>
        
			<!-- 방재기간 선택 시 ON -->
            <div class="btn-group  bangjae_date" >
            	<input class="dateBox" id="bangjae_endD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 방재기간 선택 시 ON -->

			<!-- 계절별 선택 시 ON -->
            <div class="btn-group  season_date" >
            	<input class="dateBox" id="season_endD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 계절별 선택 시 ON -->

			<!-- 전체기간 선택 시 ON -->
            <div class="btn-group  allmonth_date" >
            	<input class="dateBox" id="allmonth_endD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 전체기간 선택 시 ON -->

            <div class="btn-group original_date">
            	<input class="dateBox" id="eInitDate" name="eInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" />
            </div>
            <div class="btn-group original_date" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openECalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div>
            <div class="btn-group">
            	<button class="nowBtn original_date" type="button" onclick="readyAndNowFunc();">NOW</button>
            </div>

			<div class="btn-group viewPerformTable">
				<!-- <button class="performTableBtn" type="button" onclick="">예측 성능 비교표 보기</button> -->
			</div>

    	</div>
    	
		<?php if( $type === "GEMD" ) { ?>
			<div id="utilizeSelect" class="verifIndexArea" >
			</div>
			<div class="verifIndexArea" style="width:100%; height:.1px;"></div>
			<div id="vrfySelect" class="verifIndexArea" >
			</div>
		<?php } else { ?>
			<!-- assets/js/vrfy_js/common_func.js -->
			<div id="vrfySelect" class="verifIndexArea" >
			</div>
		<?php } ?>

    </div>   
</section>