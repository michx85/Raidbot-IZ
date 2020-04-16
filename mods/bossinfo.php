<?php

debug_log('trainer_team()');

bot_access_check($update, 'start');

$pokemon = $data['arg'];

$user_id = $update['callback_query']['from']['id'];

send_photo($user_id, IMAGE_PATH.$pokemon.'.png');


 ?>
