function makeCSVfile_similarity() {

    const period = document.getElementById("data_period");
    const period_val = period.options[period.selectedIndex].value;

    let yyyymm_arr = new Array();
    let model_arr = new Array();
    $(".chartName").each(function(){
        const text = $(this).text();
        const text_split = text.split(":");
        
        const final_split = (text_split[0].trim()).split(" ");
        if (period_val === "FCST")
        {
            yyyymm_arr.push(final_split[0]);
            model_arr.push(final_split[1]);
        }
        else
        {
            model_arr.push(final_split[0]);
        }
    });

    let vrfyname_arr = new Array();
    let vrfyheader_arr = new Array();
    $(".vrfyName").each(function(){
        let text = $(this).text();
        vrfyheader_arr.push(text);
        vrfyname_arr.push(text.replace(/\s/gi, ""));
    });

    let csv = new Array();
    // csv.push(period_txt);

    // 시계열 헤더 수집 (검증지수와 지점 정보 수집을 위함.) - 테이블 id 개수와 동일하다고 
    let vrfy_arr = new Array();
    let stn_arr = new Array();
    let utc_arr = new Array();
    for (let i=0; i<vrfyname_arr.length; i++)
    {
        const split_fir = vrfyname_arr[i].split("_");
        const vrfy_split = split_fir[0].split("[");
        vrfy_arr.push(vrfy_split[1]);
        const stn_split = split_fir[1].split("]");
        stn_arr.push(stn_split[0]);
        
        const utc_fir = stn_split[1].trim();
            if (utc_fir.indexOf("-"))
            {
                const utc_split = utc_fir.split("-");
                utc_arr.push(utc_split[0].trim());
            }
            else
            {
                utc_arr.push(utc_fir);
            }

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

    let head_val = "";
    // item_id, vrfy_arr, stn_arr 는 모두 같은 배열의 개수를 가지고 있다고 가정.
    for (let id=0; id<item_id.length; id++)
    {
        const table = document.getElementById(item_id[id]);
        const tr_elements = table.getElementsByClassName("tb_data");
        if (id === 0)
        {
            const head_elements = table.getElementsByClassName("tb_head");
            for (let h=0; h<head_elements.length; h++)
            {
                const td_elements = head_elements[h].getElementsByTagName("td");
                for (let d=0; d<td_elements.length; d++)
                {
                    if (d ===0)
                    {
                        if (period_val === "FCST")
                        {
                            // 한칸 띄기(period 작성)
                            head_val += ",년월,모델,검증지수,UTC,지 점";
                        }
                        else
                        {
                            head_val += ",모델,검증지수,UTC,지 점";
                        }
                    }
                    const cell_txt = td_elements[d].textContent || td_elements[d].innerText;
                    head_val += "," + cell_txt;
                }                
            }
            csv.push(head_val);
        }
    
        if (utc_arr[id] === "12UTC")
        {
            for (let i=0; i<tr_elements.length; i++)
            {
                const td_elements = tr_elements[i].getElementsByTagName("td");
                // 한칸 띄기(period 작성)
                let td_val = "";
                for (let j=0; j<td_elements.length; j++)
                {
                    if (j === 0)
                    {
                        const cell_txt = td_elements[j].textContent || td_elements[j].innerText;
                        td_val += ((period_val === "FCST") ? "," + yyyymm_arr[id] : "") + "," + model_arr[id] + "," + vrfy_arr[id] + "," + utc_arr[id] + "," + stn_arr[id];
                        td_val += "," + cell_txt;
                    }
                    else if (j === td_elements.length-1)
                    {
                        const cell_txt = get_missing_val(12) + "," + td_elements[j].textContent || td_elements[j].innerText;
                        td_val += "," + cell_txt;
                    }
                    else
                    {
                        const cell_txt = td_elements[j].textContent || td_elements[j].innerText;
                        td_val += "," + cell_txt;
                    }
                }
                csv.push(td_val);
            }
        }
        else if (utc_arr[id] === "00UTC")
            for (let i=0; i<tr_elements.length; i++)
            {
                const td_elements = tr_elements[i].getElementsByTagName("td");
                // 한칸 띄기(period 작성)
                let td_val = "";
                for (let j=0; j<td_elements.length; j++)
                {
                    if (j ===0)
                    {
                        td_val += ((period_val === "FCST") ? "," + yyyymm_arr[id] : "") + "," + model_arr[id] + "," + vrfy_arr[id] + "," + utc_arr[id] + "," + stn_arr[id];
                    }
                    const cell_txt = td_elements[j].textContent || td_elements[j].innerText;
                    td_val += "," + cell_txt;
                }
                csv.push(td_val);
            }
        {

        }
    }
// console.log('csv', csv);

    // assets/js/vrfy_js/ts_stn/CSVdownload/makeCSVfile.js
    run_download_csv(csv);
}

function get_missing_val(missing_num)
{
    let missing_val = "";
    for (let i=0; i<missing_num-1; i++)
    {
        missing_val += ",";
    }
    return missing_val;
}


