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
	
	
// 검증지수 선택 박스 만들기.
	function makeVrfySelect(vrfy_data, vrfy_txt, pType, dateType) {
		
		$('#vrfySelect').empty();
		
		var v = $("select[name=VAR]").val();
		
		var selBox = "";
		
		selBox += "<b>검증지수: </b>"; 
		
		if( pType == "shrt_ts_grd" ) {
			var js_func = " onclick='oneCheckbox(this);'";
		} else {
			if( dateType == "month" ) {
				var js_func = " onclick='getDataArray();'";
			} else {
				var js_func = " ";
			}
		}
		
		for( var h=0; h<vrfy_data.length; h++ ) {
			if( v == "T3H" || v == "TMX" || v == "TMN" || v == "REH" || v == "T1H" ) {
				if( vrfy_data[h] == "CNT1" ) {
					selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>오차빈도( </text>";
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked> " + vrfy_txt[h];
				} else if( vrfy_data[h] == "CNT2" ) {
					selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
				} else if( vrfy_data[h] == "CNT3" ) {
					selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h] + "<text>)</text>";
				} else {
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + "> " + vrfy_txt[h];
				}
			} else if( v == "POP" ) {
				if( vrfy_data[h] == "BRS0" ) {
					selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>BS( </text>";
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked> " + vrfy_txt[h];
				} else if( vrfy_data[h] == "BRSS" ) {
					selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h] + "<text>)</text>";
				} else {
					selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
				}
			} else if( v == "RN3" || v == "RN6" || v == "R12" || v == "SN3" || v == "SN6" || v == "S12" || v == "RN1" || v == "SN1" ) {
				if( vrfy_data[h] == "BIS1" ) {
					selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>BIAS( </text>";
					// selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked> " + vrfy_txt[h];
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
				} else if( vrfy_data[h] == "BIS4" || vrfy_data[h] == "CSI4" || vrfy_data[h] == "ETS4" ) {
					selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h] + "<text>)</text>";
				} else if( vrfy_data[h] == "CSI1" ) {
					selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>CSI( </text>";
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked > " + vrfy_txt[h];
				} else if( vrfy_data[h] == "ETS1" ) {
					selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>ETS( </text>";
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
				} else {
					selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
				}
			} else {
				if( h == 0 ) {
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked> " + vrfy_txt[h];
				} else {
					selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
				}
			}
		}
		
		$('#vrfySelect').append(selBox);
		
	}
	

// 검증지수의 한글 타이틀 네임 가져오는 함수
	function get_vrfy_title(vrfy_data, vrfy_title, vrfy_id) {
		
		var vrfy_name = "";

		var idx = vrfy_data.indexOf(vrfy_id);
		vrfy_name = vrfy_title[idx];
		
		return vrfy_name;
	}
	
