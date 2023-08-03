// utc_txt : resp['fcst_info']['utc_txt']
function makeShrtSliderTile(init_hour, utc_txt) {

    let fcstTable = "";

    // 3시간 단위 라벨 기입.					
    fcstTable += "<tr>";
        for(var i=0; i<24; i++) {
            var fi = i + 1;
            if( fi%3 == 0 ) {
                fcstTable += "<td class='sliderLabel' >" + fi + "</td>";
            } else {
                fcstTable += "<td class='sliderLabel' ></td>";
            }
        }
    fcstTable += "<td class='sliderLabel' >AVE</td>";
    fcstTable += "</tr>";

    let slider_num = 0;
    // 1시간 단기 자료 slider 생성.
    // for(var i=0; i<120; i++) {
    for(var i=0; i<144; i++) {
        var fi = i + 1;
        
        if( fi%24 == 1 ) {
            fcstTable += "<tr>";
        }

            // 00 UTC
            // if( (init_hour == "00#00" && fi < 15) || (init_hour == "00#00" && fi > 108) ) {
            if( (init_hour == "00#00" && fi < 15) || (init_hour == "00#00" && fi > 144) ) {
                fcstTable += "<td class='sl_black'> &nbsp; </td>";
                
            // } else if( (init_hour == "12#12" && fi < 2) || (init_hour == "12#12" && fi > 96) ) {
            } else if( (init_hour == "12#12" && fi < 3) || (init_hour == "12#12" && fi > 132) ) {
                fcstTable += "<td class='sl_black'> &nbsp; </td>";
                
            } else {
                slider_num = slider_num +1;
                
                //fcstTable += "<td class='slider' id='Image_" + i + "' title='" + resp['fcst_info']['utc_txt'][i] + "' > &nbsp; </td>";
                // fcstTable += "<td class='slider' id='Image_" + slider_num + "' title='" + utc_txt[slider_num] + "' > &nbsp; </td>";
                fcstTable += "<td class='slider' id='Image_" + slider_num + "' title='" + utc_txt[slider_num] + "' > " + utc_txt[slider_num] + "</td>";
            } 

        
        // if( fi%24 == 0 && fi != 120 ) {
        if( fi%24 == 0 && fi != 144 ) {
            fcstTable += "</tr>";
        // } else if( fi%24 == 0 && fi == 120 ) {
        } else if( fi%24 == 0 && fi == 144 ) {
            // fcstTable += "<td class='slider' id='Image_0' title='" + utc_txt[0] + "' > &nbsp; </td>";
            fcstTable += "<td class='slider' id='Image_0' title='" + utc_txt[0] + "' > AVE </td>";
            fcstTable += "</tr>";
        }
    }
    
    fcstTable += "</table>";
    
    fcstTable += "</div>";

    return fcstTable;
}


// 중기 공간분포 - 예측기간 슬라이드바
function makeMedmSliderTile(init_hour, utc_txt) {

    let fcstTable = "";

    // 3시간 단위 라벨 기입.					
    fcstTable += "<tr>";
        for(let i=0; i<96; i++) {
            let fi = i + 3;
            if( fi%3 == 0 ) {
                let class_name = "";
                if( fi%24 == 0 ) {
                    class_name = "sliderLabel hour24";
                } else {
                    class_name = "sliderLabel";
                }
                fcstTable += "<td class='" + class_name + "' ><P >" + fi + "</p></td>";
            }
        }
    fcstTable += "<td class='sliderLabel' >AVE</td>";
    fcstTable += "</tr>";

    let slider_num = 0;
    // 3시간 중기 자료 slider 생성.

    let end_hour = 0;
    if( init_hour == "00#00" ) {
        end_hour = 128;
    } else if( init_hour == "12#12" ) {
        end_hour = 96;
    }

    for(let i=0; i<end_hour; i++) {
        let fi = i + 1;
        
        if( fi%32 == 1 ) {
            fcstTable += "<tr>";
        }

        // 00 UTC
        if( (init_hour == "00#00" && fi < 5) || (init_hour == "00#00" && fi > 99) ) {
            fcstTable += "<td class='sl_black'> &nbsp; </td>";
        } else if( init_hour == "12#12" && fi > 95 ) {
            fcstTable += "<td class='sl_black'> &nbsp; </td>";
        } else {
            slider_num = slider_num +1;
            // fcstTable += "<td class='slider' id='Image_" + slider_num + "' title='" + utc_txt[slider_num] + "' > &nbsp; </td>";
            fcstTable += "<td class='slider' id='Image_" + slider_num + "' title='" + utc_txt[slider_num] + "' > " + utc_txt[slider_num] + "</td>";
        } 

        if( fi == end_hour ) {
            // fcstTable += "<td class='slider' id='Image_0' title='" + utc_txt[0] + "' > &nbsp; </td>";
            fcstTable += "<td class='slider' id='Image_0' title='" + utc_txt[0] + "' > AVE </td>";
            fcstTable += "</tr>";
        }

    }
    
    fcstTable += "</table>";
    
    fcstTable += "</div>";

    return fcstTable;
}


function make_tmx_tmn_slider_tile(utc_txt)
{
    let fcstTable = "<tr>";
    for(var i=0; i<utc_txt.length; i++) {
            fcstTable += "<td class='sliderLabel' id='ImageL_" + i + "'>" + utc_txt[i] + "</td>";
        }
    fcstTable += "</tr>";
    
    fcstTable += "<tr>";
        for(var i=0; i<utc_txt.length; i++) {
            fcstTable += "<td class='slider' id='Image_" + i + "' title='" + utc_txt[i] + "' > &nbsp; </td>";
        }
    fcstTable += "</tr>";
    
    fcstTable += "</table>";
    
    fcstTable += "</div>";

    return fcstTable;
}
