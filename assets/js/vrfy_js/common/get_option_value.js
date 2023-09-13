// (UI)초기시각 UTC 선택 값 (중복 선택)
    function get_init_hour_option()
    {
        let init_hour = new Array();

        $("input[name=INIT_HOUR]:checked").each(function() {
            init_hour.push($(this).val());
        });
        if( init_hour.length < 1 ) {
            $("input[name=INIT_HOUR][value='00#00']").prop("checked", true);
            init_hour.push("00#00");
        }

        return init_hour;
    }


// (UI)모델 및 기법 선택 값 (중복 선택)
    function get_model_option()
    {
        let model_sel = new Array();
        $("input[name=MODEL_TECH]:checked").each(function() {
            model_sel.push($(this).val());
        });
            if( model_sel.length < 1 ) {
                alert("한개 이상의 모델을 선택해 주십시오");
                return false;
            }

        return model_sel;
    }


// (UI)지점 선택 값
    function get_location_datahead_option(data_head)
    {
        const res = new Map();
        let location =  new Array();
        let datahead = data_head;

        // 멀티 지점 선택 가능
        $("input[name=STATION]:checked").each(function() {
            const selLoc = $(this).val();
            if( selLoc == "ALL" ) {
                if( sub_type === "SIMILARITY" )
                {
                    location.push( "ST308" );
                }
                else
                {
                    location.push( "AVE" );
                }
            } else if( selLoc == "247ALL" ) {
                if( sub_type === "SIMILARITY" )
                {
                    location.push( "ST247" );
                }
                else
                {
                    location.push( "AVE" );
                    // 글로벌 변수(data_head) 값 변경
                    datahead = data_head.replace("DFS", "247");
                }
            } else {
                // 권역의 전체가 선택 시
                if( $("input[id=loc_ave]").is(":checked") ) {
                    const id_val = $("input[id=loc_ave]").val();
                    location = id_val.split("#"); 
                } else {
                    // 권역평균 선택 시
                    if( $("input[id=loc_mean]").is(":checked") ) {
                        location = selLoc.split('#');
                    // 각각의 지점 선택 시
                    } else {
                        location.push(selLoc);
                    }
                }
            }
        });

        res.set("location", location);
        res.set("datahead", datahead);
        
        return res;
    }
// (UI)지점 선택 값
    function get_location_datahead_option_GEMD(data_head)
    {
        const res = new Map();
        let location =  new Array();

        // 멀티 지점 선택 가능
        $("input[name=STATION]:checked").each(function() {
            const selLoc = $(this).val();
            if( selLoc == "ALL" ) {
                location.push( "ST308" );
            } else if( selLoc == "247ALL" ) {
                location.push( "ST247" );
            } else {
                // 권역의 전체가 선택 시
                if( $("input[id=loc_ave]").is(":checked") ) {
                    const id_val = $("input[id=loc_ave]").val();
                    location = id_val.split("#"); 
                } else {
                    // 권역평균 선택 시
                    if( $("input[id=loc_mean]").is(":checked") ) {
                        location = selLoc.split('#');
                    // 각각의 지점 선택 시
                    } else {
                        location.push(selLoc);
                    }
                }
            }
        });

        res.set("location", location);

        return res;
    }


// (UI)검증지수 선택 값 (중복 선택)
    function get_vrfy_option()
    {
		let vrfy_idx = new Array();

		$("input[name=VRFY_INDEX]:checked").each(function() {
			vrfy_idx.push($(this).val());
		});

        return vrfy_idx;
    }