
	var userSpecificPoints = [];

	var nx = 149;
	var ny = 253;
	
	var default_colors = ['#3399CC','#DC3912','#FF9900','#109618','#990099','#3B3EAC','#0099C6','#DD4477','#66AA00','#B82E2E','#316395','#994499','#22AA99','#AAAA11','#6633CC','#E67300','#8B0707','#329262','#5574A6','#3B3EAC']
	
	var tiles_array = [];
	
// 첫 페이지 Canvas 기능 표출을 위해 "makeCanvas()" 메서드 실행.
//    $(document).ready(function(){
//    	
//    	// 일반 페이지인지 임의기간 페이지인지 구별하기 위해 $('#data_period') 객체의 값을 받는다.
//    	// 일반의 경우 'FCST', 임의기간의 경우 'ARBI_ANN' 또는 'ARBI_FER' 이다.
//    	var periType = $('#data_period').val();
//    	
//    	if( periType.substring(0,4) == "ARBI" ) {
//    		// 임의 기간의 경우 년월일 포멧.
//    		var dFormat = "yymmdd";
//    	} else {
//    		var dFormat = "yymm";
//    	}
//    	
//    	readyAndNowFunc(dFormat);
//    	
//    	makeCanvas();
//	});
	
	
// Canvas 기능 표출.
	function makeCanvas(x_check, y_check) {
		
    	var canvas_element = document.getElementById('canvas');
		var context = canvas_element.getContext('2d');
    	
		//var tiles_array = [];

		
		function Tile(x, y, width, height, id, fillColor, strokeStyle) {
			this.id = id;
			this.x = x;
			this.y = y;
			this.width = width;
			this.height = height;
			this.workWidth = {
			    start: x,
			    end: x + width
			}
			this.workHeight = {
				start: y,
				end: y + height
			}
			this.fillColor = fillColor;
			this.strokeStyle = 'rgba(187,187,187, 0.1)'; //#bbbbbb
		}
    	
		// TODO: Canvas 초기화
		clearCanvas();
		userSpecificPoints = [];
		
	// 사용자가 지도위의 지점을 마우스로 클릭 할 경우.
		canvas_element.onclick = function(e) {
			event = e;
			var elementClickedId = checkClick(event);
			var tileIdx = elementClickedId * 1;
			var xc = tileIdx % nx +1;
			var xy = Math.floor(tileIdx / nx)+1;

			//xy 0,0  
			var xx = ( tileIdx % nx ) +1 ;
			var yy = ( ny-(Math.floor(tileIdx / nx)) -1 ) +1 ;

			var usp = xx + "," + yy;
			// 선택 지점 중복 확인.
				for( var chk=0; chk<userSpecificPoints.length; chk++ ) {
					if( userSpecificPoints[chk] == usp ) {
						alert("이미 지정된 지점입니다.");
						return false;
					}
				}
			
//			if(userSpecificPoints.length >= 8 ){
//				alert('8개 이상 포인트를 찍을 수 없습니다.');
//				return;
//			}
			tiles_array[elementClickedId].fillColor = 'rgba(255,0,0, 1.0)'
			clearCanvas();
			drawTiles();

			//sysout( tiles_array[elementClickedId])
			
			userSpecificPoints.push(usp);
			
//console.log("선택 x좌표: " + xx);
//console.log("선택 y좌표: " + yy);
console.log("선택 좌표: [" + usp + "]");

			// 시계열 그래프 표출.
			getDataArray();
		}
		
		
		var height = window.innerHeight - 49;
		$('#main').css('wrapper', height)
			.on('click', '.close-window', function (e) {
				e.preventDefault();
				
				var window = this.name;
				
				var tile_pos = window.split('_')[2]; 
		 		var xy_arr = tile_pos.split("X");
		 		
		 		var xx = xy_arr[0];
		 		var yy = xy_arr[1];
	
		 		// 점 찍힌 타일의 Index
		 		var tileIdx = (nx*ny)-(nx*(yy*1)) + (xx-1);		
		 		
		 		tiles_array[tileIdx].fillColor = 'rgba(0,0,0, 0.0)'
		 		clearCanvas();
		 		drawTiles();
				
	 			// userSpecificPoints 저장된 xy좌표 찾아 지우기.
	 			var pointIdx = userSpecificPoints.indexOf(xx+","+yy);
				if (pointIdx !== -1) userSpecificPoints.splice(pointIdx, 1);
	 			
				$('#'+window).remove();
		});
		
		
	// 사용자가 직접 기입 할 경우.
		$("#userSpecified_submit").click(function() {
          	if(!$("#coordinates_x_input").val() || !$("#coordinates_y_input").val()){
          		alert('xy 값을 모두 입력하세요. ');
          		return;
          	} else if($("#coordinates_x_input").val()*1 > 149 || $("#coordinates_x_input").val()*1 < 1){
          		alert('x좌표의 입력범위는 1~149 입니다.')
          		return;
          	} else if($("#coordinates_y_input").val()*1 > 253 || $("#coordinates_y_input").val()*1 < 1){
          		alert('y좌표의 입력범위는 1~253 입니다.')
          		return;
          	} else{
                var xx = $("#coordinates_x_input").val()*1;
                var yy = $("#coordinates_y_input").val()*1

    			var usp = xx + "," + yy;
				for( var chk=0; chk<userSpecificPoints.length; chk++ ) {
					if( userSpecificPoints[chk] == usp ) {
						alert("이미 지정된 지점입니다.");
						return false;
					}
				}
                
                var tileIdx = (nx*ny)-(nx*(yy*1)) + (xx-1);
                
    			tiles_array[tileIdx].fillColor = 'rgba(255,0,0, 1.0)'
				clearCanvas();
				drawTiles();
							
				userSpecificPoints.push(usp);
				
//console.log("직접기입 x좌표: " + xx);
//console.log("직접기입 y좌표: " + yy);
//console.log("직접기입 좌표: [" + usp + "]");

				// 시계열 그래프 표출.
				getDataArray();
				
          	}		
		});
		
		
		function clearCanvas(){
			if(canvas_element){
				context.clearRect(0, 0, canvas_element.width, canvas_element.height);
  			}
		}
		/////////////////////////////////////////
		/////////////////////////////////////////

		canvas_element.onmousemove = function(e) {
			var elementUnder = checkClick(event);
			if (elementUnder == 1) {
				changeCursor('pointer');
			} else {
				changeCursor('default'); 
			}
		}

		canvas_element.onmouseout = function(e) {
			changeCursor('default');
		}


		//  IE11-Edge, Chrome 간의 layerXY 값이 상이함 - IE에서는 canvas margin만큰 선택된 coordinates 역시 시프트되어야 하는디 margin을 무시함 
		//  margin 만큼 시프트된 좌표값 얻을려면 
		//	  x :: event.clientX -canvas_element.getBoudingClientRect().left
		//	  y :: event.clientY -canvas_element.getBoudingClientRect().top 
		function checkClick(event) {
			//var clickX = event.layerX;
			//var clickY = event.layerY;
			
			let rect = canvas_element.getBoundingClientRect(); 
			let clickX = event.clientX - rect.left; 
			let clickY = event.clientY - rect.top; 

			var element;
		  
			tiles_array.forEach(function(tile, i, arr) {
			    if (
		    		clickX > tile.workWidth.start &&
		    		clickX < tile.workWidth.end &&
		    		clickY > tile.workHeight.start &&
		    		clickY < tile.workHeight.end
			    ) {
			    	element = tile.id;
			    }
			});
			
			return element;
		}

		
		
		// Create Tiles
		function createTiles(quantityX, quantityY) {
			var quantityAll = quantityX * quantityY;
			var tileWidth = canvas_element.width / quantityX;
			var tileHeight = canvas_element.height / quantityY;
		  
			var drawPosition = {
				x: 0,
				y: 0
			}
		  
			for (var i = 0; i < quantityAll; i++) {
				var fillColor = getRandomColor();
				var tile = new Tile(drawPosition.x, drawPosition.y, tileWidth, tileHeight, i, fillColor);
				
				tiles_array.push(tile);
    
				drawPosition.x = drawPosition.x + tileWidth;
					if (drawPosition.x >= canvas_element.width) {
						drawPosition.x = 0;
						drawPosition.y = drawPosition.y + tileHeight;
					}
			}
		  
		}
		
		///////////////////
		createTiles(nx, ny);
		///////////////////

		function drawTiles() {
			tiles_array.forEach(function(tile, i, arr) {
			    context.beginPath()
			    
			    context.fillStyle = tile.fillColor;
			    context.rect(tile.x, tile.y, tile.width, tile.height);
			    
			    context.lineWidth="1";
			    context.strokeStyle = tile.strokeStyle; 
			    context.stroke()
	
			    context.fill();
			});
		}

		drawTiles();

		function getRandomColor() {
			var letters = '0123456789ABCDEF';
			var color = '#';
			
		    for (var i = 0; i < 6; i++ ) {
		        color += letters[Math.floor(Math.random() * 16)];
		    }
			    return 'rgba(187,187,187, 0.0)'; //#bbbbbb
			}

			function changeCursor(cursorType){
			  document.body.style.cursor = cursorType;
			}		
		
	}
	
