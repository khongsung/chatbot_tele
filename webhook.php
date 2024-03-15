<?php
	echo '1';
	$path = "https://api.telegram.org/bot5139407190:AAGWUJ1ABMWBrvWDcCyH661dYOebKi7GsmY";

	$update = json_decode(file_get_contents("php://input"), TRUE);

	$chatId = $update["message"]["chat"]["id"];
	$message = $update["message"]["text"];
	
	//$groupSKID = "-1001761664791";
	$groupSKID = "988362621";
	//$GLOBALS["groupSKID"] = "-1001761664791";
	$GLOBALS["groupSKID"] = "988362621";

	// pattern data high most allow
	$GLOBALS["patternDataHigher"] = array(
		"pH" => 9,
		"TSS" => 50,
		"COD" => 75,
		"TP" => 4,
		"TN" => 20,
		"Color" => 5,
		"Nhiệt độ" => 40
	);

	// file_get_contents($path."/sendmessage?chat_id=". $groupSKID ."&text=Chào a sung");
	// die();

	date_default_timezone_set("Asia/Ho_Chi_Minh");
	

	$htmlSensor = $htmlLuuluong = "";
	// read file data
	if (file_exists("files/sensorData.txt")) {
		$htmlSensor = file_get_contents('files/sensorData.txt');
	}

	if (file_exists("files/luuLuong.txt")) {
		$htmlLuuluong = file_get_contents("files/luuLuong.txt");
	}

	// command from group chat
	switch ($message) {
		case '/ha':
			file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Bạn cười gì vậy?".$chatId);
			break;
	    case '/sung':
	        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Chào sung");
	        break;
	    case '/data':
	        file_get_contents($path."/sendmessage?chat_id=".$chatId."&parse_mode=HTML&text=".urlencode("$htmlSensor \n\n$htmlLuuluong"));
	        break;
        case '/warning':
        	$txtWarning = file_get_contents('files/warning.txt');
        	if ($txtWarning == "") {
        		$txtWarning = "không có dữ liệu";
        	}
	        file_get_contents($path."/sendmessage?chat_id=".$chatId."&parse_mode=HTML&text=".$txtWarning);
	        break;
	    exit;
	}


	if (isset($_GET['test'])) {
		file_get_contents($path."/sendmessage?chat_id=". $chatId ."&text=Chào " .  $_GET["test"]);
	}
	if(isset($_REQUEST["isDisconnected"])) {
		file_put_contents('files/disconnected.txt','disconnected',FILE_APPEND);
		file_get_contents($path."/sendmessage?chat_id=". $chatId ."&text=Disconnected");
	}
	if (isset($_REQUEST["llHienTai"])) {
		echo "ghi file luu luong";
		
		try {
			file_put_contents( 'files/luuLuong.txt', bindLuuLuong($path));
		} catch (Exception $e) {
			echo $e;
		}
		
	}
	if (isset($_REQUEST["sensorName"])) {
		echo "ghi file data sensor";
		try {
			
			file_put_contents( 'files/sensorData.txt', bindDataSensor($path));	
		} catch (Exception $e) {
			
		}
	}
	//binDataSensor($path);
	function bindDataSensor($path){
		// convert array to string and concat them
		$sensorName = explode("-", $_REQUEST["sensorName"]);
		$sensorValues = explode("-", $_REQUEST["sensorValues"]);
		//$sensorName = explode("-", "Flow-TN-TP-Color-pH-TSS-COD");
		//$sensorValues = explode("-", "51.2-7.2-0.002-2-0.433-8.923-1");
		//$sensorValues = explode("-", "51.2-7.2-17.3-19-0.433-8.923-1");
		$html = "";
		$sensorHigher = "";
		$TPerr = "";
		

		foreach ($sensorName as $key => $value) {
			$html .= $value . "=". $sensorValues[$key] . "\n";

			// send warning to group if sensor value too high
			if (isset($GLOBALS["patternDataHigher"][$value]) && $sensorValues[$key] > $GLOBALS["patternDataHigher"][$value]) {
				$sensorHigher .= "<b>".$value." vượt ngưỡng</b> \nGiá trị hiện tại: ". $sensorValues[$key] ."\n (".date("H:i:s d/m/Y").") \n  \n";
			}

			// error sensor TP = 0.002
			if ($value == "TP" && $sensorValues[$key] == "0.002") {
				$sensorHigher .= "Hết nước cất hoặc gặp sự cố rồi! (TP=0.002)";
			}
		}

		if ($sensorHigher == "") {
			file_put_contents("files/warning.txt", "");
		}

		if ($sensorHigher != "" && file_get_contents("files/warning.txt") == "") {
			$txt = urlencode($sensorHigher);
			file_get_contents($path."/sendmessage?chat_id=" . $GLOBALS["groupSKID"] . "&parse_mode=HTML&text=". $txt . $a); //send to tele
			file_put_contents("files/warning.txt", $sensorHigher);
		}
		return $html;
	}

	function bindLuuLuong($path) {
		//create string message from request
		$html = "Đang xử lý : ". $_REQUEST["llHienTai"] . "m3 \n";
		$html .= "Lưu lượng vào đã xử lý hôm nay: " . $_REQUEST["llXuLyHienTai"]  . "m3 \n";
		$html .= "Tổng ll vào hôm qua: " . $_REQUEST["llXuLy1Ngay"]  . "m3 \n";
		$html .= "<code><b>Time: " . date("H:i:s d/m/Y") . "</b></code>"; 

		return $html;
		
	}

	
?>
