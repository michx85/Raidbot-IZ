<?php

$url = "https://raidsiz.lima-city.de/m2t/gyms_iz.php";
		// echo $url;

$homepage = file_get_contents($url);

$data = json_decode($homepage);

foreach($data AS $dat)
{
    print_r($dat);
    echo "---<br>";
}


 ?>
