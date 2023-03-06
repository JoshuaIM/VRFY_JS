

//    // 첫 페이지 자료표출을 위해 "getDataArray()" 메서드 실행.
//    $(document).ready(function(){
//    	readyAndNowFunc();
//    });
//    
//	function readyAndNowFunc() {
//		changeDatePicker(null, null);
//		
//		makeVrfySelect();
//		
//		getDataArray();
//	}
    
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
            dateFormat:'yymm', 
            changeYear: true, 
            autoclose: true, 
            //yearRange: "2018:2020",
            yearRange: "2018:" + thisYear,
            defaultDate: strDate
        });
    	$('#sInitDate').datepicker('setDate', strDate);
    	
      	$('#eInitDate').datepicker({
    	  	dateFormat:'yymm', 
    	  	changeYear: true, 
    	  	autoclose: true, 
            //yearRange: "2018:2020",
            yearRange: "2018:" + thisYear,
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
		// js/vrfy_js/get_station_name.js
		setSubLocation(subLocVal);
		getDataArray();
	}

	function listingSubLocSSPS(subLocVal) {
		// js/vrfy_js/get_station_name.js
		setSubLocationSSPS(subLocVal);
		getDataArray();
	}