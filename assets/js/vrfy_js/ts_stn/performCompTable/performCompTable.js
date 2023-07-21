    // function showPerformComparisonTable(ajax_url, data, var_select, model_sel, vrfy_idx) {
    function showPerformComparisonTable(ajax_url, data, var_select, model_sel, vrfy_idx, init_hour) {

// console.log('ajax_url', ajax_url);
// console.log('data', data);
// console.log('var_select', var_select);
// console.log('model_sel', model_sel);
// console.log('vrfy_idx', vrfy_idx);
// console.log('init_hour', init_hour);

        // 검증지수 체크박스 index 값(순서)으로 vrfy_title(전역변수)의 해당 이름을 가져오기 위함.
        let vrfy_idx_arr = new Array();
    	$("input[name=VRFY_INDEX]").each(function(index, item) {
            if(item.checked) {
                vrfy_idx_arr.push(index)
            }
    	});
// console.log('vrfy_idx', vrfy_idx_arr);

        let var_name = $(".eleSelBox option:selected").text();

        let month_arr = makeMonthArrayForData(data);
// console.log('month_arr', month_arr);

        let id_arr_for_data = makeIdForData(data);
// console.log('id_arr_for_data', id_arr_for_data);
// console.log(id_arr_for_data['GKIM_NPPM_00_BIAS']);

        let table_title00 = [
            "기존" + "<br>" + "(8~87h)",
            "연장" + "<br>" + "(88~111h)",
            "증감" + "<br>" + "(변화율)",
            "전체" + "<br>" + "(8~111h)"
        ];
        let table_title12 = [
            "기존" + "<br>" + "(8~75h)",
            "연장" + "<br>" + "(76~99h)",
            "증감" + "<br>" + "(변화율)",
            "전체" + "<br>" + "(8~99h)"
        ];

        let utc = makeUtcArr(init_hour);
// console.log('utc', utc);

        let modl_num = model_sel.length;
        let vrfy_num = vrfy_idx.length;
        let utc_num = utc.length;

        let table = makeHtmlTableHeader(ajax_url);

            for( let m=0; m<month_arr.length; m++) {

                for( let vr=0; vr<vrfy_num; vr++ ) {

                    for( let ut=0; ut<utc_num; ut++ ) {

                        table += "<table class='perform_tab'>";

                            table += "<thead><tr>";
                                // table += "<th rowspan='2'>" + var_name + "<br>" + vrfy_idx[vr] + "</th>";
                                // vrfy_txt & vrfy_title is global variable.                                                                                        
                                // table += "<th rowspan='2'>" + var_name + "<br>" + viewVrfyInfo( vrfy_idx[vr], vrfy_idx_arr[vr], vrfy_txt, vrfy_title, var_select ) + "</th>";
                                // table += "<th rowspan='2'>" + month_arr[m] + "<br><br>" + var_name + "<br>" + viewVrfyInfo( vrfy_idx[vr], vrfy_title[vrfy_idx_arr[vr]] ) + "</th>";
                                table += "<th rowspan='2'>" + selectedYYYYMMType(month_arr[m]) + "<br><br>" + var_name + "<br>" + viewVrfyInfo( vrfy_idx[vr], vrfy_title[vrfy_idx_arr[vr]] ) + "</th>";
                                table += "<th colspan='" + modl_num + "'>" + utc[ut] + "UTC</th>";
                                table += "</tr>";
                                
                                table += "<tr>";
                                for( let h=0; h<modl_num; h++ ) {
                                    let modl_split = model_sel[h].split("_");
                                    table += "<th>" + modl_split[0] + "</th>";
                                }
                                table += "</tr></thead>";
                            
                            
                            table += "<tbody>";

                            let table_title = new Array();
                                if( utc[ut] === "00" ) {
                                    table_title = table_title00;
                                } else if( utc[ut] === "12" ) {
                                    table_title = table_title12;
                                }
                            for( let r=0; r<table_title.length; r++ ) {
                                table += "<tr>";
                                for( let d=0; d<modl_num; d++ ) {
                                        if( d == 0 ) {
                                            table += "<td>" + table_title[r] + "</td>";
                                        }

                                        let data_id = model_sel[d] + "_" + utc[ut] + "_" + vrfy_idx[vr] + "_" + month_arr[m];

                                        if( r == 2 ) {
                                            // table += "<td>" + ( ( id_arr_for_data[data_id] ) ? id_arr_for_data[data_id][r+1] : "" ) ;
                                            // // table += "<br>(" + ( ( id_arr_for_data[data_id] ) ? id_arr_for_data[data_id][r+2] : "" ) + "%)</td>";
                                            // table += "<br>" + ( ( id_arr_for_data[data_id] ) ? id_arr_for_data[data_id][r+2] : "" ) + "%</td>";
                                            table += "<td>" + ( ( id_arr_for_data[data_id] ) ? changeVariable( id_arr_for_data[data_id][r+1] ) : "" ) ;

                                            if( vrfy_idx[vr] === "BIAS" && var_select != "PTY" ) {
                                                table += "<br>"+ "</td>";
                                            } else {
                                                table += "<br>" + ( ( id_arr_for_data[data_id] ) ? changePercentage( id_arr_for_data[data_id][r+2] ) : "" ) + "</td>";
                                            }

                                        } else if( r == table_title.length-1) {
                                            table += "<td>" + ( ( id_arr_for_data[data_id] ) ? changeVariable( id_arr_for_data[data_id][r+2] ) : "" ) + "</td>";
                                        } else {
                                            table += "<td>" + ( ( id_arr_for_data[data_id] ) ? changeVariable( id_arr_for_data[data_id][r+1] ) : "" ) + "</td>";
                                        }
                                    }
                                table += "</tr>";
                            }
                            table += "</tbody>";

                        table += "</table></body>";
                    }
                    table += "<div style='height:10px; width:100%; float:left;'></div>";

                }

            }
    
        // let window_size = (modl_num * utc.length)*10 + 300;
        let window_width_size = ( ((modl_num*2) * 50) + 300 ) * utc_num;
        let window_height_size = 400 * vrfy_num;
        if( window_height_size > 1000 ) {
            window_height_size = 1000;
        }
        let newWindow = window.open("", "_blank", "width=" + window_width_size + "px,height=" + window_height_size + "px");
        newWindow.document.write(table);

    }

    function makeIdForData(data) {

        let file_url = new Array();
        for( let i=0; i<data.length; i++ ) {

            let data_arr = data[i]['data'];
            for( let j=0; j<data_arr.length; j++ ) {

                let month = data_arr[j]['month'];
                let modl = data_arr[j]['model'];
                let utc = data_arr[j]['utc'].replace("UTC", "");
                    let vrfy_split = data_arr[j]['vrfy_loc'].split("_");
                let vrfy = vrfy_split[0];
                let fname = data_arr[j]['tableFileName'];
                let table_data = data_arr[j]['tableData'];

                // let data_id = modl + "_" + utc + "_" + vrfy;
                let data_id = modl + "_" + utc + "_" + vrfy + "_" + month;
                file_url[data_id] = table_data;
            }
        }

        return file_url;
    }



    function makeMonthArrayForData(data) {

        let month_arr = new Array();
        let data_arr = data[0]['data'];
            for( let j=0; j<data_arr.length; j++ ) {
                month_arr.push(data_arr[j]['month']);
            }

        // 중복제거.
        const filter = new Set(month_arr);
        const unique_month = [...filter];

        return unique_month;
    }



    function changeVariable(str) {
        if( str === "-999.0" ) {
            return str = "-";
        } else if( str === "-0.0" ) {
            return str = "0.0";
        } else {
            return str;
        }
    }
    function changePercentage(str) {
        if( str === "-999.0" || str === "-999" ) {
            return str = "-";
        } else if( str === "-0.0" ) {
            return str = "(0.0%)";
        } else {
            return "(" + str + "%)";
        }
    }


    function viewVrfyInfo( each_vrfy_idx, each_vrfy_title ) {
        if( each_vrfy_idx === "MAEE" ) {
            return "MAE";
        } else {
            let pos = each_vrfy_title.indexOf("(");
            if( pos < 0 ) {
                return each_vrfy_title;
            } else {
                let split_title = each_vrfy_title.split("(");
                return "(" + split_title[1] + "<br>" + split_title[0];
            }
        }
    }


    function makeUtcArr(init_hour) {

        let utc_length = init_hour.length;

        let utc = new Array();
        if( utc_length < 2 ) {
            let utc_split = init_hour[0].split("#");
            utc = [utc_split[0]];
        } else {
            utc = ["00", "12"];
        }

        return utc;
    }



    function makeHtmlTableHeader(ajax_url) {

        let html = "<!DOCTYPE html> \
                    <html lang='ko'> \
                    <head> \
                        <meta charset='utf-8'> \
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'> \
                        <title>예측 성능 비교표</title> \
                        <link href='" + ajax_url + "assets/css/style-edit-custom.css' rel='stylesheet'> \
                    </head> \
                    <body> ";

        return html;
    }



    function selectedYYYYMMType(yyyymm) {
     
        let res_yyyymm = "";

        let selectedType = $('#data_period option:selected').val();
        if( selectedType === "SEASON" ) {
            let sel_year = $('#select_season_date option:selected').val();
            let sel_season = $('#select_season_season option:selected').text();
            res_yyyymm = sel_year + " " + sel_season;
            
        } else if ( selectedType === "BANGJAE" ) {
            let sel_year = $('#select_bangjae_date option:selected').val();
            let sel_season = $('#select_bangjae_season option:selected').text();
            res_yyyymm = sel_year + " " + sel_season + "<br>방재기간";
            
        } else if ( selectedType === "ALLMONTH" ) {
            res_yyyymm = "전체기간";
        } else {
            res_yyyymm = yyyymm;
        }

        return res_yyyymm;
    }