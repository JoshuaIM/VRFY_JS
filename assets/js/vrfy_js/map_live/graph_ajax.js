
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
// console.log('ajax_url', ajax_url);
// console.log('data', set_data);
console.log("resp", resp);
console.log('graphDirHead', graphDirHead);

                // 그래프 표출 영역 초기화.
                $('#fcstValue').empty();
                $('#contValue').empty();

                const init_hour = set_data["init_hour"][0];
                const var_select = set_data["var_select"];
                const model_sel = set_data["model_sel"];
                const vrfy_idx = set_data["vrfy_idx"];
                const data_head = set_data["data_head"];
                const peri = set_data["peri"];
                let forecast_info_txt = resp['forecast_range'];

                // 표출 항목이 있을 경우 start.
                if( resp["value"] .length > 0) {

                    fcstTable = "";
                    
                    fcstTable += "<table class='map_utc_table' >";
                    
                    // assets/js/vrfy_js/map_slider/make_map_slider.js
                    if( var_select === "TMX" || var_select === "TMN" )
                    {
                        fcstTable += make_tmx_tmn_slider_tile(forecast_info_txt);
                    }
                    else
                    {
                        if ( type === "SHRT" || type === "SSPS" )
                        {
                            fcstTable += makeShrtSliderTile(init_hour, forecast_info_txt);
                        }
                        else if( type === "MEDM" )
                        {
                            fcstTable += makeMedmSliderTile(init_hour, forecast_info_txt);
                        }
                        else
                        {
                            fcstTable = "";
                        }
                    }

                    $('#fcstValue').append(fcstTable);

                    maxstep = resp["value"].length -1;
                    
                    display_grph(var_select, model_sel, vrfy_idx, resp, idx);

                    // 슬라이드바를 직접 클릭할때 호출되는 함수
                    $("td[id^='Image_']").click(function() {
                        idx = $(this).attr("id").substr(6);
                        ImgIndex = idx;
                        // display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);
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
                            // display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);
                        } else if(e.keyCode == 39) { // right
                            idx = idx*1 +1;

                            if (idx > maxstep ){
                                idx = 0;
                            }
                            // display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);
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




    function display_grph(var_select, model_sel, vrfy_idx, resp, idx)
	{
        $("td[id^='Image_']").removeClass("sliderSelected");
        let frameIdx = (idx * 1);
        $("#Image_"+frameIdx).addClass("sliderSelected");
		
		$('#contValue').empty();
		
		// let grph_div = "<div class='img_area col-lg-3'>";
		let grph_div = "<div class='img_area col-lg-4'>";
				grph_div += "<div class='map_header'>";

				grph_div += "</div>";
				grph_div += "<div class='map_content'>";
					grph_div += "<div id='map_view' style='height:650px;'></div>";
				grph_div += "</div>";
		grph_div += "</div>";

		$('#contValue').append(grph_div);

        // assets/js/vrfy_js/map_live/map.js
		leaflet_v(resp);		
	}