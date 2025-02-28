function set_timeseries_data(get_chart, dataInfo, var_select)
{
    let chart = get_chart;

    // 하나의 차트에 들어갈 데이터 라인의 수.
    const cht_line_num = dataInfo.length;
    const cht_arr_num = is_month_ts_view(dataInfo);

    // TODO : 데이터가 하나도 없을 시. ( 위에 "if( dataInfo.length == 0 )" 를 추가했으므로 필요없을 수 있음 )
    if (cht_line_num == 0)
    {
        chart.series[0].name= "No Data";
        chart.series[0].setData([], true);
    }
    // 데이터가 하나 일 경우 series의 name이 제대로 기입이 안되므로 억지로 집어넣어준다.
    else if (cht_line_num == 1 )
    {
        let chtdata = new Array();
        // 강수확률은 값의 /100 적용.
        if (var_select === "POP" )
        {
            chtdata = get_pop_chart_data(dataInfo[0]['data'])
            // chtdata = dataInfo[0]['data'];
        }
        else
        {
            chtdata = dataInfo[0]['data'];
        }
        
        // // 집계표에 사용되는 AVE값까지 포함되어 있으므로 시계열 표출 시 삭제 함. 
        // chtdata.pop();
        // chtdata.splice(chtdata.length - 1);
        // 원본 배열 값이 같이 삭제되므로 아래 필터함수 사용해서 사용 2023-01-12
        let length = chtdata.length;
        let chtdata2 = chtdata.filter((number, index) => {
            return index < length-1;
        });
        chtdata = chtdata2;
        
        const chart_line_info = get_chart_line_info(dataInfo[0]);
        const lineName = chart_line_info['line_name'];
        const modl_color = chart_line_info['modl_color'];
        
        chart.series[0].setData( chtdata, true );
        // 카테고리 추가(00, 12 UTC 멀티 표출 때문). 2023-04-14
        // chart.xAxis[0].update({ categories: data_utc_arr });
        // 모델 컬러 추가. 2023-01-11
        chart.series[0].update({name: lineName, color: modl_color}, false);
        chart.redraw();

    }
    else
    {
        for (let mm=0; mm<dataInfo.length; mm++)
        {
            let chtdata = new Array();
            // 강수확률은 값의 /100 적용.
            if ( var_select === "POP" )
            {
                chtdata = get_pop_chart_data(dataInfo[mm]['data'])
            }
            else
            {
                if (sub_type === "ACCURACY")
                {
                    const target_arr_num = cht_arr_num[mm];
                    const state_arr_num = cht_arr_num[0];
                    if (target_arr_num ==  state_arr_num)
                    {
                        chtdata = dataInfo[mm]['data'];
                    }
                    else
                    {
                        const state_fc_head = dataInfo[0]['fHeadUtc'];
                        chtdata = get_accuracy_data(state_fc_head, dataInfo[mm]);
                    }
                }
                else if (sub_type === "SIMILARITY")
                {
                    chtdata = get_similarity_data(dataInfo[mm]);
                }
                else
                {
                    chtdata = dataInfo[mm]['data'];
                }
            }
            
            // 집계표에 사용되는 AVE값까지 포함되어 있으므로 시계열 표출 시 삭제 함. 
            // chtdata.pop();
            // chtdata.splice(chtdata.length - 1);
            // chtdata.splice(chtdata.length - 1);
            // 원본 배열 값이 같이 삭제되므로 아래 필터함수 사용해서 사용 2023-01-12
            let length = chtdata.length;
            let chtdata2 = chtdata.filter((number, index) => {
                return index < length-1;
            });
            chtdata = chtdata2;

            const chart_line_info = get_chart_line_info(dataInfo[mm]);
            const lineName = chart_line_info['line_name'];
            const modl_color = chart_line_info['modl_color'];
            if ( mm == 0 )
            {
                chart.series[0].name = lineName;
                // 모델 컬러 추가. 2023-01-11
                chart.series[0].color = modl_color;
                chart.series[0].setData(chtdata, false);
                // chart.xAxis[0].update({ categories: data_utc_arr });
            }
            else if ( mm == (dataInfo.length -1) )
            {
                // 모델 컬러 추가. 2023-01-11
                // chart.xAxis[0].update({ categories: data_utc_arr });
                chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, true);
            }
            else
            {
                // 모델 컬러 추가. 2023-01-11
                // chart.xAxis[0].update({ categories: data_utc_arr });
                chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, false);
            }
        }
    }
    return chart;
}

function get_chart_line_info(data_info)
{
    let info_arr = new Array();
    if (sub_type === "SIMILARITY")
    {
        info_arr = {
            line_name : data_info['var'],
            modl_color : data_info['variable_color']
        };
    }
    else
    {
        info_arr = {
            // line_name : data_info['month'] + "_" + data_info['utc'] + "_" + data_info['model'],
            line_name : data_info['month'] + "_" + data_info['utc'] + "_" + getSSPSLocationAveName(data_info['model']),
            modl_color : data_info['modl_color']
        };
    }
    return info_arr;
}






