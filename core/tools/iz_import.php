<?php


include_once(dirname(__DIR__). '/bot/requirements.php');

// Datenbankverbindung
if($db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME))
{

	$db->set_charset('utf8mb4');

}
else
{
	logging("Datenbank-Verbindung gescheitert.","mysql_connect");
	die();
}


$url = "https://raidsiz.lima-city.de/m2t/gyms_iz.php";
		// echo $url;

$homepage = file_get_contents($url);

$data = json_decode($homepage);

foreach($data AS $dat)
{
    echo "X".$dat->name." | ".$dat->image." | ".$dat->lat." | ".$dat->lon." | ".$dat->ex."<br>";
    $res = $db->query("SELECT * FROM gyms WHERE gym_id = '".$dat->portal_id."'");
    $row = $res->fetch_object();
    echo "Z".$row->name." | ".$row->img_url." | ".$dat->lat." | ".$dat->lon." | ".$dat->ex_gym."<br>";

}


 ?>
