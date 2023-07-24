function call_timeseries_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri)
{

    let start_init = "";
    let end_init = "";
    let bangjae_date = "";
    let season_date = "";

    if( peri === "BANGJAE" )
    {
        bangjae_date = $("#select_bangjae_date").val() + $("#select_bangjae_season").val();
    } 
    else if( peri === "SEASON" )
    {
        season_date = $("#select_season_date").val() + $("#select_season_season").val();
    }
    else
    {
        // (UI)기간 시작 값
        start_init = $("input:text[name='sInitDate']").val();
        // (UI)기간 끝 값
        end_init = $("input:text[name='eInitDate']").val();
    }

    let set_data = new Array();
    if ( peri == "FCST" || peri == "MONTH" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, location, start_init, end_init, null, vrfy_idx, peri);
    }
    else if( peri == "BANGJAE" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, location, null, null, bangjae_date, vrfy_idx, peri);
    }
    else if( peri == "SEASON" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, location, null, null, season_date, vrfy_idx, peri);
    }
    else if( peri === "ALLMONTH" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, location, null, null, null, vrfy_idx, peri);
    }

    const ajax_url = site_url + "/ts/shrt_stn/ajax_ts_stn_data";
    call_ajax_ts_data(ajax_url, set_data);

}



function call_ssps_timeseries_ajax(data_head, var_select, model_sel, init_hour, location, vrfy_idx, peri)
{
    console.log('location', location);
    // (UI)기간 시작 값
    const start_init = $("input:text[name='sInitDate']").val();
    // (UI)기간 끝 값
    const end_init = $("input:text[name='eInitDate']").val();

    const set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, location, start_init, end_init, null, vrfy_idx, peri);

    const ajax_url = site_url + "/ts/shrt_stn/ajax_ts_stn_data";
    call_ajax_ts_data(ajax_url, set_data);
}



function set_ajax_data(data_head, var_select, init_hour, model_sel, location, start_init, end_init, range_date, vrfy_idx, peri)
{
    let set_data = {
        "data_head" : data_head,
        "var_select" : var_select,
        "init_hour" : init_hour,
        "model_sel" : model_sel,
        "location" : location,
        "start_init" : start_init,
        "end_init" : end_init,
        "range_date" : range_date,
        "vrfy_idx" : vrfy_idx,
        "peri" : peri
    }; 

    return set_data;
}






