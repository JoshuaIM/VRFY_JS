function makeCSVfile() {

    // let filename = "VRFY_";
    // let filename = "VRFY.csv";

    let csv = [];

    glob_data.forEach(arr => {
        
        // filename += arr['var_name'] + "_";

        let vrfy_name_split = arr['vrfy_loc'].split("_");
        let stn_name = get_station_name( vrfy_name_split[1] );
        let vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vrfy_name_split[0]);

        let header = arr['var_name'] + " [ " + vrfy_name + "_" + stn_name + " ]";
        csv.push(header);

        let table_head = "년월,UTC,모델 - 기법,자료수,";
        for( let d=0; d<arr['data'].length; d++ ) {
            if( d === 0 ) {
                table_head += arr['data'][d]['fHeadUtc'] + ",";
                table_head += "AVE";
                csv.push(table_head);
            }
            table_data = arr['data'][d]['month'] + "," + arr['data'][d]['utc'] + "," + arr['data'][d]['model'] + "," + arr['data'][d]['fDataNum'] + ",";
            table_data += arr['data'][d]['data'];
            csv.push(table_data);
        }
        csv.push("\n");
    });

    // let today = new Date();
    // filename += today + ".csv";


    const today = new Date();
    
    const Y = today.getFullYear();
    const M = ('0' + (today.getMonth() +1)).slice(-2);
    const D = ('0' + today.getDate()).slice(-2);
    const h = ('0' + today.getHours()).slice(-2);
    const m = ('0' + today.getMinutes()).slice(-2);
    const s = ('0' + today.getSeconds()).slice(-2);

    let filename = "VRFY_" + Y + "-" + M + "-" + D + "_" +  h + "_" + m + "_" + s + ".csv";


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