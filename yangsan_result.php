<?php

    define('__CORE_TYPE__','view');
    include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

    $dong = $_POST['dong'];
    $food = $_POST['food'];


    //양산에 있는 동으로만 조회할 수 있도록 쿼리문 작성
	$select_sql = "SELECT sid FROM yangsan_dong_info WHERE dong_name = '$dong'";
	$query_result = sql($select_sql);
	$query_result = select_process($query_result);


	//입력받은 동과 메뉴로 데이터베이스 조회하는 쿼리문 생성
	$store_sql = "SELECT dong, name, menu, address, number, park, open_time, close_time 
          		 FROM yangsan_store_info 
         		 WHERE menu LIKE '%$food%'";

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
		<?php 
			if($dong === '소주동'){
					?>
			<p id="abc">소주동 촌닭이 두마리</p>
			<div id="imgbox">
				<img id="img" src="/img/soju.png">
				<img id="img" src="/img/soju2.jpg">
			</div>
			<p id="abc">합리적인 가격<br>옛날 시장에서 먹던 그맛<br>믿고 먹는 치킨</p>
			<p id="abc">추천메뉴<br>🍺후라이드치킨  🍗옛날통닭<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '물금읍' ){
					?>
			<p id="abc">물금 조개구이 주어</p>
			<div id="imgbox">
				<img id="img" src="/img/mul.jpg">
				<img id="img" src="/img/mul2.jpg">
			</div>
			<p id="abc">술이 술술넘어가<br>높은 퀄리티의 조개구이<br>먹고 죽는거야..</p>
			<p id="abc">추천메뉴<br>🐚가리비구이 🐙조개전골<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '동면'){
					?>
			<p id="abc">동면 느티나무의 사랑</p>
			<div id="imgbox">
				<img id="img" src="/img/dong.jpg">
				<img id="img" src="/img/dong2.jpg">
			</div>
			<p id="abc">뷰 맛 집<br>가성비 최고의 베이커리<br>🦆넓은 야외공간</p>
			<p id="abc">추천메뉴<br>🥤살구에이드 🍹애플유자티<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '평산동'){
					?>
			<p id="abc">평산동 더 웅촌</p>
			<div id="imgbox">
				<img id="img" src="/img/pyung.jpg">
				<img id="img" src="/img/pyung2.jpg">
			</div>
			<p id="abc">이집 국밥 진짜 최고<br>해장하러 갔다가<br>개꽐라되기 딱좋은 곳</p>
			<p id="abc">추천메뉴<br>무조건 순대국밥<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '원동면'){
					?>
			<p id="abc">원동면 전망좋은집</p>
			<div id="imgbox">
				<img id="img" src="/img/onedong.jpg">
				<img id="img" src="/img/onedong2.jpg">
			</div>
			<p id="abc">원동면 하면 역시나 미나리<br>뷰 맛 집<br>향긋한 미나리와 삼겹살</p>
			<p id="abc">추천메뉴<br>미나리삼겹살 미나리전<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '상북면'){
					?>
			<p id="abc">상북면 겟인상북</p>
			<div id="imgbox">
				<img id="img" src="/img/sang.jpg">
				<img id="img" src="/img/sang2.jpg">
			</div>
			<p id="abc">연인과 함께하면 너무 좋은곳<br>맛있는 베이커리와 커피 한잔<br>상북에서 가장 이쁜 카페</p>
			<p id="abc">추천메뉴<br>황치즈크로플 겟인슈페너<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '하북면'){
					?>
			<p id="abc">하북면 아미드포레</p>
			<div id="imgbox">
				<img id="img" src="/img/ha.jpg">
				<img id="img" src="/img/ha2.jpg">
			</div>
			<p id="abc">나만알고싶어 여기서살고싶어<br>옆에 잔잔히 흐르는 계곡과 함께<br>시그니쳐 메뉴를 함께 즐겨요</p>
			<p id="abc">추천메뉴<br>돌멩이 포레이드<p>
			<?php	
		}
	?>	
	<?php 
			if($dong === '남부동'){
					?>
			<p id="abc">남부동 첨단돌솥감자탕</p>
			<div id="imgbox">
				<img id="img" src="/img/nam.jpg">
				<img id="img" src="/img/nam2.jpg">
			</div>
			<p id="abc">돌솥밥과 감자탕<br>얼큰칼칼 쓰면서도 배고프다<br>배신하지 않는 맛</p>
			<p id="abc">추천메뉴<br>감자탕<p>
			<?php	
		}
	?>	
	<?php 
			if($dong === '북부동'){
					?>
			<p id="abc">북부동 크랩엔그릴</p>
			<div id="imgbox">
				<img id="img" src="/img/buk.jpg">
				<img id="img" src="/img/buk2.jpg">
			</div>
			<p id="abc">비쥬얼을 봐라 맛없을 수가 없다<br>맛있는거 + 맛있는거<br>우대갈비 걸려있는거좀봐</p>
			<p id="abc">추천메뉴<br>우대갈비 대게<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '중부동'){
					?>
			<p id="abc">중부동 무라</p>
			<div id="imgbox">
				<img id="img" src="/img/jung.png">
				<img id="img" src="/img/jung2.jpg">
			</div>
			<p id="abc">이걸 먹으러 부산 울산에서도 온다<br>그냥 찐한 육수에 일본라멘의 끝<br>항상 웨이팅이 있지만 그럴만해</p>
			<p id="abc">추천메뉴<br>소유라멘 돈코츠라멘<p>
			<?php	
		}
	?>	
	<?php 
			if($dong === '신기동'){
					?>
			<p id="abc">신기동 빌리빌리</p>
			<div id="imgbox">
				<img id="img" src="/img/sin.jpg">
				<img id="img" src="/img/sin2.jpg">
			</div>
			<p id="abc">그냥 신기동가면 여길 가세요<br>분위기에 사로잡히는 카페<br>요즘 젊은이들은 꼭 방문해</p>
			<p id="abc">추천메뉴<br>코젤크림라떼 큐브브리오슈<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '북정동'){
					?>
			<p id="abc">북정동 소문</p>
			<div id="imgbox">
				<img id="img" src="/img/bukjung.jpg">
				<img id="img" src="/img/bukjung2.jpg">
			</div>
			<p id="abc">소문 듣고 왔습니다..<br>이 가격에 이 퀄리티를 ?<br>가장 합리적인 가격으로 소고기를</p>
			<p id="abc">추천메뉴<br>소갈비살 부채살<p>
			<?php	
		}
	?>		
	<?php 
			if($dong === '교동'){
					?>
			<p id="abc">교동 춘추원할매집</p>
			<div id="imgbox">
				<img id="img" src="/img/gyo.jpg">
				<img id="img" src="/img/gyo2.jpg">
			</div>
			<p id="abc">몸보신 하러 오세요<br>손 큰 할머니가 제공해주는<br>얼큰~한 오리백숙과 불고기</p>
			<p id="abc">추천메뉴<br>오리불고기 오리백숙<p>
			<?php	
		}
	?>	
	<?php 
			if($dong === '서창동'){
					?>
			<p id="abc">서창동 서창카츠</p>
			<div id="imgbox">
				<img id="img" src="/img/su.jpg">
				<img id="img" src="/img/su2.jpg">
			</div>
			<p id="abc">기본에 충실한 카츠<br>깔끔하고 조용한 공간에서 한끼<br>혼자 먹으러오기 너무 좋아요</p>
			<p id="abc">추천메뉴<br>히레카츠 로스카츠<p>
			<?php	
		}
	?>
	<?php 
			if($dong === '덕계동'){
					?>
			<p id="abc">덕계동 내고향문현곱창</p>
			<div id="imgbox">
				<img id="img" src="/img/duk.jpg">
				<img id="img" src="/img/duk2.jpg">
			</div>
			<p id="abc">잡내 하나도안나는 돼지막창<br>술이 꿀꺽꿀꺽 들어가서 인사불성<br>라면도 끓여먹을 수 있답니다</p>
			<p id="abc">추천메뉴<br>돼지막창 한우소곱창전골<p>
			<?php	
		}
	?>
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