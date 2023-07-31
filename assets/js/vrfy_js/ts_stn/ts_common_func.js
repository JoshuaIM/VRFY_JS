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


