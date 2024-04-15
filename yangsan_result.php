<?php

    define('__CORE_TYPE__','view');
    include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

    $dong = $_POST['dong'];
    $food = $_POST['food'];


    //양산에 있는 동으로만 조회할 수 있도록 쿼리문 작성
	$select_sql = "SELECT sid FROM yangsan_dong_info WHERE dong_name = '$dong'";
	$query_result = sql($select_sql);
	$query_result = select_process($query_result);


	// 입력받은 동과 메뉴로 데이터베이스 조회하는 쿼리문 생성
	$store_sql = "SELECT dong, name, menu, address, number, park, open_time, close_time 
	          FROM yangsan_store_info 
	          WHERE menu LIKE '%$food%'";

	// 동 정보를 추가하여 쿼리문 생성
	$store_dong_sql = $store_sql . " AND dong = '$dong'";

	$query_result_store_dong = sql($store_dong_sql);
	$query_result_store_dong = select_process($query_result_store_dong);


    $query_result_store = sql($store_sql);
    $query_result_store = select_process($query_result_store);

 

    $total_data = array();

    for($i=0;$i<$query_result_store['output_cnt'];$i++){

    	array_push($total_data, $query_result_store[$i]);
    }




	if (empty($dong) && empty($food)) {
	    // 둘 다 입력되지 않은 경우
	    echo "<script>alert('하나라도 입력해주세요.');</script>";
	    echo "<script>window.location.href = 'yangsan.php';</script>";
	} elseif (!empty($dong) && $query_result['output_cnt'] === 0) {
	    // dong이 유효하지 않은 경우
	    echo "<script>alert('양산에 있는 동만 입력해주세요.');</script>";
	    echo "<script>window.location.href = 'yangsan.php';</script>";
	} 



?>

	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Orbit&family=Stylish&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bagel+Fat+One&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Black+Han+Sans&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<style >
	#container {
		display: flex;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}

	#map {
		width: 700px;
		height: 800px;
		border:  3px solid white;
		border-radius: 15px;
	}

	#textbox {
		width: 400px;
		height: 800px;
		background-color: #dfebf5;
		border: 3px solid #80beed;
		border-radius: 15px;
		text-align: center; /* 추가 */
		position: relative;
	}

	#dong,#food,#time {
		width: 300px;
		height: 40px;
		margin: 20px 0 auto; /* 가운데 정렬을 위한 마진 설정 */
		border: 3px solid #80beed;
		border-radius: 10px;
	}
	#abc{
		margin: 20px 0  auto;
		font-size: x-large;
		font-family: "Orbit", sans-serif;
		color: #454f52;
		font-weight: bold;
		margin-bottom: 20px
	}
	#head{
		margin: 10px 0 auto;
		font-size: 80px;
		font-weight: 300;
		font-family: "Black Han Sans", sans-serif;
		color: #2d65c4;
	}
	#btn{
		width: 200px;
		color: white;
		background-color: #2d65c4;
		margin: 50px 0 auto;
		height: 40px;
		font-weight: 100;
		border-radius: 5px;
		font-size: 22px;
		font-family: "Black Han Sans", sans-serif;
	}
	#boxbox{
		style="padding:5px;
		font-size:15px;
		text-align:center;
		font-weight:bold;
		width:100%;
		width:200px;
		background-color: rgba(135, 206, 235, 0.5);
		font-family: "Black Han Sans", sans-serif;"
	}
	#img{
		width: 180px;
	 	height: 200px;
	  	border: 5px solid #80beed;
	   	border-radius: 5px;
	   	margin-right: 5px;
	   	margin-left: 10px;
	}
	#imgbox{
		display: flex;
		flex-direction: row;
	  	align-items: center;
	   	margin-right: 10px;
	}
	 #left_button, #right_button {
        position: absolute; /* 부모 요소를 기준으로 위치를 설정합니다. */
        bottom: 5px; /* 아래쪽에 배치합니다. */
        width: 60px; /* 버튼의 너비를 설정합니다. */
        height: 30px; /* 버튼의 높이를 설정합니다. */
        border-radius: 5px;
        background-color: #2d65c4;
        border: 1px solid #2d65c4;
    }
    #left_button{
    	left: 10px;
    }
    #right_button{
    	right: 10px;
    }
    #wheel{
    	overflow: auto;
    	width: 400px;
    	height: 500px;
    }
    ::-webkit-scrollbar{
    	display: none;
    }

</style>

