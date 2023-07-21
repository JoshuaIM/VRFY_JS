function makeFCselectBox(init_hour, var_select) {

    let str = "<div class='forecastSelectArea'>";
    
    // str += "<input name='FC_INDEX' value='0#129' type='checkbox' onclick='' checked> +6H ~ 135H";    
    str += "<input name='FC_INDEX' value='all' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' checked> +6H ~ 135H";    

        // if( var_select === "TMX" ) {
        //     str += "<input name='FC_INDEX' value='1#3|1#2' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +8H ~ +87H , (12UTC) : +8H ~ +75H";    
        //     str += "<input name='FC_INDEX' value='4#4|3#3' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +88H ~ +111H , (12UTC) : +76H ~ +99H";    
        //     str += "<input name='FC_INDEX' value='1#4|1#4' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +8H ~ +111H , (12UTC) : +8H ~ +99H";    
        // } else if( var_select === "TMN" ) {
        //     str += "<input name='FC_INDEX' value='1#3|1#3' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +8H ~ +87H , (12UTC) : +8H ~ +75H";    
        //     str += "<input name='FC_INDEX' value='4#4|4#4' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +88H ~ +111H , (12UTC) : +76H ~ +99H";    
        //     str += "<input name='FC_INDEX' value='1#4|1#4' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +8H ~ +111H , (12UTC) : +8H ~ +99H";    
        // } else {
        //     str += "<input name='FC_INDEX' value='2#81|2#69' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +8H ~ +87H , (12UTC) : +8H ~ +75H";    
        //     str += "<input name='FC_INDEX' value='82#105|70#93' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +88H ~ +111H , (12UTC) : +76H ~ +99H";    
        //     str += "<input name='FC_INDEX' value='2#105|2#93' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > (00UTC) : +8H ~ +111H , (12UTC) : +8H ~ +99H";    
        // }

        // 최고,최저기온 선택 시, 초기시각 멀티 선택 시 예외처리를 위해.
        str += viewSelectInfo(init_hour, var_select);
    str += "</div>";

    return str;
}


// 요소(최고,최저기온 예외처리), UTC 멀티 선택 시(예외) 변경된 예측기간 범위 선택 박스 생성.
function viewSelectInfo(init_hour, var_select) {

    let str = "";

    const str_first = "<input name='FC_INDEX' value='";
    const str_sec = "' type='checkbox' onclick='forecastSingleiewBoxAndDo(this)' > ";

    const UTC00 = ["+8H ~ +87H", "+88H ~ +111H", "+8H ~ +111H"];
    const UTC12 = ["+8H ~ +75H", "+76H ~ +99H", "+8H ~ +99H"];
    const forecast_selBox = {
        //  "TMX": ["1#3|1#2", "4#4|3#3", "1#4|1#4"],
        //  "TMN" : ["1#3|1#3", "4#4|4#4", "1#4|1#4"],
         "TMX": ["1#3|0#2", "4#4|3#3", "1#4|0#3"],
         "TMN" : ["1#3|0#2", "4#4|3#3", "1#4|0#3"],
         "SN3" : ["1#27|1#23", "28#35|24#31", "1#35|1#31"],
         "nomal" : ["2#81|2#69", "82#105|70#93", "2#105|2#93"]
    };


    let forecast_value = new Array();
    for( let key in forecast_selBox ) {
        if( var_select === key ) {
            forecast_value = forecast_selBox[key];
            break;
        }
        if ( var_select != "TMX" && var_select != "TMN" && var_select != "SN3" ) {
            forecast_value = forecast_selBox["nomal"];
            break;
        }
    }

    // select Box 작성.
    for( let i=0; i<3; i++ ) {
        // 00 + 12 UTC 멀티 선택.
        if( init_hour.length == 2 ) {
            str += str_first + forecast_value[i] + str_sec + "(00UTC) : " + UTC00[i] + " , (12UTC) : " + UTC12[i] + "\n";
        } else if ( init_hour.length == 1 ) {
            str += str_first + forecast_value[i] + str_sec + ( (init_hour[0] === "00#00")?UTC00[i]:UTC12[i] ) + "\n";
        }
    }

    return str;
}


