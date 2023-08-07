// 첫 페이지 또는 NOW 버튼 클릭 시 실행.
    function readyAndNowFunc() {
		// assets/js/vrfy_js/common_func.js
    	changeDatePicker();

		// 검증 지수 셀렉트박스 생성.
		if( type === "GEMD" && grph_type === "map" )
		{
			// 활용도 셀렉트박스 생성.
			makeVrfySelectUtilizeMap(utilizeTech['data_vrfy'], utilizeTech['txt_vrfy'], data_type, dateType);
			
			// 검증 지수 셀렉트박스 생성.
			// assets/js/vrfy_js/common_func.js
    		makeVrfySelect(vrfy_data, vrfy_txt, data_type, dateType);
		}
		else
		{
			// assets/js/vrfy_js/common_func.js
			makeVrfySelect(vrfy_data, vrfy_txt, data_type);
		}
    	getDataArray();


		const test_cont = document.querySelector(".cont_cover");

		test_cont.addEventListener("scroll", () => {
console.log("do");
			const scrollLeft = test_cont.scrollLeft;
			const scrollTop = test_cont.scrollTop;
			const content = document.querySelector("#contValue");
			content.style.transform = `translate(${-scrollLeft}px, ${-scrollTop}px)`;
		});
    }
 