
<!-- 사이드 서브 메뉴 : 지점 선택 -->
<?php 
    // 일반 시계열 지점선택
    if($stn === "def") {
?>
        <div class="js2-panel pn">
            <p class="submenu_p"><i class="fa fa-globe" style="font-size:17px; color:#52616a;"></i> 지점선택</p>
            <hr class="submenu_hr">
            <!-- js/vrfy_js/month_common_func.js -->
            <select class="selectMainLoc" onchange="listingSubLoc(this);">
                <option value="EVLALL" selected>표준검증지점</option>
                <option value="ALL" >전체지점</option>
                <option value="47003#47005#47008#47014#47016#47020#47022#47025#47028#47031#47035#47037#47039#47041#47046#47050#47052#47055#47058#47060#47061#47065#47067#47068#47069#47070#47075" >북한</option>
                <option value="47098#47099#47102#47108#47112#47119#47201#47202#47203" >서울경기</option>
                <option value="47090#47095#47100#47101#47104#47105#47106#47114#47121#47211#47212#47216#47217" >강원도</option>
                <option value="47115#47130#47136#47137#47138#47143#47271#47272#47273#47277#47278#47279#47281#47283#00096" >경상북도</option>
                <option value="47152#47155#47159#47162#47192#47255#47284#47285#47288#47289#47294#47295" >경상남도</option>
                <option value="47127#47131#47135#47221#47226" >충청북도</option>
                <option value="47129#47133#47177#47232#47235#47236#47238" >충청남도</option>
                <option value="47140#47146#47172#47243#47244#47245#47247#47248" >전라북도</option>
                <option value="47156#47165#47168#47169#47170#47174#47259#47260#47261#47262#47268" >전라남도</option>
                <option value="47184#47185#47188#47189#47299#329#885" >제주도</option>
                <option value="47092#47110#47113#47128#47151#47163#47167#47139#47142#47153#47158#47161#47182" >공항</option>
            </select>
            <!-- 멀티 지점 선택 -->
            <div id="subLocation" class="checkStation">
                <input type="checkbox" class="checkbox_stn" name="STATION" value="EVLALL" onclick="checkStation(this.name, this.value, this.id); getDataArray();" checked>표준검증지점
            </div>
        </div>

<?php
    // 산악지점 시계열 지점선택
    } else if($stn === "ssps") {
?>
        <div class="js2-panel pn">
            <p class="submenu_p"><i class="fa fa-globe" style="font-size:17px; color:#52616a;"></i> 지점선택</p>
            <hr class="submenu_hr">
            <!-- js/vrfy_js/month_common_func.js -->
            <select class="selectMainLoc" onchange="listingSubLocSSPS(this);">
                <option value="ALL" selected>산악지점 전체평균</option>
                <option value="10112#10122#10142#10151#10172#10193#10202#10211#10213#10222#10262#10272#10282#40262#40281#40497#40595#40980#40984#40990#40997#10252#30191#40321#40331#40979#40985#40988#40991#40995#40998#40999" >산악지점별</option>
            </select>
            <!-- 기존 지점 선택(멀티X) -->
            <!-- <select id="subLocation" name="LOCATION" class="selectMulti" size="9" onclick="getDataArray();" multiple >
                <option value="ALL" selected>&#128440; 전체지점 평균</option>
            </select> -->

            <!-- 멀티 지점 선택 -->
            <div id="subLocation" class="checkStation">
                <input type="checkbox" class="checkbox_stn" name="STATION" value="ALL" onclick="check_ssps_station(this.name, this.value, this.id); getDataArray();" checked>산악지점 전체평균
            </div>
	    </div>

<?php
    } else if($stn === "medm") {
?>
        <div class="js2-panel pn">
            <p class="submenu_p"><i class="fa fa-globe" style="font-size:17px; color:#52616a;"></i> 지점선택</p>
            <hr class="submenu_hr">
            <!-- js/vrfy_js/month_common_func.js -->
            <select class="selectMainLoc" onchange="listingSubLoc(this);">
                <option value="EVLALL" selected>표준검증지점</option>
                <option value="ALL" selected>전체지점</option>
                <option value="47003#47005#47008#47014#47016#47020#47022#47025#47028#47031#47035#47037#47039#47041#47046#47050#47052#47055#47058#47060#47061#47065#47067#47068#47069#47070#47075" >북한</option>
                <option value="47098#47099#47102#47108#47112#47119#47201#47202#47203" >서울경기</option>
                <option value="47090#47095#47100#47101#47104#47105#47106#47114#47121#47211#47212#47216#47217" >강원도</option>
                <option value="47115#47130#47136#47137#47138#47143#47271#47272#47273#47277#47278#47279#47281#47283#00096" >경상북도</option>
                <option value="47152#47155#47159#47162#47192#47255#47284#47285#47288#47289#47294#47295" >경상남도</option>
                <option value="47127#47131#47135#47221#47226" >충청북도</option>
                <option value="47129#47133#47177#47232#47235#47236#47238" >충청남도</option>
                <option value="47140#47146#47172#47243#47244#47245#47247#47248" >전라북도</option>
                <option value="47156#47165#47168#47169#47170#47174#47259#47260#47261#47262#47268" >전라남도</option>
                <option value="47184#47185#47188#47189#47299#329#885" >제주도</option>
                <option value="47092#47110#47113#47128#47151#47163#47167#47139#47142#47153#47158#47161#47182" >공항</option>
            </select>
            
            <!-- 멀티 지점 선택 -->
            <div id="subLocation" class="checkStation">
                <input type="checkbox" class="checkbox_stn" name="STATION" value="EVLALL" onclick="checkStation(this.name, this.value, this.id); getDataArray();" checked>전체지점
            </div>
        </div>

<?php
    }
?>




