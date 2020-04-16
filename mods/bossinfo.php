<?php

debug_log('trainer_team()');

bot_access_check($update, 'start');

$pokemon = $data['arg'];

$user_id = $update['callback_query']['from']['id'];


$file= "https://raidbot-test.piraidgruppe.de/index.php=bossingo=true&pic=".$pokemon;
error_log("DATEI: ".$file);
$p = send_photo($user_id, $file, '...', array(), ['disable_web_page_preview' => 'true']);
error_log($p);



 ?>