<body>
    <div id="container">
    	<div id="map"></div>
    	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=a36d9dd6255fd543307de8afb0a7669c&libraries=services,clusterer,drawing"></script>
		<script>
		//마커를 클릭하면 장소명을 표출할 인포 윈도우
		var infowindow = new kakao.maps.InfoWindow({zIndex:1});

		var container = document.getElementById('map');
		var options = {
			center: new kakao.maps.LatLng(35.2323, 126.3232),
			level: 5
		};

		var map = new kakao.maps.Map(container, options);

		var mapTypeControl = new kakao.maps.MapTypeControl();

		//오른쪽에 컨트롤추가
		map.addControl(mapTypeControl, kakao.maps.ControlPosition.TOPRIGHT);

		//줌 컨트롤 생성
		var zoomControl = new kakao.maps.ZoomControl();
		map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);

		// 장소 검색 객체를 생성합니다
		var ps = new kakao.maps.services.Places(); 

		var imageSrc = '/img/daebak.png', // 마커이미지의 주소입니다    
		    imageSize = new kakao.maps.Size(50, 50), // 마커이미지의 크기입니다
		    imageOption = {offset: new kakao.maps.Point(27, 69)};

		var markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imageOption);

		// 마커를 표시할 위치 배열
		var positions = [
		    new kakao.maps.LatLng(35.40786, 129.15774),
		    new kakao.maps.LatLng(35.3292, 129.0122),
		    new kakao.maps.LatLng(35.3070, 129.1064),
		    new kakao.maps.LatLng(35.3789, 129.1514),
		    new kakao.maps.LatLng(35.3661, 128.9188),
		    new kakao.maps.LatLng(35.3972, 129.0545),
		    new kakao.maps.LatLng(35.4472, 129.0842),
		    new kakao.maps.LatLng(35.3420, 129.0376),
		    new kakao.maps.LatLng(35.3491, 129.0406),
		    new kakao.maps.LatLng(35.3375, 129.0285),
		    new kakao.maps.LatLng(35.3553, 129.0374),
		    new kakao.maps.LatLng(35.3576, 129.0472),
		    new kakao.maps.LatLng(35.3453, 129.0284),
		    new kakao.maps.LatLng(35.4153, 129.1694),
		    new kakao.maps.LatLng(35.3708, 129.1487)

		];

		// 배열을 사용하여 여러 마커 생성 및 지도에 표시
		positions.forEach(function(position) {
		    var marker = new kakao.maps.Marker({
		        position: position, // 마커의 위치
		        image: markerImage  // 마커 이미지 설정
		    });
		    
		    // 마커가 지도 위에 표시되도록 설정
		    marker.setMap(map);
		});
		

		// Post로 입력받은 값 넣기(키워드검색)
		ps.keywordSearch('양산'+'<?php echo $dong; ?> <?php echo $food; ?>', placesSearchCB); 

		// 키워드 검색 완료 시 호출되는 콜백함수 
		function placesSearchCB (data, status, pagination) {
		    if (status === kakao.maps.services.Status.OK) {

		        // 검색된 장소 위치를 기준으로 지도 범위를 재설정하기위해
		        // LatLngBounds 객체에 좌표를 추가합니다
		        var bounds = new kakao.maps.LatLngBounds();


		        for (var i=0; i<data.length; i++) {
		            displayMarker(data[i]);    
		            bounds.extend(new kakao.maps.LatLng(data[i].y, data[i].x));
		        }

		        console.log(bounds);       

		        // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
		        map.setBounds(bounds);
		    } 
		}

		// 지도에 마커를 표시하는 함수입니다
		function displayMarker(place) {
		    
		    // 마커를 생성하고 지도에 표시합니다
		    var marker = new kakao.maps.Marker({
		        map: map,
		        position: new kakao.maps.LatLng(place.y, place.x) 
		    });

		    // 마커에 클릭이벤트를 등록합니다
		    kakao.maps.event.addListener(marker, 'click', function() {
		        // 마커를 클릭하면 장소명이 인포윈도우에 표출됩니다
		        infowindow.setContent('<div style="padding:5px;font-size:17px;text-align:center;font-weight:bold;width:100%;width:170px;background-color: #2d65c4;color:white;border:1px solid white;border-radius: 9px;box-sizing: border-box; ">' + place.place_name + '</div>');
		        infowindow.open(map, marker);
		    });
		}
	</script>
		<div id="textbox">
			<p id="head" ><?php
			    if ($dong !== '') {
			        echo $dong;
			    } elseif (empty($dong)) {
			        echo $food;
			    }
			?><br>추천맛집</p>
			<div id="wheel">
		
<!-- 음식부문 -->
<?php 
    if(empty($dong) && $query_result_store['output_cnt'] > 0){
        for($i=0; $i<$query_result_store['output_cnt']; $i++){
            $store = $total_data[$i];
            ?>
            <p id="abc" style="margin-bottom: 5px;" onclick="gogo();">
                <?php echo $store['dong']; ?> 
                <?php echo $store['name']; ?> 
            </p> 
                <br>
            <p>
                <?php echo $store['address']; ?> <br>
           	</p>
           	<p>
               메뉴 : <?php echo str_replace('_', ' ', $store['menu']); ?> 
            </p>
               오픈시간 : <?php echo $store['open_time']; ?>  
               마감시간 : <?php echo $store['close_time']; ?>
            </p>
        <?php
        }
    }
?>
			</div>
		</div>
	</div>

</body>
</html>
