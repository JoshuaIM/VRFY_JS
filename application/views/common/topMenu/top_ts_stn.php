<script type="text/javascript">

// 2020년 12월 부터 1시간 자료 표출.
var changeDateFormat = "2020-12-01";


    // 캘린더 날짜 선택 호출 메서드.
    function chkCalendar(calendarID) {
    	var sDate = $('#sInitDate').val();
    	var eDate = $('#eInitDate').val();

//         	// 기간 선택 값이 6자(YYYYMM)가 맞는지 체크.
//         	if( sDate.length != 6 && eDate.length != 6) {
//     			alert("날짜 형식이 잘못되었습니다.");
//     		return false;
//         	}

		var sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2);
		var eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2);

// TODO : 2021-01-22 백업 - Default
//     	var strDate;
//     	var endDate;
//     	if( sDate > eDate ) {
//     		$('#sInitDate').val(eDate)
//             strDate = new Date(eDateFormat);
//     	} else {
//             strDate = new Date(sDateFormat);
//     	}
// 			endDate = new Date(eDateFormat);

// 		changeDatePicker(strDate, endDate);

//         // onchange 이 후 텍스트 박스에서 커서 끔.
// 	  	calendarID.blur();

// 	  	getDataArray();

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
				var temp_var = changeVrfyByVar(temp_start_init, temp_select_var);

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
        		<option value="MONTH">월별</option>	
        		<!-- <option value="SPRING">방재기간(여름철)</option>	
        		<option value="WINTER">방재기간(겨울철)</option>	 -->
        	</select>

			<!-- 방재기간 선택 시 ON -->
        	<!-- <select id="sel_year" name="SELYEAR" class="dateSelBox bangjae_date" onChange="getDataArray();">
			</select>
            <div class="btn-group  bangjae_date" >
            	<input class="dateBox" id="bangjae_startD" type="text" style="width:85px; background:#E0E3DA" value="readonly" readonly />
            </div> -->
			<!-- 방재기간 선택 시 ON -->

            <div class="btn-group original_date" >
            	<input class="dateBox" id="sInitDate" name="sInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" />
            </div>
            <div class="btn-group original_date" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openSCalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div>
        
        	<b class="date_wave">~</b>
        
			<!-- 방재기간 선택 시 ON -->
            <!-- <div class="btn-group  bangjae_date" >
            	<input class="dateBox" id="bangjae_endD" type="text" style="width:85px; background:#E0E3DA" value="readonly" readonly />
            </div> -->
			<!-- 방재기간 선택 시 ON -->

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
            	<button class="totalDownBtn" type="button" onclick="makeCSVfile();">DOWNLOAD CSV FILE</button>
            </div>
			<!--
			<div class="btn-group" >
				<div class="grphZoomOut">
					<input style="" name="" value="" type="checkbox"> <text>전체그래프보기</text>
				</div>
			</div>
			-->
    	</div>
    	
    	<div id="vrfySelect" class="verifIndexArea" >
    	</div>
    </div>   
</section>