// 예측기간 범위 선택박스 (중복 불가)
function forecastSingleiewBoxAndDo(func) {

    let chk_val = func.value;

    $("input[name=" + func.name + "]").each(function() {
        if( this.value === chk_val ) {
            this.checked = true;
        } else {
            this.checked = false;
        }
    });

    changeViewForecastRange(func);
}


function changeViewForecastRange(clickForecastRange) {

    let range_val = clickForecastRange.value;

    // 그래프 표출 개수 (지점X검증지수)
    // 글로벌 변수에 저장된 데이터 값으로 표출.
    for(var vl=0; vl<glob_data.length; vl++) {

        // 지점X검증지수 가 없을 경우 표출 안하고 넘어감.
        if( glob_data[vl]['data'].length == 0 ) {
            alert("데이터가 없습니다.");
            continue;

        // 지점X검증지수 표출.
        } else {

            const each_utc = glob_data[vl]['utc'];
            const dataInfo = glob_data[vl]['data'];
            const var_select = glob_data[vl]['var_name'];

            const forecast_info = (range_val === "all") ? dataInfo[0]['fHeadUtc'] : cutForecastRange(each_utc, range_val, dataInfo[0]['fHeadUtc']);

            let vrfy_loc = glob_data[vl]['vrfy_loc'];
                // let check_247 = $('#subLocation option:selected').val();
                let check_247 = $("input:checkbox[name=STATION]").val();
                if( check_247 === "247ALL" ) {
                    vrfy_loc = vrfy_loc + "247";
                }

        //---- 집계표 표출
            const table_id = each_utc + "UTC_" + vrfy_loc + "_table";
                $('#'+table_id).empty();

                let new_table = fcstDataTableUseId(forecast_info, dataInfo, var_select, dataFontClass, each_utc, range_val);
                $('#'+table_id).append(new_table);
                
        //---- 시계열 차트 시작    
            // 모델X초기시각X기간 값을 하이차트에 Append 하기 위해 객체 생성.
            let chart = $('#' + vrfy_loc + "_" + each_utc + "_div").highcharts();
        
            // 하나의 차트에 들어갈 데이터 라인의 수.
            let cht_line_num = glob_data[vl]['data'].length;

            if( cht_line_num == 1 ) {

                // 강수확률은 값의 /100 적용.
                if( var_select === "POP" ) {
                    // IE에서 .map() 함수 사용 못함.
                    var original_data = new Array();
                    var pop_d = dataInfo[0]['data'];
                        for(var x=0; x<pop_d.length; x++) {
                            if( pop_d[x] ) {
                                original_data.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                            } else {
                                original_data.push( pop_d[x] );
                            }
                        }
                } else {
                    var original_data = dataInfo[0]['data'];
                }
                
                // let chtdata = dataInfo[0]['data'].slice(utc00_start, utc00_end);
                // let chtdata = (range_val === "all") ? dataInfo[0]['data'] : cutForecastRange(each_utc, range_val, dataInfo[0]['data']);
                
                // let chtdata = (range_val === "all") ? original_data : cutForecastRange(each_utc, range_val, original_data);
                // let length = chtdata.length +1;

                let length = 0;
                let chtdata = new Array();
                if( range_val === "all" ) {
                    chtdata = original_data;
                    length = chtdata.length;
                } else {
                    chtdata = cutForecastRange(each_utc, range_val, original_data);
                    length = chtdata.length +1;
                }

                let chtdata2 = chtdata.filter((number, index) => {
                    return index < length-1;
                });
                chtdata = chtdata2;
                                
                chart.series[0].setData( chtdata, true );
                // 카테고리 추가(00, 12 UTC 멀티 표출 때문). 2023-04-14
                chart.xAxis[0].update({ categories: forecast_info });
                chart.redraw();

            } else {
    
                for(var mm=0; mm<dataInfo.length; mm++) {

                    let data = dataInfo[mm]['data'];
                    let data_mon = dataInfo[mm]['month'];
                    let data_utc = dataInfo[mm]['utc'];
                    let data_modl = dataInfo[mm]['model'];
                    let modl_color = dataInfo[mm]['modl_color'];

                    // 강수확률은 값의 /100 적용.
                    if( var_select === "POP" ) {
                        var original_data = new Array();
                        var pop_d = data;
                            for(var x=0; x<pop_d.length; x++) {
                                if( pop_d[x] ) {
                                    chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                                } else {
                                    chtdata.push( pop_d[x] );
                                }
                            }
                    } else {
                        var original_data = data;
                    }
                    
                    // let chtdata = (range_val === "all") ? original_data : cutForecastRange(each_utc, range_val, original_data);

                    // let length = chtdata.length +1;

                    let length = 0;
                    let chtdata = new Array();
                    if( range_val === "all" ) {
                        chtdata = original_data;
                        length = chtdata.length;
                    } else {
                        chtdata = cutForecastRange(each_utc, range_val, original_data);
                        length = chtdata.length +1;
                    }

                    let chtdata2 = chtdata.filter((number, index) => {
                        return index < length-1;
                    });
                    chtdata = chtdata2;

                    const lineName = data_mon + "_" + data_utc + "_" + data_modl;

                    // chart.series = new Highcharts.Series
                    if( mm == 0 ) {
                        chart.series[0].name = lineName;
                        // 모델 컬러 추가. 2023-01-11
                        chart.series[0].color = modl_color;
                        chart.series[0].setData(chtdata, false);
                        chart.xAxis[0].update({ categories: forecast_info });
                    } else if( mm == (dataInfo.length -1) ) {
                        // 모델 컬러 추가. 2023-01-11
                        // chart.xAxis[0].update({ categories: data_utc_arr });
                        // chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, true);
                        chart.series[mm].update({data: chtdata});
                    } else {
                        // 모델 컬러 추가. 2023-01-11
                        // chart.xAxis[0].update({ categories: data_utc_arr });
                        // chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, false);
                        chart.series[mm].update({data: chtdata});
                    }
                        
                }
            }

            // 단기 기준 "+88H ~ +111H" 클릭 시 그래프 축소
            if( !checkZoomGraph() ) {
                if( var_select != "TMX" && var_select != "TMN" && var_select != "SN3") {
                    if( range_val === "82#105|70#93" ) {
                        chart.setSize(1200, 300);
                        $(".col-lg-1h").css("width", "1200");
                    } else {
                        chart.setSize(5000, 300);
                        $(".col-lg-1h").css("width", "5000");
                    }
                // 3시간 적설의 +88H ~ +111H 의 범위가 다름.
                } else if( var_select === "SN3" ) {
                    if( range_val === "28#35|24#31" ) {
                        chart.setSize(1200, 300);
                        $(".col-lg-3h").css("width", "1200");
                    } else {
                        chart.setSize(3000, 300);
                        $(".col-lg-3h").css("width", "3000");
                    }
                }
            }

        } // End of "if( resp[vl]['data'].length == 0 )"
            
    } // End of "for(var vl=0; vl<resp.length; vl++)"

}



function cutForecastRange(utc, range_val, array) {

    const split_range_val = range_val.split("|");

    let chk_utc = "";
    if( utc === "00" ) {
        chk_utc = split_range_val[0];
    } else {
        chk_utc = split_range_val[1];
    }

    const split_utc = chk_utc.split("#");
    const utc_start = split_utc[0];
    const utc_end = split_utc[1];

    const resp = array.slice(parseInt(utc_start), parseInt(utc_end)+1);

    return resp;
}