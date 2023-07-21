
<!-- 사이드 서브 메뉴 : 모델선택 -->
<?php 
		// 시계열 === "정확도"
        if ($vrfyTypeName[0] == "단기" || $vrfyTypeName[0] == "시계열") {
    ?>
		<div class="shrtModelTech-panel pn">
	<?php 
	   } else if ($vrfyTypeName[0] == "중기") {
    ?>
    	<div class='medmModelTech-panel pn'>
	<?php
	   }
    ?>
		<p class="submenu_p"><i class="fa fa-sitemap" style="font-size:16px; color:#52616a;"></i> 모델선택</p>
		<hr class="submenu_hr">
		<table class="chk_select">

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][0];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][0] . "_" . $modltech_info['tech_id'][0][0] ;?>" onclick="checkNoneSelectBox(this.name, this.value); getDataArray();" checked >
				<?php echo $modltech_info['tech_name'][0][0]; ?>
				</td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][1];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][1] . "_" . $modltech_info['tech_id'][1][0] ;?>" onclick="checkNoneSelectBox(this.name, this.value); getDataArray();" >
				<?php echo $modltech_info['tech_name'][1][0]; ?>
				</td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][2];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][2] . "_" . $modltech_info['tech_id'][2][0] ;?>" onclick="checkNoneSelectBox(this.name, this.value); getDataArray();" >
				<?php echo $modltech_info['tech_name'][2][0]; ?>
				</td>
			</tr>

			<tr>
				<td class="modl_td"><b> <?php echo $modltech_info['modl_name'][3];?> </b></td>
			</tr>
			<tr>
				<td>
				<input type="checkbox" name="MODEL_TECH" value="<?php echo $modltech_info['modl_id'][3] . "_" . $modltech_info['tech_id'][3][0] ;?>" onclick="checkNoneSelectBox(this.name, this.value); getDataArray();" >
				<?php echo $modltech_info['tech_name'][3][0]; ?>
				</td>
			</tr>

		</table>
	</div>