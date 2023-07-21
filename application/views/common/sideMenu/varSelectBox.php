
<!-- 사이드 서브 메뉴 : 요소선택 -->
<div class="selBoxPn-panel pn">
		<p class="submenu_p"><i class="fa fa-tasks" style="font-size:16px; color:#52616a;"></i> 요소선택</p>
		<hr class="submenu_hr">
		<!-- 2021.03.25 Edit.
			<select class="eleSelBox" name="VAR" onchange="selVar(this);">
		-->
		<select class="eleSelBox" name="VAR" onchange="selVar(this.value);">
            <?php 
            for ($i=0; $i<sizeof($varArray); $i++) {
                if($varArray[$i] == $varName) {
            ?>
                <option value="<?php echo $varArray[$i]; ?>" selected><?php echo $varnameArray[$i]; ?></option>	
            <?php 
                } else {
            ?>
                <option value="<?php echo $varArray[$i]; ?>" ><?php echo $varnameArray[$i]; ?></option>	
            <?php 
                }
            }
            ?>
		</select>
</div>