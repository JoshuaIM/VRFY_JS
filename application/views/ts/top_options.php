
<section class="top_wrapper">
    <div class="containter" style="height:100%;">
        <div class="dateSelect" >

        	<select id="data_period" name="PERIOD" class="dateSelBox" onChange="changeBangjaeType(this.value); getDataArray();">
        		<option value="FCST" selected>예측기간(월별)</option>	
				<?php 
				if ( $type != "SSPS" )
				{
				?>
					<?php 
					if ( $type != "GEMD" )
					{
					?>
						<option value="MONTH">월별</option>	
					<?php
					}
					?>
					<option value="SEASON">계절별</option>	
					<option value="BANGJAE">방재기간별</option>	
					<option value="ALLMONTH">전체기간</option>	
				<?php
				}
				?>
        	</select>

			<!-- 방재기간 선택 시 ON -->
        	<select id="select_bangjae_date" name="SELYEAR" class="dateSelBox bangjae_date" onChange=" makeBangJaeSeasonOptions(BANGJAEMAP); getDataArray();" ></select>
        	<select id="select_bangjae_season" name="SELSEASON" class="dateSelBox bangjae_date" onChange="getDataArray();" ></select>
            <div class="btn-group  bangjae_date" >
            	<input class="dateBox" id="bangjae_startD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 방재기간 선택 시 ON -->

			<!-- 계절별 선택 시 ON -->
        	<select id="select_season_date" name="SELYEAR" class="dateSelBox season_date" onChange=" makeSeasonSeasonOptions(SEASONMAP); getDataArray();" ></select>
        	<select id="select_season_season" name="SELSEASON" class="dateSelBox season_date" onChange="getDataArray();" ></select>
            <div class="btn-group  season_date" >
            	<input class="dateBox" id="season_startD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 계절별 선택 시 ON -->

			<!-- 전체기간 선택 시 ON -->
            <div class="btn-group  allmonth_date" >
            	<input class="dateBox" id="allmonth_startD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 전체기간 선택 시 ON -->

            <div class="btn-group original_date" >
				<input class="dateBox" id="sInitDate" name="sInitDate" type="text" style="width:85px;" />
				<!-- <input class="dateBox" id="sInitDate" name="sInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" /> -->
				<!-- <input class="dateBox" id="sInitDate" name="sInitDate" data-date="02/2012" data-date-format="mm/yyyy" data-date-viewmode="years" data-date-minviewmode="months" style="width:85px;" /> -->
            	<!-- <input class="dateBox" id="cal1" /> -->
            </div>
            <!-- <div class="btn-group original_date" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openSCalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div> -->
        
        	<b class="date_wave">~</b>
        
			<!-- 방재기간 선택 시 ON -->
            <div class="btn-group  bangjae_date" >
            	<input class="dateBox" id="bangjae_endD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 방재기간 선택 시 ON -->

			<!-- 계절별 선택 시 ON -->
            <div class="btn-group  season_date" >
            	<input class="dateBox" id="season_endD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 계절별 선택 시 ON -->

			<!-- 전체기간 선택 시 ON -->
            <div class="btn-group  allmonth_date" >
            	<input class="dateBox" id="allmonth_endD" type="text" style="width:85px; background:#E0E3DA" value="" readonly />
            </div>
			<!-- 전체기간 선택 시 ON -->

            <div class="btn-group original_date">
            	<input class="dateBox" id="eInitDate" name="eInitDate" type="text" style="width:85px;" />
            	<!-- <input class="dateBox" id="eInitDate" name="eInitDate" type="text" onChange="chkCalendar(this);" style="width:85px;" /> -->
            </div>
            <!-- <div class="btn-group original_date" >
            	<button class="dateBtn" type="button" id="innerBtn" onclick="openECalendar();" >
            		<i class="glyphicon glyphicon-calendar" ></i>
        		</button>
            </div> -->
            <div class="btn-group">
            	<button class="nowBtn original_date" type="button" onclick="readyAndNowFunc();">NOW</button>
            	<button class="totalDownBtn" type="button" onclick="makeCSVfile();">DOWNLOAD CSV FILE</button>
            </div>

			<div class="btn-group viewPerformTable">
				<!-- <button class="performTableBtn" type="button" onclick="">예측 성능 비교표 보기</button> -->
			</div>

			<div class="btn-group zoom_grph" >
				<div class="grphZoomOut">
					<input type="checkbox" id="GRPH_ZOOM" onclick="getDataArray()" /> <text>전체그래프보기</text>
				</div>
			</div>

    	</div>
    	
		<!-- assets/js/vrfy_js/common_func.js -->
    	<div id="vrfySelect" class="verifIndexArea" >
    	</div>
    </div>   
</section>