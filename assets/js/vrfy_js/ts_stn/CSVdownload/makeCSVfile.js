function makeCSVfile() {

    const var_select = $("select[name=VAR]").val();

    let vrfyname_arr = new Array();
    $(".vrfyName").each(function(){
        let text = $(this).text();
        vrfyname_arr.push(text.replace(/\s/gi, ""));
    });

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
    }

    // 검증자료 값 테이블 id 수집
    let item_id = new Array();
    $(".fcstTable").each(function(){
        item_id.push($(this).attr("id"));
    });

    let head_val = "";
    let csv = new Array();
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
                        // td_val += "검증지수" + " | " + "지 점" + " | ";
                        head_val += "요소,검증지수,지 점";
                    }
                    const cell_txt = td_elements[d].textContent || td_elements[d].innerText;
                    // td_val += cell_txt + " | ";
                    head_val += "," + cell_txt;
                }                
            }
            csv.push(head_val);
        }
    
        for (let i=0; i<tr_elements.length; i++)
        {
            const td_elements = tr_elements[i].getElementsByTagName("td");
            let td_val = "";
            for (let j=0; j<td_elements.length; j++)
            {
                if (j ===0)
                {
                    // td_val += vrfy_arr[i] + " | " + stn_arr[i] + " | ";
                    td_val += var_select + "," + vrfy_arr[id] + "," + stn_arr[id];
                }
                const cell_txt = td_elements[j].textContent || td_elements[j].innerText;
                // td_val += cell_txt + " | ";
                td_val += "," + cell_txt;
            }
            // td_val += "\n";
            csv.push(td_val);
        }
    }
console.log('csv', csv);

    // const today = new Date();
    
    // const Y = today.getFullYear();
    // const M = ('0' + (today.getMonth() +1)).slice(-2);
    // const D = ('0' + today.getDate()).slice(-2);
    // const h = ('0' + today.getHours()).slice(-2);
    // const m = ('0' + today.getMinutes()).slice(-2);
    // const s = ('0' + today.getSeconds()).slice(-2);

    // const filename = "VRFY_" + Y + "-" + M + "-" + D + "_" +  h + "_" + m + "_" + s + ".csv";


    // // 한글 처리를 위한 BOM 추가
    // const BOM = '\uFEFF' ;

    // csv = BOM + csv.join('\n');

    // let csvFile = new Blob([csv], {type: 'text/csv'});

    // downloadLink = document.createElement("a");
    // downloadLink.download = filename;
    // downloadLink.href = window.URL.createObjectURL(csvFile);
    // downloadLink.style.display = "none";

    // document.body.appendChild(downloadLink);

    // downloadLink.click();
}