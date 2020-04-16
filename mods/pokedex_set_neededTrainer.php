<?php
// Write to log.
debug_log('pokedex_set_neededTrainer()');

// For debug.
//debug_log($update);
//debug_log($data);

// Check access.
bot_access_check($update, 'pokedex');

// Set the id.
$pokedex_id = $data['id'];

// Get the action, old and new neededTrainer
$arg = $data['arg'];
$data = explode("-", $arg);
$action = $data[0];
$new_neededTrainer = $data[1];
$old_neededTrainer = get_pokemon_neededTrainer($pokedex_id);

// Log
debug_log('Action: ' . $action);
debug_log('Old neededTrainer: ' . $old_neededTrainer);
debug_log('New neededTrainer: ' . $new_neededTrainer);

// Split pokedex_id and form
$dex_id_form = explode('-',$pokedex_id);
$dex_id = $dex_id_form[0];
$dex_form = $dex_id_form[1];

// Add weather
if($action == 'add') {
    // Init empty keys array.
    $keys = [];

    // Get the keys.
    $keys[] = array(
        'text'          => 1,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-1'
    );
    $keys[] = array(
        'text'          => 2,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-2'
    );
    $keys[] = array(
        'text'          => 3,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-3'
    );
    $keys[] = array(
        'text'          => 4,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-4'
    );
    $keys[] = array(
        'text'          => 5,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-5'
    );
    $keys[] = array(
        'text'          => 6,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-6'
    );
    $keys[] = array(
        'text'          => 7,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-7'
    );
    $keys[] = array(
        'text'          => 8,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-8'
    );
    $keys[] = array(
        'text'          => 0,
        'callback_data' => $pokedex_id . ':pokedex_set_neededTrainer:save-0'
    );

    $keys = inline_key_array($keys, 3);
    // Build callback message string.
    $callback_response = 'OK';

    // Back and abort.
    $keys[] = [
        [
            'text'          => getTranslation('back'),
            'callback_data' => $pokedex_id . ':pokedex_edit_pokemon:0'
        ],
        [
            'text'          => getTranslation('abort'),
            'callback_data' => '0:exit:0'
        ]
    ];

    // Set the message.
    $msg = getTranslation('raid_boss') . ': <b>' . get_local_pokemon_name($pokedex_id) . ' (#' . $dex_id . ')</b>' . CR;
    $msg .= 'ALT: Machbar mit '.$old_neededTrainer.' suboptimalen LVL25 Kontern/Wetter' . CR . CR;
    $msg .= 'NEU: Machbar mit '.$new_neededTrainer.' suboptimalen LVL25 Kontern/Wetter' . CR . CR;

// Save weather to database
} else if($action == 'save') {
    // Update weather of pokemon.
    $rs = my_query(
            "
            UPDATE    pokemon
            SET       neededTrainer = {$new_neededTrainer}
            WHERE     pokedex_id = {$dex_id}
            AND       pokemon_form = '{$dex_form}'
            "
        );

    // Init empty keys array.
    $keys = [];

    // Back to pokemon and done keys.
    $keys = [
        [
            [
                'text'          => getTranslation('back') . ' (' . get_local_pokemon_name($pokedex_id) . ')',
                'callback_data' => $pokedex_id . ':pokedex_edit_pokemon:0'
            ],
            [
                'text'          => getTranslation('done'),
                'callback_data' => '0:exit:1'
            ]
        ]
    ];

    // Build callback message string.
    $callback_response = getTranslation('pokemon_saved') . ' ' . get_local_pokemon_name($pokedex_id);

    // Set the message.
    $msg = getTranslation('pokemon_saved') . CR;
    $msg .= '<b>' . get_local_pokemon_name($pokedex_id) . ' (#' . $dex_id . ')</b>' . CR . CR;
    $msg .= 'NEU: Machbar mit '.$new_neededTrainer.' suboptimalen LVL25 Kontern/Wetter' . CR;
}

// Telegram JSON array.
$tg_json = array();

// Answer callback.
$tg_json[] = answerCallbackQuery($update['callback_query']['id'], $callback_response, true);

// Edit message.
$tg_json[] = edit_message($update, $msg, $keys, false, true);

// Telegram multicurl request.
curl_json_multi_request($tg_json);

// Exit.
exit();
