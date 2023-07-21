// function makeEmptyHighcharts( isZoom, peri, cht_name, yaxis_title, data_utc_arr, unitSymb ) {
// function makeEmptyHighcharts( isZoom, peri, cht_name, yaxis_title, data_utc_arr, unitSymb, location ) {
function makeEmptyHighcharts( isZoom, peri, cht_name, yaxis_title, data_utc_arr, unitSymb, location, variable ) {

    // 활용도(CORR, COSS 만 적용) : 최대*최저기온 및 3시간 적설의 경우 중간 빈 값들이 들어가는 부분을 제외하고 연결해주기 위해(connectNulls) 옵션 설정 예외처리.
    let cht_type_split = cht_name.split("_");
    let cht_type = cht_type_split[0];

    // let set_yaxis_max_txt = "";
    // let split_cht = cht_name.split("_");
    // if( split_cht[0] === "CORR" || split_cht[0] === "COSS" ) {
    //     set_yaxis_max_txt = "max: 1";
    // } else {
    //     set_yaxis_max_txt = "";
    // }


    // 북한의 경우 3시간 자료이므로 선 그래프가 없음, 고로 전체그래프보기 시 값 표출이 없으므로, 북한 지역만 포인터 지우지 않고 표기.
    let new_isZoom = false;
    // 북한 지역을 선별하기 위한 변수.
    let location_id = 0;
        // AVE(전체지점), mean(권역평균) 외 해당.
        if( location[0].length == 5 ) {
            location_id = parseInt(location[0]);
        } else {
            // AVE-전체지점 제외, mean-평균 의 경우 해당
            if( location[0] != "AVE" ) {
                location_id = parseInt(location[1]);
            }
        }
        // 북한지역 id 값 범위를 제외한 다른 지역은 포인터 삭제 후 선그래프로 표출 - 북한지역은 포인터 삭제 안함.
        if( !( (47002 < location_id && location_id < 47076) || variable == "SN1" ) ) {
            new_isZoom = isZoom;
        }

    let x_title_txt = "";
    // let legend_align = "";
    // let marker_enabled = isZoom;
    let marker_enabled = new_isZoom;
    switch (peri) {
        case "FCST", "BANGJAE" :
            x_title_txt = 'time(H)';
            // legend_align = "left";
            break; 
            case "MONTH" : 
            x_title_txt = 'Date (YYYYMM)';
            // legend_align = "center";
            break;
    }

    // 하이차트 표출. (데이터가 없는 껍데기)
    $('#' + cht_name + "_div").highcharts({

        chart: {
            defaultSeriesType: 'line',
            renderTo: 'container'
        },                                	
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        yAxis: {
            title: {
                text: yaxis_title
            },
            // max: 1
            // set_yaxis_max_txt
        },
        xAxis: {
            title: {
                text: x_title_txt
            },
            categories: data_utc_arr
        },
        legend: {
            layout: 'horizontal',
            // align: legend_align,
            align: "left",
            verticalAlign: 'bottom'
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                marker: {
                	enabled: !marker_enabled
                },
                // connectNulls: true
                connectNulls: ( cht_type === "CORR" || cht_type === "COSS" ) ? true : false
            }
        },
        tooltip: {
            valueSuffix: " " + unitSymb,
            crosshairs: true,                              		
            shared: true
        },
        series: [{}],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal',
                    }
                }
            }]
        },
        exporting: {
            enabled: false
        }

    });

}