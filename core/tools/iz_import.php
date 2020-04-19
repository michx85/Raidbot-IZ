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
    // echo "Z".$row->gym_name." | ".$row->img_url." | ".$row->lat." | ".$row->lon." | ".$row->ex_gym."<br>";
    if($row->ex_gym == 1 OR $dat->ex == 1)
      $dat->ex = 1;
    else {
      $dat->ex = 0;
    }

    if($row->gym_name != "")
      $sql = "UPDATE gyms SET name = '".$dat->name."', lat = '".$dat->lat."', lon = '".$dat->lon."', ex_gym = ".$dat->ex.", img_url = '".$dat->image."' WHERE gym_id = '".$dat->portal_id."'";
    else {
      $sql = "INSERT INTO `gyms` (`id`, `lat`, `lon`, `address`, `gym_name`, `ex_gym`, `show_gym`, `gym_note`, `gym_id`, `img_url`) VALUES (NULL, '".$dat->lat."', '".$dat->lon."', NULL, '".$dat->name."', ".$dat->ex.", 1, NULL, '".$dat->portal_id."', '".$dat->image."');";
    }
    error_log($sql);
    $db->query($sql);
}


 ?>
