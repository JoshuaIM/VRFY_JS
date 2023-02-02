// bootstrap 메뉴 접기/펼치기 기능(css추정)이 onclick 함수[목적:getDataArray()] 이후에 발생되므로 
// 인위적으로 "getDataArray()"함수를 delay 줘서 css 이후 작동하도록 함.
// 접기를 눌렀을 시 css가 그래픽 범위를 늘린상태에서 "getDataArray()"함수를 실행시켜 그 크기에 맞는 그래픽(highcharts)을 표출.
// TODO : 그래픽 표출의 수가 많아 질 수록 delay 상태가 보인다.
function hideNview() {
	var delay=100;
	setTimeout( getDataArray, delay );
}
	
function setArbiDateFormat(sDate, eDate) {
	
	var sDateFormat = "";
		if( sDate.length == 6 ) {
			sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2) + "-01";
		} else if( sDate.length == 8 ) {
			sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2) + "-" +sDate.substr(6,2);
		}
	var eDateFormat = "";
		if( eDate.length == 6 ) {
			eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2) + "-01";
		} else if( sDate.length == 8 ) {
			eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2) + "-" +eDate.substr(6,2);
		}
	
	var strDate;
	var endDate;
		if( sDate > eDate ) {
			$('#sInitDate').val(eDate)
	        strDate = new Date(eDateFormat);
		} else {
	        strDate = new Date(sDateFormat);
		}
		endDate = new Date(eDateFormat);

	changeArbiDatePicker(strDate, endDate);

    // onchange 이 후 텍스트 박스에서 커서 끔.
//  	calendarID.blur();
}


function changeArbiDatePicker(setStrDate, setEndDate) {

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
        //yearRange: "2018:2020",
        yearRange: "2018:" + thisYear,
        defaultDate: strDate
    });
	$('#sInitDate').datepicker('setDate', strDate);
	
  	$('#eInitDate').datepicker({
	  	dateFormat:'yymmdd', 
	  	changeYear: true, 
	  	autoclose: true, 
        //yearRange: "2018:2020",
        yearRange: "2018:" + thisYear,
	  	defaultDate: endDate
  	});
	$('#eInitDate').datepicker('setDate', endDate);
}

	
	
	
	