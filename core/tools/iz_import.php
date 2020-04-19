<?php

$url = "https://lanched.ru/PortalGet/searchPortals.php?query=".$row->portal_id;
		// echo $url;

$homepage = file_get_contents($url);

$data = json_decode($homepage);

foreach($data AS $dat)
{
    print_r($dat);
    echo "---<br>";
}


 ?>
