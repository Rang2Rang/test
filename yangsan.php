<?php

    define('__CORE_TYPE__','view');
    include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';



?>

	
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&family=Orbit&family=Stylish&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bagel+Fat+One&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


<body>
	<div id="container">
		<div id="textbox">
			<p id="head">양산 <br>안심식당<br> 탐지기</p>
			<p id="abc">동을 입력해주세요</p>
			<input  type="text" id="dong">
			<p id="abc" style="font-size: 19px;">정확하게 입력해주세요!<br>ex) 물금❌ 물금읍⭕</p>
			<div style="height: 20px;"></div>
			<p id="abc">음식을 입력해주세요</p>
			<input type="text" id="food">
			<p id="abc" style="font-size: 18px;">동과 음식 중 하나만 입력해도<br>검색이 가능합니다!</p>
			<button id="btn" onclick="search();" type="button" class="btn btn-primary">가보자~</button>
		</div>
	</div>
</body>