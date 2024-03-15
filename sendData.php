<?php

	$path = "https://api.telegram.org/bot5139407190:AAGWUJ1ABMWBrvWDcCyH661dYOebKi7GsmY";
	//$groupSKID = "-1001761664791";
	$groupSKID = "988362621";

	$htmlSensor = "11"; 
	$htmlLuuluong = "22";
	// read file data

	$htmlLuuluong = file_get_contents("files/luuLuong.txt");
	$htmlSensor = file_get_contents('files/sensorData.txt');
	
	$txt = getWeather(). "\n \n$htmlSensor  \n \n$htmlLuuluong";

	$query = http_build_query(array(
		'chat_id'=> $groupSKID,
		'parse_mode'=> "HTML",
		'text'=> $txt,
	));

	file_get_contents("$path/sendmessage?$query"); //send to tele

	function getWeather() {
		$api = "https://api.openweathermap.org/data/3.0/onecall?lat=21.244970968644566&lon=106.18273952512693&exclude=daily,minutely,hourly&appid=453f8cc8fe3af4de0b0af078e84015ff&units=metric";
		$respone = file_get_contents($api);
		$json = json_decode($respone);
		$data = $json->current;
		$html = "Weather: ".$data->weather[0]->main." <b>$data->temp</b> &#8451;";
		return $html;
	}

	// echo $htmlLuuluong;
?>
