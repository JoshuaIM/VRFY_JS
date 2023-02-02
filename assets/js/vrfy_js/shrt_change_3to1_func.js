// 단기 3시간 자료가 1시간으로 변경 됨으로써  사용되는 함수 모음.

// 1시간 자료 표출 시 DIV 크기 변경. col-lg-12 >> col-lg-1h[신규 추가 클래스]
function setShrt3H1HDisplay(sDate, eDate, var_select) {
	
	var selCont = "";
	
	var sDateFormat = sDate.substr(0,4) + "-" + sDate.substr(4,2) + "-01";
//	var sDateFormat = sDate.substr(0,4) + "-" + "10" + "-01";
	var eDateFormat = eDate.substr(0,4) + "-" + eDate.substr(4,2) + "-01";
//	var eDateFormat = eDate.substr(0,4) + "-" + "10" + "-01";
		
// TODO: 캘린더 사용 시 적용할 것.	
//	var listDate = [];
//		getDateRange(sDateFormat, eDateFormat, listDate);
	
//	var changeD = new Date("2020-10-01");
//	var startD = new Date(sDateFormat);
//	var endD = new Date(eDateFormat);
//		if( startD < changeD ) {
//			if( endD >= changeD ) {
//				shrtMonthAlert("3h");
//			}
//		} else {
//			if( endD < changeD ) {
//				shrtMonthAlert("1h");
//			}
//		}
			
//		if( !shrtMonthCheck(listDate[listDate.length-1]) ) {
		if( !shrtMonthCheck(eDateFormat) && var_select != "TMN" && var_select != "TMX" ) {
			selCont = "<div class='col-lg-1h mb'>||data1H";
		} else {
			selCont = "<div class='col-lg-12 mb'>||data3H";
		}

	return selCont;
}


//// 월과 월사이 모든 월 배열 반환.
//function getDateRange(startDate, endDate, listDate) {
//
//    var dateMove = new Date(startDate);
//    var strDate = startDate;
//
//    if (startDate == endDate) {
//        var strDate = dateMove.toISOString().slice(0,10);
//        listDate.push(strDate);
//        
//    } else {
//        while (strDate < endDate) {
//            var strDate = dateMove.toISOString().slice(0, 10);
//            listDate.push(strDate);
//            dateMove.setMonth(dateMove.getMonth() + 1);
//        }
//    }
//
//    return listDate;
//};
//
//


// 1시간 자료인지 3시간 자료인지 체크.
function shrtMonthCheck(chkDate) {

	var is3H = true;
	
	var checkD = new Date(chkDate);
	//var changeD = new Date("2020-10-01");
	var changeD = new Date(changeDateFormat);

	if( checkD >= changeD ) {
		is3H = false;
	}
	
	return is3H;
}
//	
//
//
//function shrtMonthAlert(shrtType) {
//	if(shrtType == "3h") {
//		alert("2020년 10월 이전으로 선택해 주세요. ");
//	} else if(shrtType == "1h") {
//		alert("2020년 10월 이후로 선택해 주세요. ");
//	}
//}


// 예측시간이 1시간 또는 3시간 자료인지 확인 후 변수 이름 변경.
function changeVrfyByVar(start_init, var_select) {
	
	// 2021-01-25 add by joshua.
	var var_change = "";

	var sd = start_init.substr(0,4) + "-" + start_init.substr(4,2) + "-01";
	
	if( var_select == "T1H" || var_select == "T3H" ) {
		if( shrtMonthCheck(sd) ) {
			var_change = "T3H";
		} else {
			var_change = "T1H";
		}
	} else if( var_select == "RN1" || var_select == "RN3" ) {
		if( shrtMonthCheck(sd) ) {
			var_change = "RN3";
		} else {
			var_change = "RN1";
		}
	} else if( var_select == "SN1" || var_select == "SN3" ) {
		if( shrtMonthCheck(sd) ) {
			var_change = "SN3";
		} else {
			var_change = "SN1";
		}
	} else {
		var_change = var_select;
	}
	// 2021-01-25 add by joshua.		

	return var_change;
}
	
	
	
	