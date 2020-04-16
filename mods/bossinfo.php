<?php

debug_log('trainer_team()');

bot_access_check($update, 'start');

$pokemon = $data['arg'];

$user_id = $update['callback_query']['from']['id'];


$file= IMAGE_PATH.$pokemon.'.png';
error_log("DATEI: ".$file);
$p = send_photo($user_id, $file, '...', array(), ['disable_web_page_preview' => 'true']);
error_log($p)


$file= "https://www.novitas-bkk.de/_daten/fotos/startseitenbilder/sl_leistungen2020.jpg";
error_log("DATEI: ".$file);
$p = send_photo($user_id, $file, '...', array(), ['disable_web_page_preview' => 'true']);
error_log($p)


 ?>
