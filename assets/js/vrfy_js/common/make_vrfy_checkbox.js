// 검증지수 선택 박스 만들기.
function makeVrfySelect(vrfy_data, vrfy_txt, data_type) {

    $('#vrfySelect').empty();
    
    var v = $("select[name=VAR]").val();
    
    var selBox = "";
    
    let split_pType = data_type.split("_");
    if( split_pType[0] === "gemd" && split_pType[2] === "similarity" ) {
        selBox += "<b>활용도: </b>"; 
    } else {
        selBox += "<b>검증지수: </b>"; 
    }

    
    if( data_type == "shrt_ts_grd" ) {
        var js_func = " onclick='oneCheckbox(this);'";
    } else {
        if( dateType == "month" ) {
            // var js_func = " onclick='getDataArray();'";
            var js_func = " onclick='checkNoneSelectBox(this.name, this.value); getDataArray();'";
        } else {
            var js_func = " ";
        }
    }
    
    let rn1_num = 0;
    for( var h=0; h<vrfy_data.length; h++ ) {
        if( v == "T3H" || v == "TMX" || v == "TMN" || v == "REH" || v == "T1H" ) {
            if( vrfy_data[h] == "CNT1" ) {
                // selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>오차빈도( </text>";
                selBox += "<text style='margin-top:10px; margin-left:20px; margin-right:-20px;'>오차빈도( </text>";
                // selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked> " + vrfy_txt[h];
                selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
            } else if( vrfy_data[h] == "CNT2" ) {
                selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
            } else if( vrfy_data[h] == "CNT3" ) {
                selBox += "<input style='margin-left:5px;' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h] + "<text>)</text>";
            } else {
                // selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + "> " + vrfy_txt[h];
                let chking = (vrfy_data[h] == "BIAS") ? " checked " : "" ;
                selBox += "<input name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + chking + "> " + vrfy_txt[h];
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
    
        // } else if( (data_type == "shrt_ts_stn" && v == "RN1") || (data_type == "shrt_map_stn" && v == "RN1") ) {
        // } else if( (data_type == "shrt_ts_stn" && v == "RN1") || (data_type == "shrt_map_stn" && v == "RN1") || (data_type == "gemd_ts_accuracy" && v == "RN1") || (data_type == "gemd_map_utilize" && v == "RN1") ) {
        } else if( v === "RN1" || v === "RN3" ) {
            let vrfy_name = vrfy_data[h].substring(0,3);
            let vrfy_id = vrfy_data[h].substring(3,4);
                if( vrfy_id === "1" ) {
                    rn1_num = rn1_num +1;
                        if( rn1_num % 2 === 1 && rn1_num != 1 ) {
                            selBox += "<br>";
                            selBox += "<text style='margin:10px -20px 0px 73px; font-size:13px;'>" + ( (vrfy_name==="BIS")?"FBI":vrfy_name ) + "( </text>";
                        } else {
                            selBox += "<text style='margin:10px -20px 0px 5px; font-size:13px;'>" + ( (vrfy_name==="BIS")?"FBI":vrfy_name ) + "( </text>";
                        }
                }
                    let vrfy_txt_id = parseInt(vrfy_id) -1;
                    selBox += "<input style='width:14px; height:14px; " + ( (vrfy_id === "1")?"":"margin-left: 10px;" ) + "' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + ( (h == 0)?" checked":"" ) + " > <text style='font-size:13px;'>" +  vrfy_txt[vrfy_txt_id] + "</text>";
                if( parseInt(vrfy_id) == vrfy_txt.length ) {
                    selBox += "<text>)</text> ";
                }		
    
        // } else if( (data_type == "shrt_ts_stn" && v == "SN3") || (data_type == "shrt_map_stn" && v == "SN3") || (data_type == "gemd_ts_accuracy" && v == "SN3") || (data_type == "gemd_map_utilize" && v == "SN3") ) {
        } else if( v === "SN3" ) {
            let vrfy_name = vrfy_data[h].substring(0,3);
            let vrfy_id = vrfy_data[h].substring(3,4);
                if( vrfy_id === "1" ) {
                    rn1_num = rn1_num +1;
                        if( rn1_num % 2 == 1 && rn1_num != 1 ) {
                            selBox += "<br>";
                            selBox += "<text style='margin:10px -20px 0px 73px; font-size:13px;'>" + ( (vrfy_name==="BIS")?"FBI":vrfy_name ) + "( </text>";
                        } else {
                            selBox += "<text style='margin:10px -20px 0px 5px; font-size:13px;'>" + ( (vrfy_name==="BIS")?"FBI":vrfy_name ) + "( </text>";
                        }
                }
                    let vrfy_txt_id = parseInt(vrfy_id) -1;
                    selBox += "<input style='width:14px; height:14px; " + ( (vrfy_id === "1")?"":"margin-left: 10px;" ) + "' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + ( (h == 0)?" checked":"" ) + " > <text style='font-size:13px;'>" +  vrfy_txt[vrfy_txt_id] + "</text>";
                if( parseInt(vrfy_id) == vrfy_txt.length ) {
                    selBox += "<text>)</text> ";
                }		
    
        // } else if( v == "RN3" || v == "RN6" || v == "R12" || v == "SN3" || v == "SN6" || v == "S12" || ( data_type == "ssps_shrt_ts_stn" && v == "RN1" ) || ( data_type == "ssps_shrt_ts_stn" && v == "SN1" ) || ( data_type == "ssps_shrt_map_stn" && v == "RN1" ) || ( data_type == "ssps_shrt_map_stn" && v == "SN1" ) ) {
        } else if( v == "RN3" || v == "RN6" || v == "R12" || v == "SN3" || v == "SN6" || v == "S12" ) {
            if( vrfy_data[h] == "BIS1" ) {
                // selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>BIAS( </text>";
                selBox += "<text style='margin-top:10px; margin-left:10px; margin-right:-20px;'>FBI( </text>";
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

        } else if( data_type == "shrt_ts_stn" && v == "SN1" || data_type == "shrt_map_stn" && v == "SN1"  ) {

            let vrfy_name = vrfy_data[h].substring(0,3);
            let vrfy_id = vrfy_data[h].substring(3,4);
                if( vrfy_id === "1" ) {
                    rn1_num = rn1_num +1;
                        if( rn1_num % 2 == 1 && rn1_num != 1 ) {
                            selBox += "<br>";
                            selBox += "<text style='margin:10px -20px 0px 73px; font-size:13px;'>" + ( (vrfy_name==="BIS")?"FBI":vrfy_name ) + "( </text>";
                        } else {
                            selBox += "<text style='margin:10px -20px 0px 5px; font-size:13px;'>" + ( (vrfy_name==="BIS")?"FBI":vrfy_name ) + "( </text>";
                        }
                }
                    let vrfy_txt_id = parseInt(vrfy_id) -1;
                    selBox += "<input style='width:14px; height:14px; " + ( (vrfy_id === "1")?"":"margin-left: 10px;" ) + "' name='VRFY_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + ( (h == 0)?" checked":"" ) + " > <text style='font-size:13px;'>" +  vrfy_txt[vrfy_txt_id] + "</text>";
                if( parseInt(vrfy_id) == vrfy_txt.length ) {
                    selBox += "<text>)</text> ";
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




// 예보 편집 공간분포 검증지수 선택 박스 만들기.
function makeVrfySelectUtilizeMap(vrfy_data, vrfy_txt, pType, dateType) {
			
    $('#utilizeSelect').empty();
    
    // var v = $("select[name=VAR]").val();
    
    var selBox = "";
    
    // 활용도 표출
    selBox += "<b>활용도: </b>"; 
        if( dateType == "month" ) {
            var js_func = " onclick='setUtilize(this.value); getDataArray();'";
        } else {
            var js_func = " ";
        }

    for( var h=0; h<vrfy_data.length; h++ ) {
        if( h == 0 ) {
            selBox += "<input name='UTILIZE_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " checked> " + vrfy_txt[h];
        } else {
            selBox += "<input name='UTILIZE_INDEX' value='" + vrfy_data[h] + "' type='checkbox' " + js_func + " > " + vrfy_txt[h];
        }
    }
    
    $('#utilizeSelect').append(selBox);
    
}


// 검증지수의 한글 타이틀 네임 가져오는 함수
	function get_vrfy_title(vrfy_data, vrfy_title, vrfy_id) {
		
		var vrfy_name = "";

		var idx = vrfy_data.indexOf(vrfy_id);
		vrfy_name = vrfy_title[idx];
		
		return vrfy_name;
	}