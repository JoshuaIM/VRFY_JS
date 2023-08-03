function changeBangjaeType(peri) {

    if( peri === "BANGJAE") {
        bangjaeON();
    } else if ( peri === "SEASON" ) {
        seasonON();
    } else if ( peri === "ALLMONTH") {
        allmonthON();
    } else {
        originalON();
    }
}

// 기본 데이트 선택 박스 ON (DEFAULT) - 방재기간 외 선택 시
function originalON() {
    $(".original_date").show();
    // 방재기간 연도 선택 박스 초기화    
    $("#sel_year").children("option").remove()
    
    $(".bangjae_date").css("display", "none");
    $(".season_date").css("display", "none");
    $(".allmonth_date").css("display", "none");
}

// 방재기간 선택 박스 ON
function bangjaeON() {

    $(".original_date").css("display", "none");
    $(".season_date").css("display", "none");
    $(".allmonth_date").css("display", "none");
    
    // 방재기간 연도 선택 박스 초기화    
    $("#select_bangjae_date").empty();

    let year_arr = getOnlyYearArr(BANGJAE);
    makeBangJaeYearOptions(year_arr);

    makeBangJaeSeasonOptions(BANGJAEMAP);

    $(".bangjae_date").show();
}

// 계절별 선택 박스 ON
function seasonON() {
    $(".original_date").css("display", "none");
    $(".bangjae_date").css("display", "none");
    $(".allmonth_date").css("display", "none");
    
    // 계절별 연도 선택 박스 초기화    
    $("#select_season_date").empty();

    let year_arr = getOnlyYearArr(SEASON);
    makeSeasonYearOptions(year_arr);

    makeSeasonSeasonOptions(SEASONMAP);

    $(".season_date").show();
}

// 전체기간 선택 박스 ON
function allmonthON() {
    $(".original_date").css("display", "none");
    $(".bangjae_date").css("display", "none");
    $(".season_date").css("display", "none");

    $(".allmonth_date").show();
}

function getOnlyYearArr(date_arr) {
    let year_arr = new Array();
    for(let i=0; i<date_arr.length; i++) {
        let year = date_arr[i].substring(0,4);
        year_arr.push(year);
    }

    return year_arr;
}

function makeBangJaeYearOptions(year_arr) {
    // 중복 제거.
    let yearArr = year_arr.filter( (v, i) => year_arr.indexOf(v) === i );
    // 연도 셀렉트 박스 생성.
    for( let i=0; i<yearArr.length; i++ ) {
        let selected = (i == 0) ? "selected='selected'" : "";
        let option = "<option value='" + yearArr[i] + "'" + selected + ">" + yearArr[i] + "</option>";
        $("#select_bangjae_date").append(option);
    }
}

function makeSeasonYearOptions(year_arr) {
    // 중복 제거.
    let yearArr = year_arr.filter( (v, i) => year_arr.indexOf(v) === i );
    // 연도 셀렉트 박스 생성.
    for( let i=0; i<yearArr.length; i++ ) {
        let selected = (i == 0) ? "selected='selected'" : "";
        let option = "<option value='" + yearArr[i] + "'" + selected + ">" + yearArr[i] + "</option>";
        $("#select_season_date").append(option);
    }
}

function makeBangJaeSeasonOptions(BANGJAEMAP) {

    // 방재기간 연도 선택에 따른 시즌 박스 초기화    
    $("#select_bangjae_season").children("option").remove()    

    let select_year = $("#select_bangjae_date").val();
    let season_arr = BANGJAEMAP[select_year];
    for( let i=0; i<season_arr.length; i++ ) {
        let selected = (i == 0) ? "selected='selected'" : "";
        let option_txt = (season_arr[i] === "05") ? "여름철" : "겨울철";
        let option = "<option value='" + season_arr[i] + "'" + selected + ">" + option_txt + "</option>";
        $("#select_bangjae_season").append(option);
    }
}

function makeSeasonSeasonOptions(SEASONMAP) {

    // 계절별 연도 선택에 따른 시즌 박스 초기화    
    $("#select_season_season").children("option").remove()    

    let select_year = $("#select_season_date").val();
    let season_arr = SEASONMAP[select_year];
    for( let i=0; i<season_arr.length; i++ ) {
        let selected = (i == 0) ? "selected='selected'" : "";
        let option_txt = getSeasonSelectTxt(season_arr[i]);
        let option = "<option value='" + season_arr[i] + "'" + selected + ">" + option_txt + "</option>";
        $("#select_season_season").append(option);
    }
}
function getSeasonSelectTxt(mm) {
    let season_name = "";
	switch (mm) {
		case "03" 	: season_name = "봄철";   break;
		case "06" 	: season_name = "여름철"; break;
		case "09" 	: season_name = "가을철"; break;
		case "12"   : season_name = "겨울철"; break;
    }
    return season_name;
}


function setBangjaeInitDate(fileName) {
    // 초기화
    $('#bangjae_startD').attr("value", "");
    $('#bangjae_endD').attr("value", "");

    let split_arr = fileName.split(".");
    let date_name = split_arr[split_arr.length -1];
    let split_date = date_name.split("_");

    $('#bangjae_startD').attr("value", split_date[0].substring(0,8));
    $('#bangjae_endD').attr("value",split_date[1].substring(0,8));
}


function setNodataBangjaeInitDate(bangjae_date) {
    // 초기화
    $('#bangjae_startD').attr("value", "");
    $('#bangjae_endD').attr("value", "");
    
    // TODO: 잠시 사용
    let tmp_y = bangjae_date.substring(0,4);
    let tmp_m = bangjae_date.substring(4,6);
    
    let start_d = "";
    let end_d = "";
    if( tmp_m === "05" ) {
        start_d = tmp_y + tmp_m + "15";
        end_d = tmp_y + "1015";
    } else if( tmp_m === "11" ) {
        start_d = tmp_y + tmp_m + "15";
        let change_y = parseInt(tmp_y) + 1;
        end_d = change_y + "0315";
    }

    $('#bangjae_startD').attr("value", start_d);
    $('#bangjae_endD').attr("value", end_d);
}


function setSeasonInitDate(fileName) {
    // 초기화
    $('#bangjae_startD').attr("value", "");
    $('#bangjae_endD').attr("value", "");

    let split_arr = fileName.split(".");
    let date_name = split_arr[split_arr.length -1];
    let split_date = date_name.split("_");

    $('#season_startD').attr("value", split_date[0].substring(0,8));
    $('#season_endD').attr("value",split_date[1].substring(0,8));
}


function setAllmonthInitDate(fileName) {
    // 초기화
    $('#allmonth_startD').attr("value", "");
    $('#allmonth_endD').attr("value", "");

    let split_arr = fileName.split(".");
    let date_name = split_arr[split_arr.length -1];
    let split_date = date_name.split("_");

    $('#allmonth_startD').attr("value", split_date[0].substring(0,8));
    $('#allmonth_endD').attr("value",split_date[1].substring(0,8));
}



