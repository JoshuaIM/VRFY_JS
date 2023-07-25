<!-- // 공통 사용 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common/ready_and_now.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/common/make_vrfy_checkbox.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/common/get_option_value.js');?>"></script>
	
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/ts_common_func.js');?>"></script>
	
<!-- // 모든 UI에서 공통적으로 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/common_func.js');?>"></script>
	
<!-- // timeseries 사용 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/call_timeseries_ajax.js');?>"></script>
<!-- // 지점의 id를 매칭하여 지점 이름을 가져온다. 또한 지점 선택 selectBox를 통해 세부 지역을 선택 할 수 있도록 리스팅 한다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/get_station_name.js');?>"></script>

<!-- // 시계열 표출 시 y축 변수의 단위 표시 및 tooltip의 단위 표시를 위해 정보를 제공한다. -->
<!-- // TODO: 향후 "ts_common_func.js"에 포함되는 것을 생각해 본다. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/get_grph_unit.js');?>"></script>
<!-- // highcharts 관련 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/highcharts/highcharts_frame.js');?>"></script>
<!-- // CSV 파일 생성에 사용되는 함수.  -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/CSVdownload/makeCSVfile.js');?>"></script>

<!-- // 2020년 12월 기준 이전 날짜에는 단기 3시간 자료 이후에는 단기 1시간 자료를 표출하기 위해 사용되는 함수. -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/shrt_change_3to1_func.js');?>"></script>

<!-- // 방재기간 관련 함수 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/bangjae/bangjae_ui_options.js');?>"></script>

<!-- // fcst ajax 및 집계표 공통함수 분리 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/highcharts/fcst_ajax.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/highcharts/data_table/data_table.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/highcharts/data_table/data_table_similarity.js');?>"></script>
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/highcharts/ts_highcharts.js');?>"></script>

<!-- // 줌인아웃(전체보기) 기능 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/zoom/zoom.js');?>"></script>

<!-- // 예측기간 범위 선택 표출 기능 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/highcharts/fc_range_select/forecast_selectBox.js');?>"></script>

<!-- // 예측성능표 표출 기능 -->
	<script src="<?php echo base_url('assets/js/vrfy_js/ts_stn/performCompTable/performCompTable.js');?>"></script>
	