function makeFcstDataTable( forecast_arr, data_arr, var_select, dataFontClass, each_utc, table_id ) {
    
    selCont = "";

    //---- 집계표 테이블 시작
    selCont += "<table class='fcstTable' id='" + table_id + "'>";

    selCont += fcstDataTableUseId( forecast_arr, data_arr, var_select, dataFontClass, each_utc, "all" );

    selCont += "</table>";    						
    //---- 집계표 테이블 끝


    return selCont;
}



function fcstDataTableUseId( forecast_arr, data_arr, var_select, dataFontClass, each_utc, range_val ) {

    selCont = "";

    selCont += "<tr class='tb_head'>";
    selCont += "<td class='td_lg'>년월</td><td class='td_lg'>UTC</td><td class='td_lg'>모델 - 기법</td><td class='td_lg'>자료수</td>";

        // 예측시간 헤더 표출
        for(var u=0; u<forecast_arr.length; u++) {
            selCont += "<td class='td_sm'>" + forecast_arr[u] + "</td>"
        }
        selCont += "<td class='td_sm'>AVE</td></tr>";

        // 예측시간 데이터 표출
        for(var mm=0; mm<data_arr.length; mm++) {

            let month = data_arr[mm]['month'];
            let model = data_arr[mm]['model'];
            let total_data_num = data_arr[mm]['fDataNum'];
            // let data = data_arr[mm]['data'];

            let data = new Array();
            if( range_val === "all" ) {
                data = data_arr[mm]['data'];
            } else {
                data = cutForecastRange(each_utc, range_val, data_arr[mm]['data']);

                let calculate_ave = calculateAveValue(data , var_select);
                data.push(calculate_ave);
            }
            
            selCont += "<tr class='tb_data'>";
            selCont += "<td>" + month + "</td>" +
                        "<td>" + each_utc + "</td>" + 
                        "<td>" + model + "</td>" + 
                        "<td>" + total_data_num + "</td>";
            
            for(var d=0; d<data.length; d++) {
                if( data[d] == null ) {
                        selCont += "<td> </td>";
                } else {
                    // 강수확률은 값의 /100 적용.
                    if( var_select == "POP" ) {
                        selCont += "<td class='" + dataFontClass + "'>" + (data[d]/100).toFixed(3) + "</td>";
                    } else {
                        selCont += "<td class='" + dataFontClass + "'>" + data[d] + "</td>";
                        
                        // // TODO: 2021-06-01 소수점 둘째자리 반올림
                        // selCont += "<td class='" + dataFontClass + "'>" + resp[vl]['data'][mm]['data'][d].toFixed(1) + "</td>";
                    }
                }
            }

            selCont += "</tr>";
        } // End of "for(var mm=0; mm<resp[vl]['data'].length; mm++)" 

    return selCont;
}



function calculateAveValue( array, var_select ) {

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