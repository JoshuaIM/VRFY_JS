	function get_station_name(stnid)
	{
console.log('get_station_name.js : stnid', stnid);
		let stn_name = "";
		switch (stnid) {
			case "AVEEVL" 	: stn_name = "표준검증지점"; break;
			case "AVE" 		: stn_name = "전체지점"; break;
			case "mean" 	: stn_name = "권역평균"; break;

			case "ST247" 	: stn_name = "표준검증지점(247개)"; break;
			case "STEVL" 	: stn_name = "표준검증지점"; break;
			case "ST308" 	: stn_name = "전체지점"; break;

			// gropID 47200 - 북한
			case "47003" : stn_name = "선봉";		break;
			case "47005" : stn_name = "삼지연";		break;
			case "47008" : stn_name = "청진";		break;
			case "47014" : stn_name = "청강";		break;
			case "47016" : stn_name = "혜산";		break;
			case "47020" : stn_name = "강계";		break;
			case "47022" : stn_name = "풍산";		break;
			case "47025" : stn_name = "김책";		break;
			case "47028" : stn_name = "수풍";		break;
			case "47031" : stn_name = "창진";		break;
			case "47035" : stn_name = "신의주";		break;
			case "47037" : stn_name = "구성";		break;
			case "47039" : stn_name = "희천";		break;
			case "47041" : stn_name = "함흥";		break;
			case "47046" : stn_name = "신포";		break;
			case "47050" : stn_name = "안주";		break;
			case "47052" : stn_name = "양덕";		break;
			case "47055" : stn_name = "원산";		break;
			case "47058" : stn_name = "평양";		break;
			case "47060" : stn_name = "남포";		break;
			case "47061" : stn_name = "장전";		break;
			case "47065" : stn_name = "사리원";		break;
			case "47067" : stn_name = "신계";		break;
			case "47068" : stn_name = "룡연";		break;
			case "47069" : stn_name = "혜주";		break;
			case "47070" : stn_name = "개성";		break;
			case "47075" : stn_name = "평강";		break;
			
			// gropID 47109 - 서울,경기	
			case "47098" : stn_name = "동두천";		break;
			case "47099" : stn_name = "파주";		break;
			// TODO: 없음
			case "47102" : stn_name = "백령도";		break;
			case "47108" : stn_name = "서울";		break;
			case "47112" : stn_name = "인천";		break;
			case "47119" : stn_name = "수원";		break;
			case "47201" : stn_name = "강화";		break;
			case "47202" : stn_name = "양평";		break;
			case "47203" : stn_name = "이천";		break;
			
			// gropID 47105 - 강원도	
			case "47090" : stn_name = "속초";		break;
			case "47095" : stn_name = "철원";		break;
			case "47100" : stn_name = "대관령";		break;
			case "47101" : stn_name = "춘천";		break;
			case "47104" : stn_name = "북강릉";		break;
			case "47105" : stn_name = "강릉";		break;
			case "47106" : stn_name = "동해";		break;
			case "47114" : stn_name = "원주";		break;
			case "47121" : stn_name = "영월";		break;
			case "47211" : stn_name = "인제";		break;
			case "47212" : stn_name = "홍천";		break;
			case "47216" : stn_name = "태백";		break;
			case "47217" : stn_name = "정선군";		break;
				
			// gropID 47159 - 경상남도
			case "47152" : stn_name = "울산";		break;
			case "47155" : stn_name = "창원";		break;
			case "47159" : stn_name = "부산";		break;
			case "47162" : stn_name = "통영";		break;
			case "47192" : stn_name = "진주";		break;
			case "47255" : stn_name = "북창원";		break;
			case "47284" : stn_name = "거창";		break;
			case "47285" : stn_name = "합천";		break;
			case "47288" : stn_name = "밀양";		break;
			case "47289" : stn_name = "산청";		break;
			case "47294" : stn_name = "거제";		break;
			case "47295" : stn_name = "남해";		break;
			
			// gropID 47143 - 경상북도
			case "47115" : stn_name = "울릉도";		break;
			case "47130" : stn_name = "울진";		break;
			case "47136" : stn_name = "안동";		break;
			case "47137" : stn_name = "상주";		break;
			case "47138" : stn_name = "포항";		break;
			case "47143" : stn_name = "대구";		break;
			case "47271" : stn_name = "봉화";		break;
			case "47272" : stn_name = "영주";		break;
			case "47273" : stn_name = "문경";		break;
			case "47277" : stn_name = "영덕";		break;
			case "47278" : stn_name = "의성";		break;
			case "47279" : stn_name = "구미";		break;
			case "47281" : stn_name = "영천";		break;
			case "47283" : stn_name = "경주";		break;
			// TODO: 없음
			case "00096" : stn_name = "독도";		break;
			
			// gropID 47131 - 충청북도
			case "47127" : stn_name = "충주";		break;
			case "47131" : stn_name = "청주";		break;
			case "47135" : stn_name = "추풍령";		break;
			case "47221" : stn_name = "제천";		break;
			case "47226" : stn_name = "보은";		break;
			
			// gropID 47133 - 충청남도
			case "47129" : stn_name = "서산";		break;
			case "47133" : stn_name = "대전";		break;
			case "47177" : stn_name = "홍성";		break;
			case "47232" : stn_name = "천안";		break;
			case "47235" : stn_name = "보령";		break;
			case "47236" : stn_name = "부여";		break;
			case "47238" : stn_name = "금산";		break;
			
			// gropID 47156 - 전라남도
			case "47156" : stn_name = "광주";		break;
			case "47165" : stn_name = "목포";		break;
			case "47168" : stn_name = "여수";		break;
			case "47169" : stn_name = "흑산도";		break;
			case "47170" : stn_name = "완도";		break;
			case "47174" : stn_name = "순천";		break;
			case "47259" : stn_name = "강진군";		break;
			case "47260" : stn_name = "장흥";		break;
			case "47261" : stn_name = "해남";		break;
			case "47262" : stn_name = "고흥";		break;
			case "47268" : stn_name = "진도군";		break;
			
			// gropID 47146 - 전라북도
			case "47140" : stn_name = "군산";		break;
			case "47146" : stn_name = "전주";		break;
			case "47172" : stn_name = "고창";		break;
			case "47243" : stn_name = "부안";		break;
			case "47244" : stn_name = "임실";		break;
			case "47245" : stn_name = "정읍";		break;
			case "47247" : stn_name = "남원";		break;
			case "47248" : stn_name = "장수";		break;

			// gropID 47184 - 제주도
			case "47184" : stn_name = "제주";		break;
			case "47185" : stn_name = "고산";		break;
			case "47188" : stn_name = "성산";		break;
			case "47189" : stn_name = "서귀포";		break;
			case "47299" : stn_name = "이어도";		break;
			case "329"	 : stn_name = "산천단";		break;
			case "885"	 : stn_name = "태풍센터";	break;
			
			// gropID 47113 - 공항
			case "47092" : stn_name = "양양공항";	break;
			case "47110" : stn_name = "김포공항";	break;
			case "47113" : stn_name = "인천공항";	break;
			case "47128" : stn_name = "청주공항";	break;
			case "47151" : stn_name = "울산공항";	break;
			case "47163" : stn_name = "무안공항";	break;
			case "47167" : stn_name = "여수공항";	break;
			case "47139" : stn_name = "포항공항";	break;
			case "47142" : stn_name = "대구공항";	break;
			case "47153" : stn_name = "김해공항";	break;
			case "47158" : stn_name = "광주공항";	break;
			case "47161" : stn_name = "사천공항";	break;
			case "47182" : stn_name = "제주공항";	break;
			

			// gropID 10112 - 산악 (다른 메인페이지)
			case "10112" : stn_name = "성북";				break;
			case "10122" : stn_name = "미시령";				break;
			case "10142" : stn_name = "치악산";				break;
			case "10151" : stn_name = "계룡산";				break;
			case "10172" : stn_name = "송계";				break;
			case "10193" : stn_name = "뱀사골";				break;
			case "10202" : stn_name = "내장산";				break;
			case "10211" : stn_name = "설천봉";				break;
			case "10213" : stn_name = "덕유산";				break;
			case "10222" : stn_name = "주왕산";				break;
			case "10262" : stn_name = "성판악";				break;
			case "10272" : stn_name = "어리목";				break;
			case "10282" : stn_name = "조선대";				break;
			case "40262" : stn_name = "뱀사골";				break;
			case "40281" : stn_name = "진도(레)";			break;
			case "40497" : stn_name = "삽당령";				break;
			case "40595" : stn_name = "진부령";				break;
			case "40980" : stn_name = "신림터널";			break;
			case "40984" : stn_name = "안흥";				break;
			case "40990" : stn_name = "구사리재";			break;
			case "40997" : stn_name = "만항재";				break;
			case "10252" : stn_name = "고령 문수봉";		break;
			case "30191" : stn_name = "영취산병봉";			break;
			case "40321" : stn_name = "양산 금오산";		break;
			case "40331" : stn_name = "둔철산";				break;
			case "40979" : stn_name = "춘천 금병산";		break;
			case "40985" : stn_name = "횡성 태기산";		break;
			case "40988" : stn_name = "영월 백운산";		break;
			case "40991" : stn_name = "사자산";				break;
			case "40995" : stn_name = "정선 꽃밭덩이산";	break;
			case "40998" : stn_name = "정선 기추목이";		break;
			case "40999" : stn_name = "갈고개";				break;
		}

		return stn_name;
	}




	function setSubLocation(selectValue)
	{
		const selValue = selectValue.value;
		const selTxt = selectValue.options[selectValue.selectedIndex].text;
		
		// 서브 지점 리스트 삭제(초기화).
		$('#subLocation').empty();

		// 지점 멀티 선택 되게
		let checkbox = "";
		if (selValue === "ALL" || selValue === "EVLALL")
		{
			checkbox += "<input type='checkbox' class='checkbox_stn' name='STATION' value='" + selValue + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' checked>" + selTxt;
		}
		else
		{
			checkbox += "<input type='checkbox' id='loc_ave' class='checkbox_stn' name='STATION' value='" + selValue + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' >" + selTxt + " 전체<br>";
			checkbox += "<input type='checkbox' id='loc_mean' class='checkbox_stn' name='STATION' value='mean#" + selValue + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' >" + selTxt + " 평균<br>";
			
			let splitValue = selValue.split("#");
			for (let sp=0; sp<splitValue.length; sp++)
			{
				let l_id = splitValue[sp];
				let l_txt = get_station_name( splitValue[sp] );
				if (sp == 0)
				{
					checkbox += "<input type='checkbox' id='loc_each' class='checkbox_stn' name='STATION' value='" + l_id + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' checked>" + l_txt + "<br>";
				}
				else
				{
					checkbox += "<input type='checkbox' id='loc_each' class='checkbox_stn' name='STATION' value='" + l_id + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' >" + l_txt + "<br>";
				}
			}
		}
		$('#subLocation').append(checkbox);
		// 지점 멀티 선택 되게
	}



