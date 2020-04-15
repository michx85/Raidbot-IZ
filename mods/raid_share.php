<?php
// Write to log.
debug_log('raid_share()');

// For debug.
//debug_log($update);
//debug_log($data);

// Access check.
raid_access_check($update, $data, 'share');

// Get raid id.
$id = $data['id'];

// Get chat id.
$chat = $data['arg'];

// Get raid data.
$raid = get_raid($id);

// Get text and keys.
$text = show_raid_poll($raid);
$keys = keys_vote($raid);

// Send location.
if (RAID_LOCATION == true) {
    // Send location.
    $msg_text = !empty($raid['address']) ? ($raid['address'] . ', ' . substr(strtoupper(BOT_ID), 0, 1) . '-ID = ' . $raid['id']) : ($raid['pokemon'] . ', ' . $raid['id']); // DO NOT REMOVE "ID ="--> NEEDED FOR CLEANUP PREPARATION!
    $loc = send_venue($chat, $raid['lat'], $raid['lon'], '', $msg_text);

    // Write to log.
    debug_log('location:');
    debug_log($loc);
}

// Telegram JSON array.
$tg_json = array();

// Raid picture
if(RAID_PICTURE == true) {
    $picture_url = RAID_PICTURE_URL . "?pokemon=" . $raid['pokemon'] . "&raid=". $id;
    debug_log('PictureUrl: ' . $picture_url);
}

// Send the message.
$raid_picture_hide_level = explode(",",RAID_PICTURE_HIDE_LEVEL);
$raid_picture_hide_pokemon = explode(",",RAID_PICTURE_HIDE_POKEMON);

$raid_pokemon = $raid['pokemon'];
$raid_pokemon_id = explode('-',$raid_pokemon)[0];
$raid_level = get_raid_level($raid_pokemon);

if(RAID_PICTURE == true && !in_array($raid_level, $raid_picture_hide_level) && !in_array($raid_pokemon, $raid_picture_hide_pokemon) && !in_array($raid_pokemon_id, $raid_picture_hide_pokemon)) {
    $tg_json[] = send_photo($chat, $picture_url, $text['short'], $keys, ['reply_to_message_id' => $chat, 'disable_web_page_preview' => 'true'], true);
} else {
    $tg_json[] = send_message($chat, $text['full'], $keys, ['reply_to_message_id' => $chat, 'disable_web_page_preview' => 'true'], true);
}

// Set callback keys and message
$callback_msg = getTranslation('successfully_shared');
$callback_keys = [];

// Answer callback.
$tg_json[] = answerCallbackQuery($update['callback_query']['id'], $callback_msg, true);

// Edit message.
$tg_json[] = edit_message($update, $callback_msg, $callback_keys, false, true);

// Telegram multicurl request.
curl_json_multi_request($tg_json);

// Exit.
exit();