//===================================================================//
//===== End of "makeCanvas()" Function ==============================//    
//===================================================================//
	
	
	
// 좌표확인 용.
    function sysout(str2print){
		if(isChrome) console.log(str2print);
		else return;
	}
    
// 시작페이지의 일정 선택 datepicker 일반 or 임의기간 포멧으로 초기화. 
//	function readyAndNowFunc(dFormat) {
//		changeDatePicker(null, null, dFormat);
//	}
	
    
// DatePicker 적용 함수.
    function changeDatePicker(setStrDate, setEndDate, dFormat) {
    
    	var strDate = setStrDate;
    	var endDate = setEndDate;
    	
    	if( !setStrDate || !setEndDate ) {
            strDate = new Date(currentStrDate);
            endDate = new Date(currentEndDate);
    	}
    
    	// datepicker 초기화 ( default date 초기화를 위함. )
    	$('#sInitDate').datepicker( "destroy" );
    	$('#eInitDate').datepicker( "destroy" );
    	
        $('#sInitDate').datepicker({
            dateFormat: dFormat, 
            changeYear: true, 
            autoclose: true, 
            yearRange: "2018:2020",
            defaultDate: strDate
        });
    	$('#sInitDate').datepicker('setDate', strDate);
    	
      	$('#eInitDate').datepicker({
    	  	dateFormat: dFormat, 
    	  	changeYear: true, 
    	  	autoclose: true, 
            yearRange: "2018:2020",
    	  	defaultDate: endDate
      	});
    	$('#eInitDate').datepicker('setDate', endDate);
    }


    // 캘린더 아이콘 클릭 시 표출.
    function openSCalendar(){
    	$("#sInitDate").datepicker("show");
    }
    // 캘린더 아이콘 클릭 시 표출.
    function openECalendar(){
    	$("#eInitDate").datepicker("show");
    }

    
// 격자 시계열의 경우 plot 버튼이 필요 없다. 격자 지점 클릭 시 작동.
//	// 임의기간과 구별하기 위한 함수. (월자료는 선택 즉시 작동 But 임의기간은 PLOT 버튼 클릭 시 작동)
//	function listingSubLoc(subLocVal) {
//		setSubLocation(subLocVal);
//		getDataArray();
//	}