
    function call_ajax_map_data(ajax_url, set_data)
    {
        $.ajax({
            type : "POST",
            data : set_data,
            dataType: "json",
            url : ajax_url, 
            // 변수에 저장하기 위함.
            async: false,
            success : function(resp)
            {
console.log("resp", resp);
// console.log('data', set_data);
// console.log('graphDirHead', graphDirHead);

                // 그래프 표출 영역 초기화.
                $('#fcstValue').empty();
                $('#contValue').empty();

                const init_hour = set_data["init_hour"][0];
                const var_select = set_data["var_select"];
                const model_sel = set_data["model_sel"];
                const vrfy_idx = set_data["vrfy_idx"];
                const data_head = set_data["data_head"];
                const forecast_info_txt = resp['fcst_info']['utc_txt'];

                // 표출 항목이 있을 경우 start.
                if( resp["fcst_info"] ) {

                    fcstTable = "";
                    
                    fcstTable += "<table class='map_utc_table' >";
                    
                    // assets/js/vrfy_js/map_slider/make_map_slider.js
                    if( type === "SHRT" || type === "SSPS" ) {
                        fcstTable += makeShrtSliderTile(init_hour, forecast_info_txt);
                    } else if( type === "MEDM" ) {
                        fcstTable += makeMedmSliderTile(init_hour, forecast_info_txt);
                    }
                    $('#fcstValue').append(fcstTable);

                    maxstep = resp['fcst_info']['utc_txt'].length -1;
                    
                    // display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
                    display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);

                    // 슬라이드바를 직접 클릭할때 호출되는 함수
                    $("td[id^='Image_']").click(function() {
                        idx = $(this).attr("id").substr(6);
                        ImgIndex = idx;
                        display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
                    });

                    // 한번 클릭 시 여러번 실행되는 문제(여러번 콜했을 경우)를 방지.
                    $("body").off('keydown');
                    // 좌우 화살표 키
                    $("body").keydown(function(e) {
                        if(e.keyCode == 37) { // left
                            idx = idx*1 -1;
                            if (idx < 0){
                                idx = maxstep;
                            }
                            display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
                            
                        } else if(e.keyCode == 39) { // right
                            idx = idx*1 +1;

                            if (idx > maxstep ){
                                idx = 0;
                            }
                            display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
                        }
                    });
                } // 표출 항목이 있을 경우 End of if.
            }, // End of "success : function(resp)"
            error : function(error) 
            {
                alert("error");
                console.log(error);
            }
        })

    }



    function display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head) {

        $("td[id^='Image_']").removeClass("sliderSelected");
        var frameIdx = (idx * 1);
        $("#Image_"+frameIdx).addClass("sliderSelected");
		
		$('#contValue').empty();
		
		var grph_div = "";
        for(var m=0; m<model_sel.length; m++) {
            for(var v=0; v<vrfy_idx.length; v++) {
                for(var d=0; d<resp['date_info'].length; d++) {
            		grph_div += "<div class='img_area col-lg-3' >";
                		grph_div += "<div class='map_header'>";
                			var vrfy_name = get_vrfy_title(vrfy_data, vrfy_title, vrfy_idx[v]);
                			grph_div += model_sel[m] + "_" + vrfy_name + "_" + resp['date_info'][d]['ymInfo'] + "_" + resp['date_info'][d]['utcInfo'];
                		grph_div += "</div>";
                		grph_div += "<div class='map_content'>";
                		
							var nameOfgrph = data_head + model_sel[m] + "_" + var_select + "_VRFY_" + vrfy_idx[v] + "." + resp['date_info'][d]['data'] + "_" +  resp['fcst_info']['utc_idx'][idx] + ".png";

							var dt_arr = data_head.split("_");
                			grph_div += "<img class='grph_img' src='" + "<?php echo base_url('/'); ?>" + graphDirHead + resp['date_info'][d]['ymInfo'] + "/" + var_select + "/" + nameOfgrph + "' onerror='no_image(this);' />" ;
                			
                		grph_div += "</div>";
            		
            		grph_div += "</div>";
                } 
            }
        }
        
		$('#contValue').append(grph_div);
	}


    function no_image(th) {
        console.log(th);
		$(th).attr("src", "<?php echo base_url('assets/img/nodata.gif');?>");
    }