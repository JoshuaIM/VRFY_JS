function makeCSVfile() {

    let csv = [];

console.log('glob_data', glob_data);
console.log('def_fr', def_forecast_range);

    glob_data.forEach(arr => {
        
        const ymInfo = arr['month']['ymInfo'];
        const modl_split = arr['model'].split("_");
        const model = modl_split[0];

        let vrfy_name_split = arr['vrfy_loc'].split("_");
        let stn_id = vrfy_name_split[1];
        let stn_name = "";
        if( stn_id === "AVE" ) {
            const sel_loc = $("select[name=LOCATION]").val();
            if( sel_loc[0] === "247ALL" ) {
                stn_id = "AVE247";
                // assets/js/vrfy_js/get_station_name.js
                let stn = get_station_name( stn_id );

                stn_split = stn.split("(");
                stn_name = stn_split[0];
            } else {
                // assets/js/vrfy_js/get_station_name.js
                stn_name = get_station_name( stn_id );
            } 
        } else {
            // assets/js/vrfy_js/get_station_name.js
            stn_name = get_station_name( stn_id );
        }

        let vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vrfy_name_split[0]);

        let header = ymInfo + " " + model + " [ " + vrfy_name + "_" + stn_name + " ] " + arr['utc'] + "UTC";
        csv.push(header);

        let table_head = "요소,";

        for( let d=0; d<arr['data'].length; d++ ) {

            let csv_fc = "";

            const f_fc =  arr['data'][d]['fHeadUtc'];
            const var_select = arr['data'][d]['var'];

            if( d === 0 ) {
                csv_fc += table_head;
                if( (glob_data.length == 1 && var_select == "TMX") || (glob_data.length == 1 && var_select == "TMN") ) {
                    for(let u=0; u<glob_data[0]['data'][0]['fHeadUtc'].length; u++) {
                        csv_fc += glob_data[0]['data'][0]['fHeadUtc'][u] + ",";
                    }
                } else {
                    for(let u=0; u<def_forecast_range.length; u++) {
                        csv_fc += def_forecast_range[u] + ",";
                    }
                }

                csv_fc += "AVE";
                csv.push(csv_fc);
            }
            
            let csv_data = "";

            csv_data += var_select + ",";
            

            const data = arr['data'][d]['data'];
            let tm_num = 0;
            for(let fc=0; fc<def_forecast_range.length; fc++) {


                if( def_forecast_range.length == f_fc.length ) {
                    if( f_fc == null ) {
                        csv_data += "  ,";
                    } else {
                        csv_data += data[fc] + ",";
                    }

                // 요소가 TMX, TMN 일 경우 : forecast time 개수가 다르다.
                } else {
                    let is_find = false;
                    let find_data = "";
                    for(let tm=tm_num; tm<f_fc.length; tm++) {
                        if( def_forecast_range[fc] == f_fc[tm] ) {
                            // 0 도 null 로 인식함.
                            if( data[tm] || data[tm] == 0 ) {
                                if( tm != data.length-1 ) {
                                    // find_data = data[tm] + ",";
                                    find_data = data[tm];
                                } else {
                                    find_data = " ,";
                                }
                            } else {
                                find_data = " ,";
                            }
                            tm_num++;
                            is_find = true;
                            break;
                        } 
                    }
    
                    if( glob_data.length != 1 ) {
                        if( is_find ) {
                            csv_data += find_data + ",";
                        } else {
                            csv_data += " ,";
                        }
                    } else {
                        csv_data += find_data + ",";
                    }

                }

            }


            csv_data += data[data.length -1] + ",";


            csv.push(csv_data);

            

            // table_data = arr['data'][d]['var'] + "," ;
            // table_data += arr['data'][d]['data'];
            // csv.push(table_data);




            // if( d === 0 ) {
            //     table_head += arr['data'][d]['fHeadUtc'] + ",";
            //     table_head += "AVE";
            //     csv.push(table_head);
            // }
            // table_data = arr['data'][d]['var'] + "," ;
            // table_data += arr['data'][d]['data'];
            // csv.push(table_data);
        }
            csv.push("\n");
    });

    const today = new Date();
    
    const Y = today.getFullYear();
    const M = ('0' + (today.getMonth() +1)).slice(-2);
    const D = ('0' + today.getDate()).slice(-2);
    const h = ('0' + today.getHours()).slice(-2);
    const m = ('0' + today.getMinutes()).slice(-2);
    const s = ('0' + today.getSeconds()).slice(-2);

    const filename = "VRFY_" + Y + "-" + M + "-" + D + "_" +  h + "_" + m + "_" + s + ".csv";
console.log('csv', csv);

//     const test = fcstDataUtilizeTableUseId( def_forecast_range, glob_data, var_select, dataFontClass, each_utc, "all" );
// console.log('test', test);

    // 한글 처리를 위한 BOM 추가
    const BOM = '\uFEFF' ;

    csv = BOM + csv.join('\n');

    let csvFile = new Blob([csv], {type: 'text/csv'});

    downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    document.body.appendChild(downloadLink);

    downloadLink.click();
}


