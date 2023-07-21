
<!-- 예보편집용 사이드 서브 메뉴 : 요소선택 -->
<div class="gemdVar-panel pn">
		<p class="submenu_p"><i class="fa fa-tasks" style="font-size:16px; color:#52616a;"></i> 요소선택</p>
		<hr class="submenu_hr">
		<div id="subVariable" class="checkVariable">
            <table>
                    <tr><td>
                        <input type="checkbox" class="checkbox_var" name="VARIABLE" value="ALL" onclick="checkVariable(this.name, this.value, this.id); getDataArray();" checked>전체선택<br>
                    </td></tr>
                <?php 
                for ($i=0; $i<sizeof($varArray); $i++) {
                    $input = "<input type='checkbox' class='checkbox_var' name='VARIABLE' value=" . $varArray[$i] . " onclick='checkVariable(this.name, this.value, this.id); getDataArray();' checked>";
                    if( $i % 2 == 0 ) {
                        echo "<tr><td>" . $input . $varnameArray[$i] . "</td>";
                    } else {
                        echo "<td>&nbsp;" . $input . $varnameArray[$i] . "<br> </td></tr>";
                    }
                }
                ?>
            </table>
        </div>
</div>