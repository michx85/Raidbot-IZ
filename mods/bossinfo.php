<?php

debug_log('trainer_team()');

bot_access_check($update, 'start');

$pokemon = $data['arg'];

$user_id = $update['callback_query']['from']['id'];


$file= IMAGE_PATH.$pokemon.'.png';
error_log($file);
send_photo($user_id, $file, '...', array(), ['disable_web_page_preview' => 'true']);


 ?>