// 유사도 오류로 인해 잠시 사용
	// function setSubLocationSimilarity(selectValue) {
	// 	console.log("here================");
	// 	const selValue = selectValue.value;
	// 	const selTxt = selectValue.options[selectValue.selectedIndex].text;
		
	// 	// 서브 지점 리스트 삭제(초기화).
	// 	$('#subLocation').empty();

	// 	// 기존 지점 멀티 선택 안되게 default (주석 풀기)
	// 	let option = "";
	// 	if (selValue === "ALL" || selValue === "247ALL")
	// 	{
	// 		option += "<option value='" + selValue + "' selected='selected'>&#128440; " + selTxt + "</option>";
	// 	}
	// 	else
	// 	{
	// 		option += "<option value='" + selValue + "' >&#128440; " + selTxt + " 전체</option>";
	// 		option += "<option value='mean#" + selValue + "' >&#128440; " + selTxt + " 평균</option>";
			
	// 		const splitValue = selValue.split("#");
	// 		for (let sp=0; sp<splitValue.length; sp++)
	// 		{
	// 			let l_id = splitValue[sp];
	// 			let l_txt = get_station_name(splitValue[sp]);
	// 			if (sp == 0)
	// 			{
	// 				option += "<option value='" + l_id + "' selected>&#128440; " + l_txt + "</option>";
	// 			}
	// 			else
	// 			{
	// 				option += "<option value='" + l_id + "' >&#128440; " + l_txt + "</option>";
	// 			}
	// 		}
	// 	}
	// 	$('#subLocation').append(option);
	// 	// 기존 지점 멀티 선택 안되게 default (주석 풀기)
	// }



	function setSubLocationSSPS(selectValue)
	{
		let selValue = selectValue.value;
		let selTxt = selectValue.options[selectValue.selectedIndex].text;
		
		// 서브 지점 리스트 삭제(초기화).
		$('#subLocation').empty();

		// 지점 멀티 선택 되게
		let checkbox = "";
		if (selValue === "ALL")
		{
			checkbox += "<input type='checkbox' class='checkbox_stn' name='STATION' value='" + selValue + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' checked>" + selTxt;
		}
		else
		{
			let splitValue = selValue.split("#");
			for (let sp=0; sp<splitValue.length; sp++)
			{
				let l_id = splitValue[sp];
				let l_txt = get_station_name(splitValue[sp]);
				
				if (sp == 0)
				{
					checkbox += "<input type='checkbox' id='loc_each' class='checkbox_stn' name='STATION' value='" + l_id + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' checked>" + l_txt + "<br>";
				}
				else
				{
					checkbox += "<input type='checkbox' id='loc_each' class='checkbox_stn' name='STATION' value='" + l_id + "' onclick='checkStation(this.name, this.value, this.id); getDataArray();' >" + l_txt + "<br>";
				}
			}
		}
		$('#subLocation').append(checkbox);
		// 지점 멀티 선택 되게
	}



	function checkStation(obj_name, obj_value, obj_id)
	{
		// assets/js/vrfy_js/common_func.js : 체크가 모두 풀리는 것을 방지.
		checkNoneSelectBox(obj_name, obj_value);

		const chk_box_obj = $("input[name=" + obj_name + "]");
		const chk_total_num = chk_box_obj.length;
		const pos = obj_value.indexOf("#");

		// 전체 또는 평균 선택 시
		if (obj_id === "loc_ave" || obj_id === "loc_mean")
		{
			// 권역평균 선택 시
			if (obj_id === "loc_mean")
			{
				chk_box_obj.prop("checked", false);
				$("input[id=" + obj_id + "]").prop("checked", true);
				
			}
			// 권역 전체 선택 시
			else
			{
				if ($("input[id=loc_ave]").is(":checked"))
				{
					chk_box_obj.prop("checked", true);
					$("input[id=loc_mean]").prop("checked", false);
				}
				else
				{
					chk_box_obj.prop("checked", false);
					chk_box_obj.eq(2).prop("checked", true);
				}
			}

		}
		// 지역 개별 선택 시
		else if (obj_id === "loc_each")
		{
			// 권역평균 체크 시 - 해제
			if ($("input[id=loc_mean]").is(":checked"))
			{
				$("input[id=loc_mean]").prop("checked", false);
			}
			else
			{
				// ave & mean 
				const each_total_num = chk_total_num -2;
				const each_num = $("input[id=loc_each]:checked").length;
				if (each_total_num == each_num)
				{
					$("input[id=loc_ave]").prop("checked", true);
				}
				else
				{
					$("input[id=loc_ave]").prop("checked", false);
				}
			}
		}
	}


	// 지점선택 체크박스의 체크 된 ID 값 반환.
	// 예측 성능 비교표에서 사용
	function getCheckedStationID()
	{
		let check_stn = new Array();
		$('input[name="STATION"]:checked').each(function () {
			const st_id = $(this).attr('id');
			// 표준검증지점 또는 전체지점
			if (!st_id)
			{
				// 표준검증지점 (데이터에서 "vrfy_loc"으로 값 추출 시 AVE로 변환 필요)
				if ($(this).val() === "EVLALL")
				{
					// function get_station_name(stnid) 에서 네임 추출 시 사용
					check_stn.push("AVEEVL");
				}
				// 전체지점
				else
				{
					// function get_station_name(stnid) 에서 네임 추출 시 사용
					check_stn.push("AVE");
				}
			}
			// 각 지점
			else if (st_id === "loc_each")
			{
				check_stn.push($(this).val());
			}
			// 권역 평균
			else if (st_id === "loc_mean")
			{
				check_stn.push("mean");
			}
		});

		return check_stn;
	}





