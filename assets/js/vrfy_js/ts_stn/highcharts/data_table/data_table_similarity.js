function makeFcstDataTableUtilize( forecast_arr, data_arr, var_select, dataFontClass, each_utc, table_id ) {
 
    let grph_type = "";
    let separator_table_id = table_id.split("_");
    if( separator_table_id[1] === "CORR" || separator_table_id[1] === "COSS" ) {
        grph_type = "simi";
    } else {
        grph_type = "accu";
    }

    selCont = "";

    //---- 집계표 테이블 시작
    selCont += "<table class='fcstTable' id='" + table_id + "'>";

    selCont += fcstDataUtilizeTableUseId( grph_type, forecast_arr, data_arr, var_select, dataFontClass, each_utc, "all" );

    selCont += "</table>";    						
    //---- 집계표 테이블 끝


    return selCont;
}



function fcstDataUtilizeTableUseId( grph_type, forecast_arr, data_arr, var_select, dataFontClass, each_utc, range_val ) {

    selCont = "";

    selCont += "<tr class='tb_head'>";

    // 유사도의 경우
    if( grph_type === "simi" ) {
        selCont += "<td class='td_lg'>요소</td>";
    // 정확도의 경우
    } else {
        selCont += "<td class='td_lg'>년월</td><td class='td_lg'>UTC</td><td class='td_lg'>모델 - 기법</td><td class='td_lg'>자료수</td>";
    }

    // 예측시간 헤더 표출
    // 최고기온 또는 최저기온만 표출 될 경우
        if( (data_arr.length == 1 && data_arr[0]['var'] == "TMX") || (data_arr.length == 1 && data_arr[0]['var'] == "TMN") ) {
            for(var u=0; u<data_arr[0]['fHeadUtc'].length; u++) {
                selCont += "<td class='td_sm'>" + data_arr[0]['fHeadUtc'][u] + "</td>"
            }
            // 일반 헤더 표출
        } else {
            for(var u=0; u<forecast_arr.length; u++) {
                selCont += "<td class='td_sm'>" + forecast_arr[u] + "</td>"
            }
        }
        selCont += "<td class='td_sm'>AVE</td></tr>";


        // 예측시간 데이터 표출
        for(var mm=0; mm<data_arr.length; mm++) {

            const f_fc =  data_arr[mm]['fHeadUtc'];
            selCont += "<tr class='tb_data'>";

            // 유사도의 경우
            if( grph_type === "simi" ) {
                const variable = data_arr[mm]['var'];
                selCont += "<td>" + variable + "</td>";
            // 정확도의 경우
            } else {
                let month = data_arr[mm]['month'];
                let model = data_arr[mm]['model'];
                let total_data_num = data_arr[mm]['fDataNum'];
                selCont += "<td>" + month + "</td>" +
                            "<td>" + each_utc + "</td>" + 
                            "<td>" + model + "</td>" + 
                            "<td>" + total_data_num + "</td>";
            }


            let data = new Array();
            if( range_val === "all" ) {
                data = data_arr[mm]['data'];
            } else {
                data = cutForecastRange(each_utc, range_val, data_arr[mm]['data']);

                let calculate_ave = calculateUtilizeAveValue(data , var_select);
                data.push(calculate_ave);
            }
            

            // 유사도 : TMX, TMN 요소의 경우 LOOP로 해당 forecast time을 찾아야 함.
            // 정확도 : GEMD 와 ECMWF 의 경우 LOOP로 해당 forecast time을 찾아야 함.
            let tm_num = 0;
            // 정확도 GEMD 를 제외한 모델 AVE 값을 계산하기 위함.
            let value_arr = new Array();
            for(let d=0; d<forecast_arr.length; d++) {
               
                // 유사도 : TMX, TMN, SN3 제외의 경우
                // 정확도 : GEMD 의 경우
                if( forecast_arr.length+1 == data.length ) {
                    if( data[d] == null ) {
                        selCont += "<td> </td>";
                    } else {
                        selCont += "<td class='" + dataFontClass + "'>" + data[d] + "</td>";
                    }

                // 요소가 TMX, TMN 일 경우 : forecast time 개수가 다르다.
                } else {
                    let is_find = false;
                    let find_data = "";
                    for(let tm=tm_num; tm<f_fc.length; tm++) {
                        if( forecast_arr[d] == f_fc[tm] ) {
                            // 0 도 null 로 인식함.
                            if( data[tm] || data[tm] == 0 ) {
                                if( tm != data.length-1 ) {
                                    find_data = "<td class='" + dataFontClass + "'>" + data[tm] + "</td>";
                                    // AVE 값 계산을 위해 해당 값 배열화.
                                    value_arr.push(data[tm]);
                                } else {
                                    find_data = "<td> </td>";
                                    // AVE 값 계산을 위해 해당 값 배열화.
                                    value_arr.push(null);
                                }
                            } else {
                                find_data = "<td> </td>";
                                // AVE 값 계산을 위해 해당 값 배열화.
                                value_arr.push(null);
                            }
                            tm_num++;
                            is_find = true;
                            break;
                        } 
                    }
    
                    if( data_arr.length != 1 ) {

                        if( is_find ) {
                            selCont += find_data;
                        } else {
                            selCont += "<td> </td>";
                        }
                        
                    } else {
                        selCont += find_data;
                    }

                }
            }

            if( grph_type === "simi" || forecast_arr.length+1 == data.length ) {
                // AVE값 표출. (forecast 배열 개수보다 데이터 배열이 1개 더 많음 >> AVE값)
                selCont += "<td class='" + dataFontClass + "'>" + data[data.length -1] + "</td>";
            } else {
                const average = (value_arr.reduce((a, b) => a + b, 0) / value_arr.length).toFixed(1);
                selCont += "<td class='" + dataFontClass + "'>" + checkAveViewValue(average) + "</td>";
            }

            selCont += "</tr>";
        } // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)" 

    return selCont;
}


// 소수점 아래 값이 0 이면 소수점 아래 삭제.
function checkAveViewValue(value) {
    const split_value = (String(value)).split(".");
    const point_value = parseInt(split_value[1]);

    if( point_value == 0 ) {
        return split_value[0];
    } else {
        return value;
    }
}



function calculateUtilizeAveValue( array, var_select ) {

    let num = array.length;
    let total = 0;
    for(let i=0; i<array.length; i++) {
        if(array[i] == null) {
            num = num -1;
        } else {
            total = total + parseFloat(array[i]);
        }
    }
    
    let ave = total/num;
    if( var_select === "POP" ) {
        ave = ave.toFixed(3);
    } else {
        ave = ave.toFixed(1);
    }
    
    return ave;
}