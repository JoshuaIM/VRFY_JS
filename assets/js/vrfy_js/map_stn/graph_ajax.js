
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
console.log('data', set_data);
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
                let forecast_info_txt = "";
                if ( type != "GEMD" )
                {
                    forecast_info_txt = resp['fcst_info']['utc_txt'];
                }

                let dir_path = "";
                if( peri === "FCST" ) {
                    dir_path = graphDirHead.split("./")[1];
                } else if( peri === "BANGJAE" ) {
                    dir_path = bangjae_graph_dir.split("./")[1];
                } else if( peri === "SEASON" ) {
                    dir_path = season_graph_dir.split("./")[1];
                } else if( peri === "ALLMONTH" ) {
                    dir_path = allmonth_graph_dir.split("./")[1];
                }
console.log('call dir_path', dir_path);

                // 표출 항목이 있을 경우 start.
                if( resp["fcst_info"] ) {

                    fcstTable = "";
                    
                    fcstTable += "<table class='map_utc_table' >";
                    
                    // assets/js/vrfy_js/map_slider/make_map_slider.js
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
                    $('#fcstValue').append(fcstTable);

                    maxstep = resp['fcst_info']['utc_txt'].length -1;
                    
                    // display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head);
                    display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);

                    // 슬라이드바를 직접 클릭할때 호출되는 함수
                    $("td[id^='Image_']").click(function() {
                        idx = $(this).attr("id").substr(6);
                        ImgIndex = idx;
                        display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);
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
                            display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);
                            
                        } else if(e.keyCode == 39) { // right
                            idx = idx*1 +1;

                            if (idx > maxstep ){
                                idx = 0;
                            }
                            display_grph(var_select, model_sel, vrfy_idx, resp, idx, data_head, dir_path);
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


    function call_ajax_utilize_map_data(ajax_url, set_data)
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

                const peri = set_data["peri"];
                const init_hour = set_data["init_hour"][0];
                const var_select = set_data["var_select"];
                const model_sel = set_data["model_sel"];
                const vrfy_idx = set_data["vrfy_idx"];
                const data_head = set_data["data_head"];

                const date_info = resp["date_info"];

                // assets/js/vrfy_js/ts_stn/bangjae/bangjae_ui_options.js
                if( peri === "BANGJAE" )
                {
                    setBangjaeInitDate(date_info[0]['data']);
                }
                else if( peri === "SEASON" )
                {
                    setSeasonInitDate(date_info[0]['data']);
                }
                else if( peri === "ALLMONTH" )
                {
                    setAllmonthInitDate(date_info[0]['data']);
                }

                let viewTable = "";
                const thead_txt = ["수치예보 가이던스", "예보편집", "차이분포<br>(수치예보가이던스 - 예보편집)", "활용도"];

                let utc_spilit = init_hour[0].split("#");
                let utc = utc_spilit[0] + "UTC";

                viewTable += "<table style='border: 1px solid #6E7783; /*width:90%;*/ margin-left: 45px; margin-top: 30px;'>";
                
                viewTable += "<tr>";
                    viewTable += "<thead style='text-align: center; height:70px; background-color:#84B1ED; font-size: medium; font-weight:bold; '>";
                        for( let t=0; t<thead_txt.length; t++ ) {
                            if( t == 0 ) {
                                viewTable += "<td style='border: 1px solid #6E7783;'>";
                                viewTable += "</td>";
                            }
                            viewTable += "<td style='border: 1px solid #6E7783;'>";
                            viewTable += thead_txt[t];
                            viewTable += "</td>";
                        }
                    viewTable += "</thead>";
                viewTable += "<tr>";

                for( let mon=0; mon<date_info.length; mon++ ) {
                    for( let vrfy=0; vrfy<vrfy_idx.length; vrfy++ ) {
                        for( let modl=0; modl<model_sel.length; modl++ ) {
                            
                            let modl_split = model_sel[modl].split("_");
                            let modl_txt = modl_split[0];

                            viewTable += "<tr>";
                            
                            for( let i=0; i<4; i++ ) {
                                if( i == 0 ) {
                                    viewTable += "<td style='border: 1px solid #6E7783; width: 100px; height:510px; text-align:center; font-size: small; font-weight:bold; '>";
                                    viewTable += 
                                                resp['date_info'][mon]['ymInfo'] + "<br><br>" + 
                                                modl_txt + "<br><br>" + 
                                                $("[name=VAR] option:selected").text() + "<br>" + 
                                                // assets/js/vrfy_js/common_func.js
                                                get_vrfy_title(vrfy_data, vrfy_title, vrfy_idx[vrfy]) + "<br><br>" + 
                                                utc + "<br><br>";
                                    viewTable += "</td>";
                                }
                                viewTable += "<td style='border: 1px solid #6E7783; text-align: center; width:340px;'>";

                                // 첫번째 수치예보 가이던스 이미지는 단기 공간분포 이미지.
                                let head_dir_path = "";
                                if( peri === "FCST" ) {
                                    head_dir_path = graphDirHead.split("./")[1];
                                } else if( peri === "BANGJAE" ) {
                                    head_dir_path = bangjaeGraphDirHead.split("./")[1];
                                } else if( peri === "SEASON" ) {
                                    head_dir_path = seasonGraphDirHead.split("./")[1];
                                } else if( peri === "ALLMONTH" ) {
                                    head_dir_path = allmonthGraphDirHead.split("./")[1];
                                }

                                let yyyymm_directory = "";
                                if( peri === "ALLMONTH" ) {
                                    yyyymm_directory = "202302";
                                } else {
                                    yyyymm_directory = date_info[mon]['ymInfo'];
                                }

                                // Global: site_url
                                const graph_url = site_url + head_dir_path;
                                const tail_path = yyyymm_directory + "/" + var_select + "/";
                                const directory_full_path = graph_url + tail_path;

                                const utilize = $("input[name=UTILIZE_INDEX]:checked").val();
                                const data_date = date_info[mon]['data'];
                                const graph_name = getGraphName(i, data_head, model_sel[modl], var_select, vrfy_idx[vrfy], utilize, data_date);

                                viewTable += "<img class='grph_img' style='width:330px; height:500px;' src='" + directory_full_path + graph_name + "' onerror='no_image(this);' />" ;


                                viewTable += "</td>";
                            }
                            
                            viewTable += "</tr>";
                        }
                    }
                }					
                
                viewTable += "</table>";

                $('#contValue').append(viewTable);


            }, // End of "success : function(resp)"
            error : function(error) 
            {
                alert("error");
                console.log(error);
            }
        })
    }
    function getGraphName(number, data_head, model, var_select, vrfy_idx, utilize, data_date) {

		const split_model = model.split("_");
		let def_model = "";
		if( split_model[0] === "BEST" ) {
			def_model = split_model[0] + "_MERG";
		} else {
			def_model = split_model[0] + "_NPPM";
		}

		let graph_name = data_head;
		switch (number) {
			case 0 : graph_name += def_model + "_" + var_select + "_VRFY_" + vrfy_idx + "." + data_date + "_AVE.png";		break;
			case 1 : graph_name += split_model[1] + "_" + var_select + "_VRFY_" + vrfy_idx + "." + data_date + "_AVE.png";	break;
			case 2 : graph_name += model + "_" + var_select + "_DIFF_" + vrfy_idx + "." + data_date + "_AVE.png";			break;
			case 3 : graph_name += model + "_" + var_select + "_" + utilize + "." + data_date + "_AVE.png";					break;
		}

		return graph_name;
	}

