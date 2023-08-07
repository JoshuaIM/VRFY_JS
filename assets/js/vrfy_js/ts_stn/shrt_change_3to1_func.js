// 단기 3시간 자료가 1시간으로 변경 됨으로써  사용되는 함수 모음.

// 1시간 자료 표출 시 DIV 크기 변경. col-lg-12 >> col-lg-1h[신규 추가 클래스]
function setShrt3H1HDisplay(sDate, eDate, var_select, peri) {
	
	var sel_cont = "";
	
	if ( peri === "MONTH" )
	{
		sel_cont = "<div class='col-lg-11 mb'>||data1H";
	}
	else 
	{
		var sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2) + "-01";
		var eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2) + "-01";
			if( !shrtMonthCheck(eDateFormat) && var_select != "TMN" && var_select != "TMX" ) {
				if( var_select === "SN3" && type === "SHRT" ) {
					sel_cont = "<div class='col-lg-3h mb'>||data3H";
				} else {
					sel_cont = "<div class='col-lg-1h mb'>||data1H";
				}
			} else {
				sel_cont = "<div class='col-lg-12 mb'>||data3H";
			}
	}

	return sel_cont;
}


// 1시간 자료인지 3시간 자료인지 체크.
function shrtMonthCheck(chkDate) {

	// 2020년 12월 부터 1시간 자료 표출.
	var changeDateFormat = "2020-12-01";
	
	var is3H = true;
	
	var checkD = new Date(chkDate);
	//var changeD = new Date("2020-10-01");
	var changeD = new Date(changeDateFormat);

	if( checkD >= changeD ) {
		is3H = false;
	}
	
	return is3H;
}
	
	
	