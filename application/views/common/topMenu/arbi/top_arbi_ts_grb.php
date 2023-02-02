<script type="text/javascript">

    // 캘린더 날짜 선택 호출 메서드.
    function chkCalendar(calendarID) {
    	var sDate = $('#sInitDate').val();
    	var eDate = $('#eInitDate').val();

		var sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2) + "-" + sDate.substr(6,2);
		var eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2) + "-" + eDate.substr(6,2);

    	var strDate;
    	var endDate;
    	if( sDate > eDate ) {
    		$('#sInitDate').val(eDate)
            strDate = new Date(eDateFormat);
    	} else {
            strDate = new Date(sDateFormat);
    	}
			endDate = new Date(eDateFormat);

		changeArbiDatePicker(strDate, endDate, 'yymmdd');

        // onchange 이 후 텍스트 박스에서 커서 끔.
	  	calendarID.blur();
    }


    
    function oneCheckbox(a){

        var obj = document.getElementsByName("VRFY_INDEX");

        for(var i=0; i<obj.length; i++){
            if(obj[i] != a){
                obj[i].checked = false;
            }
        }
		getDataArray();
		
    }
</script>

<section class="top_wrapper">
    <div class="containter" style="height:100%;">
        <div class="dateSelect" >
        	<select id="data_period" name="PERIOD" class="dateSelBox" >
        		<option value="ARBI_INIT">시작시간</option>	
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
