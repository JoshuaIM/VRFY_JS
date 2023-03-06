function changeBangjaeType(peri) {
    if( peri == "SPRING" || peri == "WINTER") {
        bangjaeON(peri);
    } else {
        bangjaeOFF();
    }
}

// 기본 데이트 선택 박스 ON (DEFAULT) - 방재기간 외 선택 시
function bangjaeOFF() {
    $(".original_date").show();
    // 방재기간 연도 선택 박스 초기화    
    $("#sel_year").children("option").remove()
    
    $(".bangjae_date").css("display", "none");
}

// 방재기간 선택 박스 ON
function bangjaeON(peri) {
    $(".original_date").css("display", "none");
    
    // 방재기간 연도 선택 박스 초기화    
    $("#sel_year").children("option").remove()

    // application/views/main/ts_stn.php 전역변수로 저장됨.
	switch (peri) {
		case "SPRING" : makeBangJaeYearOptions(SPRING); break;
		case "WINTER" : makeBangJaeYearOptions(WINTER); break;
    }

    $(".bangjae_date").show();
}

// 방재기간 선택 시 연도 선택 박스 생성.
function makeBangJaeYearOptions(yearArr) {
    for( let i=0; i<yearArr.length; i++ ) {
        let selected = (i == 0) ? "selected='selected'" : "";
        let option = "<option value='" + yearArr[i] + "'" + selected + ">" + yearArr[i] + "</option>";
        $("#sel_year").append(option);
    }
}