<script type="text/javascript">

//2020년 12월 부터 1시간 자료 표출.
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
    	// var strDate;
    	// var endDate;
    	// if( sDate > eDate ) {
    	// 	$('#sInitDate').val(eDate)
        //     strDate = new Date(eDateFormat);
    	// } else {
        //     strDate = new Date(sDateFormat);
    	// }
		// 	endDate = new Date(eDateFormat);

		// changeDatePicker(strDate, endDate);

        // // onchange 이 후 텍스트 박스에서 커서 끔.
	  	// calendarID.blur();

	  	// getDataArray();


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

	  	getDataArray();

    }
</script>

<section class="top_wrapper">
    <div class="containter" style="height:100%;">
        <div class="dateSelect" >
        	<select id="data_period" name="PERIOD" class="dateSelBox" onChange="getDataArray();">
        		<option value="FCST" selected>예측기간(월별)</option>	
        	</select>
            <div class="btn-group" >
            	<input class="dateBox" id="sInitDate" name="sInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" />
            </div>
            <div class="btn-group" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openSCalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div>
        
        	<b class="date_wave">~</b>
        
            <div class="btn-group">
            	<input class="dateBox" id="eInitDate" name="eInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" />
            </div>
            <div class="btn-group" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openECalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div>
            <div class="btn-group">
            	<button class="nowBtn" type="button" onclick="readyAndNowFunc();">NOW</button>
            </div>
    	</div>
    	
    	<div id="vrfySelect" class="verifIndexArea" >
    	</div>
    	
    </div>   
</section>