// 첫 페이지 또는 NOW 버튼 클릭 시 실행.
    function readyAndNowFunc() {
		// assets/js/vrfy_js/month_common_func.js
    	changeDatePicker(null, null);

		// 검증 지수 셀렉트박스 생성.
		// assets/js/vrfy_js/common_func.js
    	makeVrfySelect(vrfy_data, vrfy_txt, data_type);
    	
    	getDataArray();
    }