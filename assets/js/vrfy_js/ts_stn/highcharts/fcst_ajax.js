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
    
                if ( type === "GEMD" )
                {
                    def_forecast_range = resp[0]['data'][0]['fHeadUtc'];
                }
                else
                {
                    def_forecast_range = new Array();
                }

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
                if ( (type === "SHRT" && peri != "MONTH") || (type === "MEDM" && peri != "MONTH") )
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

                        const dataInfo = resp[vl]['data'];
                        const file_name = dataInfo[0]['fileName'];
                        const each_utc = resp[vl]['utc'];

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
                            selCont += "<h5><b class='chartName'>" + var_select + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]  " + each_utc + "UTC</b></h5>";
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
                            selCont += "<h5><b class='chartName'>" + var_select + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ] " + each_utc + "UTC " + addText + "</b></h5>";
                        }
                        else if ( peri === "ALLMONTH" )
                        {
                            const startDate = $("#allmonth_startD").val();
                            const endDate = $("#allmonth_endD").val();
                            const addText = " - 전체기간 (" + startDate + "~" + endDate + ")";

                            selCont += "<h5><b class='chartName'>" + var_select + "</b> <b class='vrfyName'>[ " + vrfy_name + "_" + stn_name + " ]  " + each_utc + "UTC " + addText + "</b></h5>";
                        }
                        
                        selCont += "</div>";
                        // 검증지수 * 지점 개수 만큼 차트DIV Header 끝.
    

                        //---- 집계표 표출
                            let forecast_info = dataInfo[0]['fHeadUtc'];

                            const table_id = each_utc + "UTC_" + vrfy_loc + "_table";

                            if ( peri === "MONTH" )
                            {
                                // assets/js/vrfy_js/highcharts/common/data_table.js
                                selCont += makeMonthDataTable(dataInfo, var_select, dataFontClass, each_utc, table_id);
                            }
                            else
                            {
                                if ( type === "GEMD" )
                                {
                                    // assets/js/vrfy_js/highcharts/common/data_table_similarity.js
                                    selCont += makeFcstDataTableUtilize(forecast_info, dataInfo, var_select, dataFontClass, each_utc, table_id);
                                }
                                else
                                {
                                    // assets/js/vrfy_js/highcharts/common/data_table.js
                                    selCont += makeFcstDataTable(forecast_info, dataInfo, var_select, dataFontClass, each_utc, table_id);
                                }
                            }

                        //---- 시계열 차트 시작
                            selCont += "<div id='" +vrfy_loc + "_" + each_utc + "_div' class='cht_div'></div>";
                            
                            selCont += "</div></div>";
                            
                            // 검증지수 * 지점 개수 만큼 차트DIV 생성 끝.
                            $('#contValue').append(selCont);
    
                            let vrfy_id = "";
                            let isZoom = false;
                                if ( peri === "MONTH" )
                                {
                                    isZoom = false;
                                    const sp_vl = vrfy_loc.split("_");
                                    vrfy_id = sp_vl[0];
                                }
                                else
                                {
                                    // assets/js/vrfy_js/zoom/zoom.js
                                    isZoom = checkZoomGraph();
                                    setZoomGraph(isZoom, data_head, location);
                                    vrfy_id = vftc[0];
                                }
    
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
                            let cht_name = vrfy_loc + "_" + each_utc;

                            let data_utc_arr = "";
                            if ( peri === "MONTH" )
                            {
                                data_utc_arr = resp[vl]['data'][0]['mon_range'];
                            }
                            else 
                            {
                                data_utc_arr = dataInfo[0]['fHeadUtc'];
                            }

                            makeEmptyHighcharts( isZoom, peri, cht_name, yaxis_title, data_utc_arr, unitSymb, location, var_select );
                            // 빈 하이차트 만들기 - assets/js/vrfy_js/highcharts/highcharts_frame.js
    
                            // 모델X초기시각X기간 값을 하이차트에 Append 하기 위해 객체 생성.
                            let chart = $('#' + vrfy_loc + "_" + each_utc + "_div").highcharts();
            
                            if ( peri === "MONTH" )
                            {
                                // 차트 업데이트 : 월별은 차트 형식이 달라서 구분.
                                chart = set_month_timeseries_data(chart, dataInfo , var_select);
                            }
                            else
                            {
                                // 차트 업데이트
                                chart = set_timeseries_data(chart, dataInfo , var_select);
                            }

    
                        } // End of "if( dataInfo.length == 0 )"
                        
                    } // End of "for(var vl=0; vl<resp.length; vl++)"
                        
                }, // End of "success : function(resp)"
                error : function(error) 
                {
                    alert("data error");
                    console.log(error);
                }
            })
            
        }