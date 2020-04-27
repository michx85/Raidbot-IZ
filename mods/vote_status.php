<?php
// Write to log.
debug_log('vote_status()');

// For debug.
//debug_log($update);
//debug_log($data);

// Check if the user has voted for this raid before.
$rs = my_query(
    "
    SELECT    user_id, attend_time, remote
    FROM      attendance
      WHERE   raid_id = {$data['id']}
        AND   user_id = {$update['callback_query']['from']['id']}
    "
);

// Get the answer.
$raidanswer = $rs->fetch_assoc();

// Write to log.
debug_log($raidanswer);

// Make sure user has voted before.
if (!empty($raidanswer)) {
    // Get status to update
    $status = $data['arg'];
    alarm($data['id'],$update['callback_query']['from']['id'],'status',$status);
    // Update attendance.
    if($status == 'alarm') {
        // Enable / Disable alarm
        my_query(
        "
        UPDATE attendance
        SET    alarm = CASE
               WHEN alarm = '0' THEN '1'
               ELSE '0'
               END
	WHERE  raid_id = {$data['id']}
	AND    user_id = {$update['callback_query']['from']['id']}
        "
        );

        // request gym name
        $request = my_query("SELECT * FROM raids as r left join gyms as g on r.gym_id = g.id WHERE r.id = {$data['id']}");
        $answer = $request->fetch_assoc();
        $gymname = '<b>' . $answer['gym_name'] . '</b>';
                $raidtimes = str_replace(CR, '', str_replace(' ', '', get_raid_times($answer, false, false, true)));

        // Get the new value
        $rs = my_query(
        "
        SELECT    alarm
        FROM      attendance
		  WHERE   raid_id = {$data['id']}
	        AND   user_id = {$update['callback_query']['from']['id']}
        "
        );
        $answer = $rs->fetch_assoc();

        // Enable alerts message.
        if($answer['alarm']) {
            $msg_text = EMOJI_ALARM . SP . '<b>' . getTranslation('alert_updates_on') . '</b>' . CR;
        // Disable alerts message.
        } else {
            $msg_text = EMOJI_NO_ALARM . SP . '<b>' . getTranslation('alert_no_updates') . '</b>' . CR;
	}
        $msg_text .= EMOJI_HERE . SP . $gymname . SP . '(' . $raidtimes . ')';
        sendmessage($update['callback_query']['from']['id'], $msg_text);
    } else if($status == 'remote'){


      if($raidanswer['remote'] == 0)
        checkRemote($update['callback_query']['from']['id'], $data['id'], $raidanswer['attend_time'],$update['callback_query']['id']);

        // All other status-updates are using the short query
        my_query(
	"
        UPDATE  attendance
        SET
                raid_done = 0,
                cancel = 0,
                $status = CASE
                      WHEN $status = '0' THEN '1'
                      ELSE '0'
                      END
        WHERE   raid_id = {$data['id']}
        AND     user_id = {$update['callback_query']['from']['id']}
        "
        );
      } else if($status == 'arrived'){
          // All other status-updates are using the short query
          my_query(
    "
          UPDATE  attendance
          SET
                  raid_done = 0,
                  cancel = 0,
                  late = 0,
                  $status = CASE
                        WHEN $status = '0' THEN '1'
                        ELSE '0'
                        END
          WHERE   raid_id = {$data['id']}
          AND     user_id = {$update['callback_query']['from']['id']}
          "
          );
        } else if($status == 'late'){
            // All other status-updates are using the short query
            my_query(
      "
            UPDATE  attendance
            SET
                    raid_done = 0,
                    cancel = 0,
                    arrived = 0,
                    $status = CASE
                          WHEN $status = '0' THEN '1'
                          ELSE '0'
                          END
            WHERE   raid_id = {$data['id']}
            AND     user_id = {$update['callback_query']['from']['id']}
            "
            );
    } else {
        // All other status-updates are using the short query
        my_query(
	"
        UPDATE  attendance
        SET     arrived = 0,
                raid_done = 0,
                cancel = 0,
                late = 0,
                $status = 1
        WHERE   raid_id = {$data['id']}
        AND     user_id = {$update['callback_query']['from']['id']}
        "
        );
    }

   // Send vote response.
   if(RAID_PICTURE == true) {
       send_response_vote($update, $data,false,false);
    } else {
       send_response_vote($update, $data);
    }
} else {
    // Send vote time first.
    send_vote_time_first($update);
}

exit();
