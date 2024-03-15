<?php
	
	$path = "https://api.telegram.org/bot5139407190:AAGWUJ1ABMWBrvWDcCyH661dYOebKi7GsmY";
	$groupSKID = "-1001761664791";
	// $groupSKID = "988362621";

	$data = [
		// "22/11" => "Luân",
		"10/01" => "Luân",
		"05/03" => "Huy",
		"07/08" => "Phúc",
		"22/02" => "Tuấn",
	];

	$current = date("d/m");
	$txt = "";
	foreach ($data as $key => $value) {
		if ($key == $current) {
			$txt = "Chúc mừng sinh nhật đồng chí " . $data[$key];
			file_get_contents($path."/sendmessage?chat_id=" . $groupSKID . "&parse_mode=HTML&text=". $txt); //send to tele
		}
	}
?>