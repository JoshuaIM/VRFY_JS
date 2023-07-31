// 중복 선택 가능 체크박스에서 모든 체크박스 풀리지 않도록 체크하는 함수
function checkNoneSelectBox(document_name, document_value) {

    const doc_name = document_name;
    let checked_arr = new Array();
    $("input[name=" + doc_name + "]:checked").each(function() {
        checked_arr.push($(this).val());
    });

    if( checked_arr.length == 0 ) {
        $("input[name=" + doc_name + "][value='" + document_value + "']").prop("checked", true);
    }
}


// 예보편집 : 요소선택 전체선택 기능 추가 및 모든 체크박스가 풀리지 않도록 체크.
// function checkVariable(this.name, this.value, this.id) {
    function checkVariable(document_name, document_value, document_id) {
        
        const chk_box_obj = $("input[name=" + document_name + "][value!=ALL]");
        const chk_total_num = chk_box_obj.length;
        
        const chked_box = $("input[name=" + document_name + "][value!=ALL]:checked");
        const chked_num = chked_box.length;

        // 전체선택 클릭 시
        if( document_value === "ALL" ) {
            // 모두 선택이 되었을 시 기온빼고 모두 해제.
            if( chk_total_num == chked_num ) {
                $("input[name=" + document_name + "]").prop("checked", false);
                $("input[name=" + document_name + "][value=T1H]").prop("checked", true);
            } else {
                $("input[name=" + document_name + "]").prop("checked", true);
            }
            
        } else {
            
            if( chk_total_num == chked_num ) {
                $("input[name=" + document_name + "][value=ALL]").prop("checked", true);
            } else {
                $("input[name=" + document_name + "][value=ALL]").prop("checked", false);
                if( chked_num == 0 ) {
                    $("input[name=" + document_name + "][value='" + document_value + "']").prop("checked", true);
                }
            }

        }




}