function set_month_timeseries_data(get_chart, dataInfo, var_select)
{

    let chart = get_chart

    // 하나의 차트에 들어갈 데이터 라인의 수.
    var cht_line_num = dataInfo.length;
    var cht_arr_num = new Array();
    
        // 그래픽당 모든 배열값이 null일 경우 표출 안하기 위함.
        for(var li=0; li<dataInfo.length; li++) {
            var cht_line_chk = 0;
            for(var li_ck=0; li_ck<dataInfo[li]['data'].length; li_ck++) {
                if( dataInfo[li]['data'][li_ck] != null ) {
                    cht_line_chk = cht_line_chk +1;
                }
            }
            // 그래프 당 값 개수를 파악하여 표출을 할지 안할지 파악.
            cht_arr_num.push(cht_line_chk);			
            // 배열 값 모두 null이면 표출 그래프 개수에서 -1
            if( cht_line_chk < 1 ) {
                cht_line_num = cht_line_num-1;
            }
        }
        
    if( cht_line_num == 0 ) {
        chart.series[0].name= "No Data";
        chart.series[0].setData([], true);
    } else if( cht_line_num == 1 ) {

        // 강수확률은 값의 /100 적용.
        if( var_select == "POP" ) {
            //var chtdata = dataInfo[0]['data'].map( x=>parseFloat( (x/100).toFixed(3) ));
            var chtdata = new Array();
            var pop_d = dataInfo[0]['data'];
                for(var x=0; x<pop_d.length; x++) {
                    chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                }
        } else {
            var chtdata = dataInfo[0]['data'];
        }
        
        // var lineName = dataInfo[0]['utcInfo'] + "_" + dataInfo[0]['model'];
        var lineName = dataInfo[0]['utcInfo'] + "_" + getSSPSLocationAveName(dataInfo[0]['model']);
        // 모델 컬러 추가. 2023-01-11
        let modl_color = dataInfo[0]['modl_color'];
        
        chart.series[0].setData(chtdata, true);
        // 모델 컬러 추가. 2023-01-11
        chart.series[0].update({name: lineName, color: modl_color}, false);
        chart.redraw();
        
    } else {

        for(var mm=0; mm<dataInfo.length; mm++) {

            if( cht_arr_num[mm] < 1 ) {
                continue;
            } else {
                // 강수확률은 값의 /100 적용.
                if( var_select == "POP" ) {
                    var chtdata = new Array();
                    var pop_d = dataInfo[mm]['data'];
                        for(var x=0; x<pop_d.length; x++) {
                            chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                        }
                } else {
                    var chtdata = dataInfo[mm]['data'];
                }
    
                // var lineName = dataInfo[mm]['utcInfo'] + "_" + dataInfo[mm]['model'];
                var lineName = dataInfo[mm]['utcInfo'] + "_" + getSSPSLocationAveName(dataInfo[mm]['model']);
                // 모델 컬러 추가. 2023-01-11
                let modl_color = dataInfo[mm]['modl_color'];
                
                if( mm == 0 ) {
                    chart.series[0].name= lineName;
                    // 모델 컬러 추가. 2023-01-11
                    chart.series[0].color= modl_color;
                    chart.series[0].setData(chtdata, false);
                } else if( mm == cht_line_num -1 ) {
                    // 모델 컬러 추가. 2023-01-11
                    chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, true);
                } else {
                    // 모델 컬러 추가. 2023-01-11
                    chart.addSeries({data: chtdata, name: lineName, marker: {symbol: 'circle'}, color: modl_color}, false);
                }
            } // End of "if( cht_arr_num[mm] < 1 )"
            
        } // End of "for(var mm=0; mm<dataInfo.length; mm++)"

    } // End of "if( cht_line_num == 0 )"

    return chart
}



function get_accuracy_data(state_fc_head, data)
{
    const target_fc_head = data['fHeadUtc'];
    const target_data = data['data'];
    let chtdata = new Array();
    let tm_num = 0;
    for (let i=0; i<state_fc_head.length; i++)
    {
        for (let d=tm_num; d<target_fc_head.length; d++)
        {
            if ( state_fc_head[i] == target_fc_head[d] )
            {
                chtdata.push(target_data[d]);
                tm_num = d;
                break;
            }
        }
    }
    // AVE 값 대신 넣어둠.
    // 데이터 추출 시 마지막 값은 AVE 값이므로.
    chtdata.push(null);
    return chtdata;
}



function get_similarity_data(data)
{
    let chtdata = new Array();

    // loop 횟수 줄이기. ( 찾은 위치부터 loop 돌리기 )
    let target_fc_num = 0; 
    const v = data['var'];
    const f_h = data['fHeadUtc'];
    if( v === "TMX" || v === "TMN" || v === "SN3" )
    {
        let find_fc = false;
        // def_forecast_range : global
        for (let fc=0; fc<def_forecast_range.length; fc++)
        {
            for (let tg=target_fc_num; tg<f_h.length; tg++ )
            {
                if (def_forecast_range[fc] === f_h[tg])
                {
                    chtdata.push( data['data'][tg] );
                    find_fc = true;
                    target_fc_num = tg;
                    break;
                } else {
                    find_fc = false;
                }
            }
            if (!find_fc)
            {
                chtdata.push(null);
            }
        }
    }
    else
    {
        chtdata = data['data'];
    }

    return chtdata;
}


// 강수확률은 값의 /100 적용.
function get_pop_chart_data(data)
{
    let chtdata = new Array();

    for (let x=0; x<data.length; x++)
    {
        if (data[x])
        {
            chtdata.push( parseFloat( (data[x]/100).toFixed(3) ) );
        }
        else
        {
            chtdata.push( data[x] );
        }
    }

    return chtdata;
}