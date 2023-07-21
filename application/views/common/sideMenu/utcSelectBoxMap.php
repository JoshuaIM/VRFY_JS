
<!-- 사이드 서브 메뉴 : 초기시각 선택 -->
<div class="js1-panel pn">
    <p class="submenu_p"><i class="fa fa-clock-o" style="font-size:17px; color:#52616a;"></i> 초기시각</p>
    <hr class="submenu_hr">
    <table id="CHECK_SELECT" class="chk_select">
        <tr>
            
            <!-- <td><input type="checkbox" name="INIT_HOUR" value="00#00" onclick="getDataArray()" checked > 00UTC (09KST)</td> -->
           
            <td><input type="checkbox" id="00utc" name="INIT_HOUR" value="00#00" onclick="setHour(this.id); getDataArray()" checked > 00UTC (09KST)</td>
        </tr>
        <tr>
            
            <!-- <td><input type="checkbox" name="INIT_HOUR" value="12#12" onclick="getDataArray()" > 12UTC (21KST)</td> -->
           
            <td><input type="checkbox" id="12utc" name="INIT_HOUR" value="12#12" onclick="setHour(this.id); getDataArray()" > 12UTC (21KST)</td>
        </tr>
        <tbody>
        </tbody>
    </table>
</div>