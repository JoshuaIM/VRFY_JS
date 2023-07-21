///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  예측기간별 자료  Ajax 이용 그래픽 표출 메서드.
    function call_ajax_ts_data(ajax_url_fcst, set_data) {

        $.ajax({
            type : "POST",
            data : set_data,
            dataType: "json",
            url : ajax_url_fcst, 
            // 변수에 저장하기 위함.
            async: false,
            success : function(resp)
            {
console.log("resp", resp);
console.log('data', set_data);

                // csv 내려받기 기능을 위해 데이터 값 광역변수에 저장.
                glob_data = resp;
    
                const data_head = set_data["data_head"];
                const init_hour = set_data["init_hour"];
                const var_select = set_data["var_select"];
                const model_sel = set_data["model_sel"];
                const vrfy_idx = set_data["vrfy_idx"];
                const location = set_data["location"];
                const peri = set_data["peri"];

                // 전체기간의 경우 OverWrite.
                let start_init = ( peri === "BANGJAE" || peri === "SEASON" ) ? set_data["range_date"] : set_data["start_init"];
                let end_init = ( peri === "BANGJAE" || peri === "SEASON" ) ? set_data["range_date"] : set_data["end_init"];


                // 그래프 표출 영역 초기화.
                $('#contValue').empty();
                        
                // 월별 제외 : 예측기간 범위 선택 표출 기능과 예측성능비교표 표출
                if ( peri != "MONTH" )
                {
                    if ( data_head === "DFS_SHRT_STN_" || data_head === "247_SHRT_STN_" )
                    {
                        // 예측기간 범위 선택 표출
                        // assets/js/vrfy_js/highcharts/common/forecast_selectBox.js
                        const forecast_select_box = makeFCselectBox(init_hour, var_select);
                        $('#contValue').append(forecast_select_box);
    
                        $('.viewPerformTable').empty();
    
                        perform_var = var_select;
                        perform_modl = model_sel;
                        perform_vrfy = vrfy_idx;
                        perform_utc = init_hour;
    
                        // 예측성능비교표
                        const func = "showPerformComparisonTable(site_url, glob_data, perform_var, perform_modl, perform_vrfy, perform_utc)";
    
                        const make_btn = "<button class='performTableBtn' type='button' onclick='" + func + "'>예측 성능 비교표 보기</button>";
                        $('.viewPerformTable').append(make_btn);
                    }
                }


                // 그래프 표출 개수 (지점X검증지수)
                for (let vl=0; vl<resp.length; vl++)
                {
                    // 지점X검증지수 가 없을 경우 표출 안하고 넘어감.
                    if ( !is_ts_view(resp[vl]["data"], peri) )
                    {
                        alert("데이터가 없습니다.");
                        continue;
                    }
                    // 지점X검증지수 표출.
                    else
                    {

                        const file_name = resp[vl]['data'][0]['fileName'];
                        if ( peri === "BANGJAE" )
                        {
                            // 방재기간 정보 표출
                            setBangjaeInitDate(file_name);
                        }
                        else if ( peri === "SEASON" )
                        {
                            // 계절별 정보 표출
                            setSeasonInitDate(file_name);
                        }
                        else if ( peri === "ALLMONTH" )
                        {
                            // 전체기간 정보 표출
                            setAllmonthInitDate(file_name);

                            // OverWrite
                            start_init = "202305";
                            end_init = "202305";
                        }

                        let selCont = "";
    

                        // assets/js/vrfy_js/shrt_change_3to1_func.js
                        let selContNdataFont = setShrt3H1HDisplay(start_init, end_init, var_select, peri); 
                        
                        let arrContNFont = selContNdataFont.split("||");
    
                        selCont += arrContNFont[0];
                        // 글로벌 변수로 변경(예측기간 변경 선택 기능 때문). 2023-04-14
                        dataFontClass = arrContNFont[1];
                        
                        selCont += "<div class='white-panel' >";
                        selCont += "<div class='white-header'>";

                        let vrfy_loc = resp[vl]['vrfy_loc'];
                        if ( data_head === "247_SHRT_STN_" )
                        {
                            vrfy_loc = vrfy_loc + "247";
                        }

                        const vftc = vrfy_loc.split("_");
                        let stn_name = get_station_name( vftc[1] );
                        if( data_head === "247_SHRT_STN_" ) {
                            stn_split = stn_name.split("(");
                            stn_name = stn_split[0];
                        }
                        const vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vftc[0]);
                                
                        if ( peri === "FCST" )
                        {
                            selCont += "<h5><b class='chartName'>" + var_select + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]  " + resp[vl]['utc'] + "UTC</b></h5>";
                        }
                        else if ( peri === "BANGJAE" || peri === "SEASON" )
                        {
                            const peri_low = peri.toLowerCase();

                            // 방재기간, 전체기간, 계절별 표출 시에만 사용.
                            const selectYear = $("#select_" + peri_low + "_date option:selected").val();
                            const selectSeason = $("#select_" + peri_low + "_season option:selected").text();
                            const startDate = $("#" + peri_low + "_startD").val();
                            const endDate = $("#" + peri_low + "_endD").val();
                            const addText = " - " + selectYear + " " + selectSeason + " 방재기간(" + startDate + "~" + endDate + ")"
                            selCont += "<h5><b class='chartName'>" + var_select + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ] " + resp[vl]['utc'] + "UTC " + addText + "</b></h5>";
                        }
                        else if ( peri === "ALLMONTH" )
                        {
                            const startDate = $("#allmonth_startD").val();
                            const endDate = $("#allmonth_endD").val();
                            const addText = " - 전체기간 (" + startDate + "~" + endDate + ")";

                            selCont += "<h5><b class='chartName'>" + var_select + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]  " + resp[vl]['utc'] + "UTC " + addText + "</b></h5>";
                        }

                        
                        selCont += "</div>";
                        // 검증지수 * 지점 개수 만큼 차트DIV Header 끝.
    

                        //---- 집계표 표출
                            const each_utc = resp[vl]['utc'];

                            let forecast_info = resp[vl]['data'][0]['fHeadUtc'];
                            let dataInfo = resp[vl]['data'];

                            const table_id = each_utc + "UTC_" + vrfy_loc + "_table";
                            // assets/js/vrfy_js/highcharts/common/data_table.js
                            selCont += makeFcstDataTable(forecast_info, dataInfo, var_select, dataFontClass, each_utc, table_id);

                        //---- 시계열 차트 시작
                            selCont += "<div id='" +vrfy_loc + "_" + resp[vl]['utc'] + "_div' class='cht_div'></div>";
                            
                            selCont += "</div></div>";
                            
                            // 검증지수 * 지점 개수 만큼 차트DIV 생성 끝.
                            $('#contValue').append(selCont);
    
                            // assets/js/vrfy_js/zoom/zoom.js
                            let isZoom = checkZoomGraph();
                            setZoomGraph(isZoom, data_head, location);
    
                            const vrfy_id = vftc[0];
    
                            const var_unit = get_grph_unit(var_select, vrfy_id);
                                const vUnit = var_unit.split("#");
                            const unitName = vUnit[0];
                            const unitSymb = vUnit[1];
    
                            // 그래픽 Y축 Title 정보.
                            let yaxis_title = "";
                            if( unitSymb ) {
                                yaxis_title = unitName + " ( " + unitSymb + " )";
                            } else {
                                yaxis_title = unitName;
                            }
    
                            // 빈 하이차트 만들기 - assets/js/vrfy_js/highcharts/highcharts_frame.js
                            let cht_name = vrfy_loc + "_" + resp[vl]['utc'];
                            let data_utc_arr = resp[vl]['data'][0]['fHeadUtc'];

                            makeEmptyHighcharts( isZoom, peri, cht_name, yaxis_title, data_utc_arr, unitSymb, location, var_select );
                            // 빈 하이차트 만들기 - assets/js/vrfy_js/highcharts/highcharts_frame.js
    
                            // 모델X초기시각X기간 값을 하이차트에 Append 하기 위해 객체 생성.
                            const chart = $('#' + vrfy_loc + "_" + resp[vl]['utc'] + "_div").highcharts();
            
                            // 하나의 차트에 들어갈 데이터 라인의 수.
                            const cht_line_num = resp[vl]['data'].length;
    
                            // TODO : 데이터가 하나도 없을 시. ( 위에 "if( resp[vl]['data'].length == 0 )" 를 추가했으므로 필요없을 수 있음 )
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
                                    const pop_d = resp[vl]['data'][0]['data'];
                                        for(let x=0; x<pop_d.length; x++) {
                                            if( pop_d[x] ) {
                                                chtdata.push( parseFloat( (pop_d[x]/100).toFixed(3) ) );
                                            } else {
                                                chtdata.push( pop_d[x] );
                                            }
                                        }
                                } else {
                                    chtdata = resp[vl]['data'][0]['data'];
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
                                
                                const lineName = resp[vl]['data'][0]['month'] + "_" + resp[vl]['data'][0]['utc'] + "_" + resp[vl]['data'][0]['model'];
                                // 모델 컬러 추가. 2023-01-11
                                const modl_color = resp[vl]['data'][0]['modl_color'];
                                
                                chart.series[0].setData( chtdata, true );
                                // 카테고리 추가(00, 12 UTC 멀티 표출 때문). 2023-04-14
                                // chart.xAxis[0].update({ categories: data_utc_arr });
                                // 모델 컬러 추가. 2023-01-11
                                chart.series[0].update({name: lineName, color: modl_color}, false);
                                chart.redraw();

                            }
                            else
                            {
                                for (let mm=0; mm<resp[vl]['data'].length; mm++)
                                {
                                    let chtdata = new Array();
                                    // 강수확률은 값의 /100 적용.
                                    if ( var_select === "POP" )
                                    {
                                        let pop_d = resp[vl]['data'][mm]['data'];
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
                                        chtdata = resp[vl]['data'][mm]['data'];
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

                                    const lineName = resp[vl]['data'][mm]['month'] + "_" + resp[vl]['data'][mm]['utc'] + "_" + resp[vl]['data'][mm]['model'];
                                    // 모델 컬러 추가. 2023-01-11
                                    let modl_color = resp[vl]['data'][mm]['modl_color'];
                                    
                                    if ( mm == 0 )
                                    {
                                        chart.series[0].name = lineName;
                                        // 모델 컬러 추가. 2023-01-11
                                        chart.series[0].color = modl_color;
                                        chart.series[0].setData(chtdata, false);
                                        // chart.xAxis[0].update({ categories: data_utc_arr });
                                    }
                                    else if ( mm == (resp[vl]['data'].length -1) )
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
    
                        } // End of "if( resp[vl]['data'].length == 0 )"
                        
                    } // End of "for(var vl=0; vl<resp.length; vl++)"
                        
                }, // End of "success : function(resp)"
                error : function(error) 
                {
                    alert("data error");
                    console.log(error);
                }
            })
            
        }