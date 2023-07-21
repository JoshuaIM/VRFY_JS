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
        yearRange: "2020:" + thisYear,
        defaultDate: strDate
    });
	$('#sInitDate').datepicker('setDate', strDate);
	
  	$('#eInitDate').datepicker({
	  	dateFormat:'yymmdd', 
	  	changeYear: true, 
	  	autoclose: true, 
        //yearRange: "2018:2020",
        yearRange: "2020:" + thisYear,
	  	defaultDate: endDate
  	});
	$('#eInitDate').datepicker('setDate', endDate);
}

	
	
	
	