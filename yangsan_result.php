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
	$select_sql = "SELECT dong, name, menu, address, number, park, open_time, close_time FROM yangsan_store_info";

	// 동과 메뉴 정보를 추가하여 쿼리문 생성
	$store_dong_sql = $select_sql . " WHERE menu LIKE '%$food%' AND dong = '$dong'";
	// 동과 메뉴를 둘 다 입력했을 때 조회되는 정보 가공
	$query_result_store_dong = sql($store_dong_sql);
	$query_result_store_dong = select_process($query_result_store_dong);

	//메뉴만 입력시
	$store_sql = $select_sql . " WHERE menu LIKE '%$food%'";

	//메뉴만 입력했을 때 조회되는 정보 가공
    $query_result_store = sql($store_sql);
    $query_result_store = select_process($query_result_store);

    //동만 입력시

    $dong_sql = $select_sql. " WHERE dong = '$dong'";

    //동만 입력했을 때 조회되는 정보 가공
    $query_result_dong = sql($dong_sql);
    $query_result_dong = select_process($query_result_dong);

    $rand_sql = "SELECT * FROM yangsan_store_info ORDER BY RAND() LIMIT 1;";

    $query_result_random = sql($rand_sql);
    $query_result_random = select_process($query_result_random);

   

	 
    //메뉴데이터
    $menu_data = array();
    //동+메뉴데이터
    $dong_menu_data = array();
    //동데이터
    $dong_data = array();
    //랜덤데이터
    $random_data = array();

    //메뉴데이터가공
    for($i=0;$i<$query_result_store['output_cnt'];$i++){

    	array_push($menu_data, $query_result_store[$i]);
    }

    //동데이터가공
    for($i=0;$i<$query_result_dong['output_cnt'];$i++){

    	array_push($dong_data, $query_result_dong[$i]);
    }

    //동+메뉴데이터
    for($i=0;$i<$query_result_store_dong['output_cnt'];$i++){

    	array_push($dong_menu_data, $query_result_store_dong[$i]);
    }

    for($i=0;$i<$query_result_random['output_cnt'];$i++){

    	array_push($random_data, $query_result_random[$i]);
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
	<link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&family=Gowun+Dodum&family=Nanum+Pen+Script&family=Sunflower:wght@300&display=swap" rel="stylesheet">	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Gowun+Dodum&family=Nanum+Pen+Script&family=Sunflower:wght@300&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&family=Sunflower:wght@300&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&display=swap" rel="stylesheet">
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

		// 내가 추천하는 식당에 마크 찍어주기
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

		//장소객체생성
		var places = new kakao.maps.services.Places();


		function searchPlaces() {

		    var keyword = document.getElementById('keyword').value;

		    if (!keyword.replace(/^\s+|\s+$/g, '')) {
		        removeAllChildNods(listEl);
		        return false;
		    }

		    ps.keywordSearch( keyword, placesSearchCB); 
		}	

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


      var markers = []; // 마커를 저장할 배열

      positions.forEach(function(position, index) {
    var marker = new kakao.maps.Marker({
        position: position,
        image: markerImage
    });
    marker.setMap(map);
    markers.push(marker); // 마커 저장
   });

      // 지도에 마커를 표시하는 함수
      function displayMarker(place) {
      var marker = new kakao.maps.Marker({
            map: map,
            position: new kakao.maps.LatLng(place.y, place.x)
      });

      kakao.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent('<div style="padding:5px;">' + place.place_name + '</div>');
            infowindow.open(map, marker);
            map.setCenter(marker.getPosition());
      });
   }

   // 지정된 인덱스의 마커 위치로 지도 중심 이동
   function moveToPlace(name) {
      ps.keywordSearch(name, function(data, status) {
         if (status === kakao.maps.services.Status.OK) {
            var coords = new kakao.maps.LatLng(data[0].y, data[0].x);
            map.setCenter(coords);
            var marker = new kakao.maps.Marker({
                  map: map,
                  position: coords
            });

         infowindow.setContent('<div style="padding:5px;">' + data[0].place_name + '</div>');
         infowindow.open(map, marker);
         }
    });
   }

   // 검색 조건에 맞는 장소를 검색
   ps.keywordSearch('<?php echo $dong; ?> <?php echo $food; ?>', function(data, status, pagination) {
         if (status === kakao.maps.services.Status.OK) {
               // 검색된 장소를 지도에 마커로 표시
               for (var i=0; i<data.length; i++) {
                     displayMarker(data[i]);
               }
         }
   });


	// <----- 태영 수정 끝 -----> //
	</script>
		<div id="textbox">
			<p id="head" ><?php
			    if ($dong !== '') {
			        echo $dong;
			    } elseif (empty($dong)) {
			        echo $food;
			    }
			?><br>안심식당</p>
			<div id="wheel">
<!-- 동과 음식 둘다 입력됐을 경우 출력하는 부분 -->
<?php
	if($query_result_store_dong > 0){
		for($i=0;$i<$query_result_store_dong['output_cnt'];$i++){
			$total = $dong_menu_data[$i];
			?>
			<p id="abc" style="margin-bottom: 5px;" onclick="moveToPlace('<?php echo $total['dong']?> <?php echo $total['name']?>');">
                <?php echo $total['dong']; ?> 
                <?php echo $total['name']; ?> 
            </p id="fff"> 
                <br>
            <p id="fff">
                <?php echo $total['address']; ?> <br>
           	</p>
           	<p id="fff">
               메뉴 : <?php echo str_replace('_', ' ', $total['menu']); ?> 
            </p>
            <p id="fff">
               오픈시간 : <?php echo $total['open_time']; ?>  
               마감시간 : <?php echo $total['close_time']; ?>
            </p >
        <?php
	}
}
?>	

