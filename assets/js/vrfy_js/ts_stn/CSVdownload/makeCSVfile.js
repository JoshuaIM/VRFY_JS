function makeCSVfile() {

    const var_select = $("select[name=VAR]").val();

    // 검증지수 명 배열화.
    let vrfyname_arr = new Array();
    let vrfyheader_arr = new Array();
    $(".vrfyName").each(function(){
        let text = $(this).text();
        vrfyheader_arr.push(text);
        vrfyname_arr.push(text.replace(/\s/gi, ""));
    });

    let diff_case = false;
    const fc_idx = $("input:checkbox[name='FC_INDEX']:checked").val();  //string.
    const utc_idx = get_init_hour_option(); //array.
    if (fc_idx != null && fc_idx != "all" && utc_idx.length > 1 )
    {
        const fc_text = $("input:checkbox[name='FC_INDEX']:checked").val(); //string.
        diff_case = true;
    }

    let csv = new Array();

    // 시계열 헤더 수집 (검증지수와 지점 정보 수집을 위함.) - 테이블 id 개수와 동일하다고 
    let vrfy_arr = new Array();
    let stn_arr = new Array();
    for (let i=0; i<vrfyname_arr.length; i++)
    {
        const split_fir = vrfyname_arr[i].split("_");
        const vrfy_split = split_fir[0].split("[");
            vrfy_arr.push(vrfy_split[1]);
        const stn_split = split_fir[1].split("]");
            stn_arr.push(stn_split[0]);

        if (i === 0)
        {
            const temp_txt = vrfyheader_arr[i].split("-");
            csv.push(temp_txt[1]);
        }
    }

    // 검증자료 값 테이블 id 수집
    let item_id = new Array();
    $(".fcstTable").each(function(){
        item_id.push($(this).attr("id"));
    });

    const table_data = get_all_table_data(item_id, var_select, vrfy_arr, stn_arr);

    // 00UTC 12UTC forecast 표출이 같을 경우.
    if ( !diff_case )
    {
        for (let i=0; i<table_data.length; i++)
        {
            for (let e=0; e<table_data[i].length; e++)
            {
                const row_data = table_data[i][e];
                if (i === 0 && e === 0)
                {
                    csv.push( "," + row_data.join(","));
                }
                else if ( e !== 0 )
                {
                    const utc = row_data[4];
                    if (sub_type === "ACCURACY" && utc === "12")
                    {
                        get_splice_data(row_data, row_data.length-1, 12);
                        csv.push( "," + row_data.join(","));
                    }
                    else
                    {
                        csv.push( "," + row_data.join(","));
                    }
                }
            }
        }
    }
    else
    {
        const select_utc_split = fc_idx.split("|");
        if (select_utc_split[0].substring(0,1) === select_utc_split[1].substring(0,1))
        {
            let def_arr_length = 0;
            for (let i=0; i<table_data.length; i++)
            {
                for (let e=0; e<table_data[i].length; e++)
                {
                    const row_data = table_data[i][e];
                    if (i === 0 && e === 0)
                    {
                        csv.push( "," + row_data.join(","));
                        def_arr_length = row_data.length;
                    }
                    else if ( e !== 0 )
                    {
                        const bin_num = def_arr_length - row_data.length;
                        get_splice_data(row_data, row_data.length-1, bin_num);
                        csv.push( "," + row_data.join(","));
                    }
                }
            }
        }
        else
        {
            const collect_forecast = get_forecast_array(table_data[0][0]);
            csv.push( "," + collect_forecast.join(","));

            // let def_arr_length = 0;
            for (let i=0; i<table_data.length; i++)
            {
                for (let e=0; e<table_data[i].length; e++)
                {
                    const row_data = table_data[i][e];
                    if (e != 0)
                    {
                        const utc = row_data[4];
                        if ( utc === "00" )
                        {
                            get_splice_data(row_data, 7, 12);
                            csv.push( "," + row_data.join(","));
                        }
                        else
                        {
                            get_splice_data(row_data, row_data.length-1, 12);
                            csv.push( "," + row_data.join(","));
                        }
                    }
                }
            }
        }
    }
// console.log('csv', csv);
    run_download_csv(csv);
}




function get_all_table_data(item_id, var_select, vrfy_arr, stn_arr)
{
    let table_data = new Array();
    for (let id=0; id<item_id.length; id++)
    {
        let each_table_data = new Array();
        const table = document.getElementById(item_id[id]);
        const head_elements = table.getElementsByClassName("tb_head");
        const tr_elements = table.getElementsByClassName("tb_data");

        each_table_data.push(extract_table_header(head_elements));
        for (let i=0; i<tr_elements.length; i++)
        {
            const td_elements = tr_elements[i].getElementsByTagName("td");
            const each_data = extract_table_data(td_elements, var_select, vrfy_arr[id], stn_arr[id])
            each_table_data.push(each_data);
        }
        table_data.push(each_table_data);
    }
    return table_data;
}



function get_forecast_array(arr)
{
    let forecast = arr.slice(0, 7);
    for (i=76; i<112; i++)
    {
        forecast.push("+" + i + "H");
    }
    forecast.push("AVE");

    return forecast;
}



function get_splice_data(data, idx, number)
{
    let result = new Array();
    for (let i=0; i<number; i++)
    {
        data.splice(idx+i, 0, "");
    }
    return result;
}



function extract_table_header(head_elements)
{
    let head_val = new Array();
    for (let h=0; h<head_elements.length; h++)
    {
        const td_elements = head_elements[h].getElementsByTagName("td");
        for (let d=0; d<td_elements.length; d++)
        {
            if (d === 0)
            {
                head_val = ["요소", "검증지수", "지 점"];
            }
            const cell_txt = td_elements[d].textContent || td_elements[d].innerText;
            head_val.push(cell_txt);
        }                
    }
    return head_val;
}



function extract_table_data(td_elements, var_select, vrfy, stn)
{
    let data_val = new Array();

    for (let i=0; i<td_elements.length; i++)
    {
        if (i === 0)
        {
            data_val = [var_select, vrfy, stn];
        }

        const cell_txt = td_elements[i].textContent || td_elements[i].innerText;
        data_val.push(cell_txt);
    }

    return data_val;
}


function run_download_csv(csv)
{
    const today = new Date();
    
    const Y = today.getFullYear();
    const M = ('0' + (today.getMonth() +1)).slice(-2);
    const D = ('0' + today.getDate()).slice(-2);
    const h = ('0' + today.getHours()).slice(-2);
    const m = ('0' + today.getMinutes()).slice(-2);
    const s = ('0' + today.getSeconds()).slice(-2);

    const filename = "VRFY_" + Y + "-" + M + "-" + D + "_" +  h + "_" + m + "_" + s + ".csv";


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