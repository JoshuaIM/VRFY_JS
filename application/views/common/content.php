<section class="wrapper">

	<div class="containter">
		<!-- 결과물 표출 영역 위 라인 -->
        <div class="table table-bordered table-striped table-condensed cf" id="expGrid" ></div>
    
		<?php
		if( $grph_type === "map" )
		{
		?>
			<!-- 예보시각 표출 영역 (공간분포 표출 시) -->
			<div id="fcstValue" class="col-lg-12" ></div>
		<?php
		}
		?>

		<!-- 결과물 표출 영역 -->
    	<div id="contValue"></div>
    </div>
    
</section>