<!-- 동만 입력됐을 때 출력하는 부분 -->
<?php
	if(empty($food) && $query_result_dong['output_cnt'] > 0){
		for($i=0;$i<$query_result_dong['output_cnt'];$i++){
			$dong = $dong_data[$i];
			?>
			<p id="abc" onclick="moveToPlace('<?php echo $dong['dong']?> <?php echo $dong['name']?>');">
                <?php echo $dong['dong']; ?> 
                <?php echo $dong['name']; ?> 
            </p> 
                <br>
            <p id="fff">
                <?php echo $dong['address']; ?> <br>
           	</p>
           	<p id="fff">
               메뉴 : <?php echo str_replace('_', ' ', $dong['menu']); ?> 
            </p>
            <p id="fff">
               오픈시간 : <?php echo $dong['open_time']; ?>  
               마감시간 : <?php echo $dong['close_time']; ?>
            </p>
        <?php
	}
}
?>		
<!-- 음식만 입력됐을 때 출력하는부분 -->
<?php 
    if(empty($dong) && $query_result_store['output_cnt'] > 0){
        for($i=0; $i<$query_result_store['output_cnt']; $i++){
            $store = $menu_data[$i];
            ?>
            <p id="abc" onclick="moveToPlace('<?php echo $store['dong']?> <?php echo $store['name']?>');">
                <?php echo $store['dong']; ?> 
                <?php echo $store['name']; ?> 
            </p> 
                <br>
            <p id="fff">
                <?php echo $store['address']; ?> <br>
           	</p>
           	<p id="fff">
               메뉴 : <?php echo str_replace('_', ' ', $store['menu']); ?> 
            </p>
            <p id="fff">
               오픈시간 : <?php echo $store['open_time']; ?>  
               마감시간 : <?php echo $store['close_time']; ?>
            </p>
        <?php
        }
    }
?>
<!-- 조회된 값이 없을 때  -->
<?php
    // 동조회 없을시
    if ($query_result_dong['output_cnt'] === 0 && $query_result_store['output_cnt'] === 0) {
        ?>
        <p id="abc"> 조회된 안심식당이 없습니다 </p>
        <p id="abc" style="margin-bottom:40px">이곳은 어떠세요?</p>
        <p id="fff">상호명 클릭시 이동합니다</p>
        <?php for($i=0;$i<$query_result_random['output_cnt'];$i++){
        	$random = $random_data[$i];
        	?>
		<p id="abc" onclick="moveToPlace('<?php echo $random['dong']?> <?php echo $random['name']?>');">
            <?php echo $random['dong']; ?> 
            <?php echo $random['name']; ?> 
        </p> 
            <br>
        <p id="fff">
            <?php echo $random['address']; ?> <br>
       	</p>
       	<p id="fff">
           메뉴 : <?php echo str_replace('_', ' ', $random['menu']); ?> 
        </p>
        <p id="fff">
           오픈시간 : <?php echo $random['open_time']; ?>  
           마감시간 : <?php echo $random['close_time']; ?>
        </p>
        <?php
    }
    ?>
        <?php
   	// 음식조회 없을시
    } else if ($query_result_store['output_cnt'] === 0) {
        ?>
        <p id="abc"> 조회된 안심식당이 없습니다 </p>
        <p id="abc" style="margin-bottom:40px">이곳은 어떠세요?</p>
        <p id="fff">상호명 클릭시 이동합니다</p>
        <?php for($i=0;$i<$query_result_random['output_cnt'];$i++){
        	$random = $random_data[$i];
        	?>
        <p id="abc" onclick="moveToPlace('<?php echo $random['dong']?> <?php echo $random['name']?>');">
            <?php echo $random['dong']; ?> 
            <?php echo $random['name']; ?> 
        </p> 
            <br>
        <p id="fff">
            <?php echo $random['address']; ?> <br>
       	</p>
       	<p id="fff">
           메뉴 : <?php echo str_replace('_', ' ', $random['menu']); ?> 
        <p id="fff">
           오픈시간 : <?php echo $random['open_time']; ?>  
           마감시간 : <?php echo $random['close_time']; ?>
        </p>
        <?php
    }
    ?>
        <?php
    } else if ($query_result_store_dong['output_cnt'] === 0 && $query_result_dong['output_cnt'] === 0 && $query_result_store['output_cnt'] === 0){
		?>
		<p id="abc"> 조회된 안심식당이 없습니다 </p>
		<p id="abc" style="margin-bottom:40px">이곳은 어떠세요?</p>
		<p id="fff">상호명 클릭시 이동합니다</p>
		<?php for($i=0;$i<$query_result_random['output_cnt'];$i++){
        	$random = $random_data[$i];
        	?>
		<p id="abc" onclick="moveToPlace('<?php echo $random['dong']?> <?php echo $random['name']?>');">
            <?php echo $random['dong']; ?> 
            <?php echo $random['name']; ?> 
        </p> 
            <br>
        <p id="fff">
            <?php echo $random['address']; ?> <br>
       	</p>
       	<p id="fff">
           메뉴 : <?php echo str_replace('_', ' ', $random['menu']); ?> 
        <p id="fff">
           오픈시간 : <?php echo $random['open_time']; ?>  
           마감시간 : <?php echo $random['close_time']; ?>
        </p>
        <?php
    }
    ?>
		<?php
	}
	?>
		</div>
		</div>
	</div>

</body>
</html>