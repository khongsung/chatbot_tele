<?php
	$url = 'https://dantri.com.vn/gia-vang.htm';
	$path = "https://api.telegram.org/bot5139407190:AAGWUJ1ABMWBrvWDcCyH661dYOebKi7GsmY";
	// $groupSKID = "-1001761664791";
	$groupSKID = "988362621";

	$query = http_build_query(array(
		'chat_id'=> $groupSKID,
		'parse_mode'=> "HTML",
		'text'=> "Giá vàng : $url \n \nTỉ giá ngoại tệ: https://portal.vietcombank.com.vn/Personal/TG/Pages/ty-gia.aspx",
	));

	file_get_contents("$path/sendmessage?$query");
?>