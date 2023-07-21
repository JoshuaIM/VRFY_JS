function setZoomButton(peri) {
    if( peri === "MONTH" ) {
        $(".zoom_grph").css("display", "none");
    } else {
        $(".zoom_grph").show();
    }
}

// 줌 체크박스 확인.
function checkZoomGraph() {
    let zoom = $("#GRPH_ZOOM").is(':checked');
    return zoom;
}

// 줌 체크박스 확인 후 수행.
function setZoomGraph(isZoom, data_head) {
    if(isZoom) {
        $(".fcstTable").css("display", "none");
        // $(".col-lg-1h").css("width", "1100");
        $(".col-lg-1h").css("width", "1300");
        $(".col-lg-3h").css("width", "1300");
    } else {
        // if( data_head === "DFS_SHRT_STN_" ) {
        if( data_head === "DFS_SHRT_STN_" || data_head === "247DFS_SHRT_STN_" ) {
            $(".fcstTable").show();
            $(".col-lg-1h").css("width", "5000");
            $(".col-lg-3h").css("width", "3000");
        } else if( data_head === "DFS_MEDM_STN_" ) {
            $(".fcstTable").show();
            $(".col-lg-1h").css("width", "4000");
        }
    }
}