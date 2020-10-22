<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Kivasti kokeillaan</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />

</head>

<body>
<?php 


function getUserLocation() {
	include 'keyInformation.php';
		
	$data = json_decode(file_get_contents('https://ip-api.io/api/json?api_key=' . $apiKey));
	

	$userCity = $data->city;

	  
echo 'You are located in: ' . $userCity; 
echo "<br/>"; 

$CurrentWeather = json_decode(file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=' . $userCity . '&appid=' . $apiKeyOWM . '&units=metric'));

echo 'The current temperature of ' . $userCity . ' is ' . $CurrentWeather->main->temp . 'C';

}


getUserLocation();
?>	
</body>

</html>
