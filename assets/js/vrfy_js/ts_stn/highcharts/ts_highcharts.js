function set_timeseries_data(get_chart, dataInfo, var_select)
{
    let chart = get_chart;

    // 하나의 차트에 들어갈 데이터 라인의 수.
    const cht_line_num = dataInfo.length;

    const cht_arr_num = is_month_ts_view(dataInfo);

    // TODO : 데이터가 하나도 없을 시. ( 위에 "if( dataInfo.length == 0 )" 를 추가했으므로 필요없을 수 있음 )
    if ( cht_line_num == 0 )
    {
        chart.series[0].name= "No Data";
        chart.series[0].setData([], true);
    }
    // 데이터가 하나 일 경우 series의 name이 제대로 기입이 안되므로 억지로 집어넣어준다.
    else if ( cht_line_num == 1 )
    {
        let chtdata = new Array();
        // 강수확률은 값의 /100 적용.
        if( var_select == "POP" ) {
            // IE에서 .map() 함수 사용 못함.
            const pop_d = dataInfo[0]['data'];
                for(let x=0; x<pop_d.length; x++) {
                    if( pop_d[x] ) {
                        chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                    } else {
                        chtdata.push( pop_d[x] );
                    }
                }
        } else {
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
        
        const lineName = dataInfo[0]['month'] + "_" + dataInfo[0]['utc'] + "_" + dataInfo[0]['model'];
        // 모델 컬러 추가. 2023-01-11
        const modl_color = dataInfo[0]['modl_color'];
        
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
                let pop_d = dataInfo[mm]['data'];
                    for (let x=0; x<pop_d.length; x++)
                    {
                        if( pop_d[x] ) {
                            chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                        } else {
                            chtdata.push( pop_d[x] );
                        }
                    }
            }
            else
            {
                chtdata = dataInfo[mm]['data'];
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

            const lineName = dataInfo[mm]['month'] + "_" + dataInfo[mm]['utc'] + "_" + dataInfo[mm]['model'];
            // 모델 컬러 추가. 2023-01-11
            let modl_color = dataInfo[mm]['modl_color'];
            
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
        
        var lineName = dataInfo[0]['utcInfo'] + "_" + dataInfo[0]['model'];
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
    
                var lineName = dataInfo[mm]['utcInfo'] + "_" + dataInfo[mm]['model'];
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