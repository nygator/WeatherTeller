<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>The Magical Weather Machine!</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />

</head>

<body>
<?php 


function tellWeather() {
	include 'keyInformation.php';
	
	
	$ip = $_SERVER['HTTP_CLIENT_IP'] ? $_SERVER['HTTP_CLIENT_IP'] : ($_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);

	
	#Gets users location information from ip-api.io	as JSON
	$data = json_decode(file_get_contents('https://ip-api.io/json/' . $ip . '?api_key=' . $apiKey));
	$userFlag = $data->flagUrl;
	
	if ($data->city == null) {
		$userCity = 'Helsinki';
	}
	
	else{ 
	$userCity = $data->city;
	}
	
	#Extra checks if the person is using a proxy
	$suspect = $data->suspiciousFactors->isSpam;
	if ($suspect == true) {
		echo 'https://ip-api.io/ thinks you are a spammer'; }
	$suspect = $data->suspiciousFactors->isProxy;
	if ($suspect == true) {
		echo 'https://ip-api.io/ thinks you are using a proxy'; }
	$suspect = $data->suspiciousFactors->isTorNode;
	if ($suspect == true) { 
		echo 'https://ip-api.io/ thinks you are using TOR'; }
	
	echo 'You are located in: ' . $userCity . '<img src ="' . $userFlag . '"width="100" height="100"><br/>'; 
	
	echo '<script> console.log("' . $userCity . '"); </script>';
	

	
	#Gets locations weather information from openweathermap.org as JSON
	$currentWeather = json_decode(file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=' . $userCity . '&appid=' . $apiKeyOWM . '&units=metric'));
	
	echo 'The current temperature of ' . $userCity . ' is ' . $currentWeather->main->temp . 'C but it feels like ' . $currentWeather->main->feels_like . 'C.';
}

tellWeather();
?>	
</body>

</html>
