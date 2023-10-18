    function leaflet_v(data)
    {
        // Leaflet을 사용하여 지도를 생성
        let map = L.map('map_view', {zoomControl:false}).setView([37.5, 128], 6); // 대한민국 중심에 지도 표시
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);
        

        let circles = new Array();
        for (let i=0; i<data["lat"].length-1; i++)
        {
            const obj = { lat: data["lat"][i], lon: data["lon"][i], color: 'green' };

            circles.push(obj);
        }
        // let circles = [
        // { lat: 37.5665, lon: 126.9780, color: 'red' },
        // { lat: 35.1796, lon: 129.0756, color: 'blue' },
        // { lat: 35.8714, lon: 128.6014, color: 'green' }
        // ];
        
        
        // 각 원을 지도에 추가
        circles.forEach(function(circle) {
        L.circle([circle.lat, circle.lon], {
            color: "black", // 선 색상
            weight: 0.5,
            fillColor: circle.color, // 내부 채우기 색상
            fillOpacity: 1, // 내부 채우기 투명도
            radius: 6000 // 반지름 (미터 단위)
        }).addTo(map);
        });
    }
