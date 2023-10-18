function call_graph_ajax(data_head, var_select, model_sel, init_hour, vrfy_idx, peri)
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
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, start_init, end_init, null, vrfy_idx, peri);
    }
    else if( peri == "BANGJAE" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, null, null, bangjae_date, vrfy_idx, peri);
    }
    else if( peri == "SEASON" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, null, null, season_date, vrfy_idx, peri);
    }
    else if( peri === "ALLMONTH" )
    {
        set_data = set_ajax_data(data_head, var_select, init_hour, model_sel, null, null, null, vrfy_idx, peri);
    }

    const ajax_url = site_url + "/map/shrt_live/ajax_map_stn_data";
    // assets/js/vrfy_js/map_stn/graph_ajax.js
    if( type === "GEMD" )
    {
        call_ajax_utilize_map_data(ajax_url, set_data);
    }
    else
    {
        call_ajax_map_data(ajax_url, set_data);
    }

}



function set_ajax_data(data_head, var_select, init_hour, model_sel, start_init, end_init, range_date, vrfy_idx, peri)
{
    let set_data = {
        "data_head" : data_head,
        "var_select" : var_select,
        "init_hour" : init_hour,
        "model_sel" : model_sel,
        "start_init" : start_init,
        "end_init" : end_init,
        "range_date" : range_date,
        "vrfy_idx" : vrfy_idx,
        "peri" : peri,
        "type" : type,
        "sub_type" : sub_type
    }; 
    return set_data;
}






