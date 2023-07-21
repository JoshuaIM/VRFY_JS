// 그래프 표출 유무
    function is_ts_view(data, peri)
    {
        if ( peri === "MONTH" )
        {
            let chk_data_exist = 0;
            for ( let t=0; t<data[0]['data'].length; t++ )
            {
                if ( data[0]['data'][t] )
                {
                    chk_data_exist = chk_data_exist +1;
                }
            }

            if ( chk_data_exist < 1 )
            {
                return false;
            }
            else
            {
                return true;
            } 
        }
        else
        {
            if ( data.length == 0 )
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }

// 월별 그래프 표출 유무
	function is_month_ts_view(dataInfo)
	{
		let cht_arr_num = new Array();

		// 그래픽당 모든 배열값이 null일 경우 표출 안하기 위함.
		for (let li=0; li<dataInfo.length; li++)
		{
			var cht_line_chk = 0;
			for (let li_ck=0; li_ck<dataInfo[li]['data'].length; li_ck++)
			{
				if( dataInfo[li]['data'][li_ck] != null ) {
					cht_line_chk = cht_line_chk +1;
				}
			}
			// 그래프 당 값 개수를 파악하여 표출을 할지 안할지 파악.
			cht_arr_num.push(cht_line_chk);			
			// 배열 값 모두 null이면 표출 그래프 개수에서 -1
			if( cht_line_chk < 1 ) {
				cht_line_num = cht_line_num-1;
			}
		}

		return cht_arr_num;
	}


	

// 중복 선택 가능 체크박스에서 모든 체크박스 풀리지 않도록 체크하는 함수
	function checkNoneSelectBox(document_name, document_value) {

		const doc_name = document_name;
		let checked_arr = new Array();
		$("input[name=" + doc_name + "]:checked").each(function() {
    		checked_arr.push($(this).val());
    	});

		if( checked_arr.length == 0 ) {
			$("input[name=" + doc_name + "][value='" + document_value + "']").prop("checked", true);
		}
	}
	
	
	// 예보편집 : 요소선택 전체선택 기능 추가 및 모든 체크박스가 풀리지 않도록 체크.
	// function checkVariable(this.name, this.value, this.id) {
		function checkVariable(document_name, document_value, document_id) {
			
			const chk_box_obj = $("input[name=" + document_name + "][value!=ALL]");
			const chk_total_num = chk_box_obj.length;
			
			const chked_box = $("input[name=" + document_name + "][value!=ALL]:checked");
			const chked_num = chked_box.length;

			// 전체선택 클릭 시
			if( document_value === "ALL" ) {
				// 모두 선택이 되었을 시 기온빼고 모두 해제.
				if( chk_total_num == chked_num ) {
					$("input[name=" + document_name + "]").prop("checked", false);
					$("input[name=" + document_name + "][value=T1H]").prop("checked", true);
				} else {
					$("input[name=" + document_name + "]").prop("checked", true);
				}
				
			} else {
				
				if( chk_total_num == chked_num ) {
					$("input[name=" + document_name + "][value=ALL]").prop("checked", true);
				} else {
					$("input[name=" + document_name + "][value=ALL]").prop("checked", false);
					if( chked_num == 0 ) {
						$("input[name=" + document_name + "][value='" + document_value + "']").prop("checked", true);
					}
				}

			}




	}