<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>The Magical Weather Machine!</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />

</head>

<body>
<?php 
date_default_timezone_set("Europe/Helsinki");

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
	
	echo 'You are located in: ' . $userCity . '<br><img src ="' . $userFlag . '"width="100" height="100"><br/>'; 
	
	echo '<script> console.log("' . $userCity . '"); </script>';
	
	
	
	#Gets locations weather information from openweathermap.org as JSON
	$currentWeather = json_decode(file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=' . $userCity . '&appid=' . $apiKeyOWM . '&units=metric'));
	$currentTemperature = $currentWeather->main->temp;
	
	echo 'The current temperature of ' . $userCity . ' is ' . $currentTemperature . 'C but it feels like ' . $currentWeather->main->feels_like . 'C.';
	
	#Gets uimaranta data from uimarantadata as JSON
	$uimaData = json_decode(file_get_contents('https://iot.fvh.fi/opendata/uiras/70B3D57050004C07_v1.json'));
	
	$sompaTemperature = end($uimaData->data)->temp_water;
	$sompaAir = end($uimaData->data)->temp_air;
	
	
	
	$start_date = new DateTime(end($uimaData->data)->time);
	$since_start = $start_date->diff(new DateTime());
	$lat = $uimaData->meta->lat;
	$lon = $uimaData->meta->lon;
	
	
	
	echo '<br><img src ="https://upload.wikimedia.org/wikipedia/commons/5/53/Sompasauna_November_2018_01.jpg" width="400" height="300">';
	echo '<br><br>Temperature of water in the famous Sompasauna beach is ' . $sompaTemperature . 'C. Air is ' . $sompaAir . 'C. This was ' . $since_start->i . ' minutes ago.<br>';
	
	
	if ($sompaTemperature > $currentTemperature) {
		$difference = $sompaTemperature - $currentTemperature;
		echo '<br>You should go to Sompasauna, it is ' . $difference . 'c warmer than ' . $userCity . '.';
	}
	
	if ($sompaTemperature == $currentTemperature) {
		echo '<br>Its as hot in ' . $userCity . ' as it is in Sompasauna! Why arent you in SompaSauna?';
	}
	
	if ($sompaTemperature < $currentTemperature) {
		$difference = $currentTemperature - $sompaTemperature;
		echo '<br>' . $userCity . ' may be ' . $difference . 'C warmer than Sompasauna but you should still go there.';
	} 
	
	
	echo '<br>Sompasauna is located at lat:' . $lat . ' lon:' . $lon . ' in Helsinki, Finland.';
	
}

tellWeather();
?>	
</body>

</html>
