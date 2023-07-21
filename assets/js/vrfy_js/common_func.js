// bootstrap 메뉴 접기/펼치기 기능(css추정)이 onclick 함수[목적:getDataArray()] 이후에 발생되므로 
// 인위적으로 "getDataArray()"함수를 delay 줘서 css 이후 작동하도록 함.
// 접기를 눌렀을 시 css가 그래픽 범위를 늘린상태에서 "getDataArray()"함수를 실행시켜 그 크기에 맞는 그래픽(highcharts)을 표출.
// TODO : 그래픽 표출의 수가 많아 질 수록 delay 상태가 보인다.
function hideNview() {
	var delay=100;
	setTimeout( getDataArray, delay );
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
		dateFormat:'yymm', 
		changeYear: true, 
		autoclose: true, 
		//yearRange: "2018:2020",
		yearRange: "2020:" + thisYear,
		defaultDate: strDate
	});
	$('#sInitDate').datepicker('setDate', strDate);
	
	  $('#eInitDate').datepicker({
		  dateFormat:'yymm', 
		  changeYear: true, 
		  autoclose: true, 
		//yearRange: "2018:2020",
		yearRange: "2020:" + thisYear,
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





// 요소선택의 변수에 따라 00+12UTC 체크박스 생성 또는 삭제.
	function makeUTCopt(selVar, dateType) {
		if( selVar == "T3H" || selVar == "TMN" || selVar == "TMX" || selVar == "T1H" ) {
			$('#CHECK_SELECT > tbody:last > tr:last').remove();
		} else {
			if( dateType == "month" ) {
				var js_func = " onclick='getDataArray();'";
			} else {
				var js_func = " ";
			}
			
			// 00+12UTC가 이미 생성되어 있으면 '1', 없으면 '0' 이므로
			if ( $('#CHECK_SELECT > tbody:last > tr:last').length < 1 ) {
				var appOpt = "<tr><td><input type='checkbox' name='INIT_HOUR' value='00#12' " + js_func + "> 00UTC + 12UTC</td></tr>";
				
				$('#CHECK_SELECT > tbody:last').append(appOpt);
			}
		}
	}
	
	