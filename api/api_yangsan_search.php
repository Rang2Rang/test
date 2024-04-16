<?php

	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';



	if($_POST['dong'] === ''){
		nowexit(false, '값을 하나라도 입력하세요.');
	}
	if($_POST['dong'] === null){
		nowexit(false, '값을 하나라도 입력하세요.');
	}

	$dong = $_POST['dong'];


	//동 이름을 위도 경도로 가져옴
	$select_sql = "SELECT Latitude, Longitude FROM yangsan_dong_info WHERE dong_name = '$dong'";
	$query_result = sql($select_sql);
	$query_result = select_process($query_result);


	$lat = (double)$query_result[0]['Latitude'];
	$lon = (double)$query_result[0]['Longitude'];



	

	// 정보 조회되지 않았을때 예외처리
	if($query_result['output_cnt'] === 0){
		nowexit(false, '조회된 동이 없습니다.');
	}

	$data = array();

	$data['lat'] = $lat;
	$data['lon'] = $lon;


	
	nowexit(true, '조회성공');




?>
