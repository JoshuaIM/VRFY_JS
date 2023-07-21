<section id="main" class="wrapper">

	<div class="containter">
		<!-- 결과물 표출 영역 위 라인 -->
        <div class="table table-bordered table-striped table-condensed cf" id="expGrid" ></div>
    
		<!-- 결과물 표출 영역 -->
    	<div id="grph_area" style="width:330px; height:85vh; float:left;">
    	
			<!-- canvas grid test -->
    		<!-- <div id="grph_map" class="col-xs-4 col-sm-4 col-md-4 col-lg-3 subWindow"> -->
    		<div id="grph_map" style="margin-left: 10px; width:312px;">
    			<div class="box">
    				<div class="box-header">
    					<div class="box-name">
    						<i class="fa fa-bar-chart"></i>
    						<span>지점 선택</span>
    					</div>
    					
						<div class='xyInput'>
						<!-- x: <input type='text' id='coordinates_x_input' class='xyInputText' onkeypress='allowOnlyNumericInput(event);' placeholder='1~149'>&nbsp -->
						<!-- y: <input type='text' id='coordinates_y_input' class='xyInputText' onkeypress='allowOnlyNumericInput(event);' placeholder='1~253'> -->
							x: <input type='text' id='coordinates_x_input' class='xyInputText' onkeypress='allowOnlyNumericInput(event);' >&nbsp
							y: <input type='text' id='coordinates_y_input' class='xyInputText' onkeypress='allowOnlyNumericInput(event);' >
							<button type='button' id='userSpecified_submit' class='xyInputText btn btn-primary btn-xs' style='margin-bottom:5px; margin-left:5px; width:40px; color:white;'>적용</button>
						</div>
    					
    				</div>
    				<div class="box-content" id="tileMap">
    					<canvas id="canvas" width="300" height="508"></canvas><!--  width="336" height="560" -->
    					<!-- <canvas id="canvas" width="500" height="845"></canvas>  -->
    					<img id="mapOverlay" class=""  src="<?php echo base_url('assets/img/korea_map.png');?>" style="width:300px;"/> <!-- class="img-responsive" -->
    					<!-- <img id="mapOverlay" class=""  src="<?php echo base_url('assets/img/korea_map.png');?>" style="width:500px;"/> -->
    				</div>
    			</div>
    		</div>
    		<!-- canvas grid test -->
    	</div>
    	
    	<div id="cht_area" style="width:120vh; height:100vh; float:left;">
    	</div> 
    	
    	
    </div>
    
</section>