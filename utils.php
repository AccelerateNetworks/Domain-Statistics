<?php
function do_sql($db, $query, $args=array(), $affected=false) {
  $statement = $db->prepare(check_sql($query));
  if($statement) {
    $result = $statement->execute($args);
    if($result) {
      $out = NULL;
      if($affected) {
        $out = $statement->rowCount();
      } else {
        $out = [];
        while($row = $statement->fetch()) {
          $out[] = $row;
        }
      }
      return $out;
    } else {
      die("Failed to execute SQL statement <code>$query</code>! SQLSTATE: ".$statement->errorInfo()[0].", <b><code>Error ".$statement->errorInfo()[1].": ".$statement->errorInfo()[2]."</code></b>");
    }
  } else {
    die("Failed to prepare the SQL statement <code>$query</code>! <b><code>".$db->errorInfo()[2]."</code></b>");
  }
}

function sanatize_cdr($cdr) {
  $cdr = json_decode($cdr, true);

  $blacklist = array('sip_from_uri', 'sip_from_host', 'sip_call_id', 'sip_local_network_addr',
                     'sip_network_ip', 'sip_received_ip', 'sip_invite_record_route', 'sip_full_via',
                     'sip_full_from', 'sip_full_to', 'sip_req_uri', 'sip_req_host', 'sip_to_uri',
                     'sip_to_host', 'sip_contact_uri', 'sip_contact_host', 'sip_via_host',
                     'switch_r_sdp', 'local_media_ip', 'advertised_media_ip', 'remote_media_ip',
                      'channel_name');

  foreach($blacklist as $key) {
    unset($cdr['variables'][$key]);
  }

  foreach($cdr['callflow'] as $i => $callflow) {
    unset($cdr['callflow'][$i]['caller_profile']['network_addr']);
    unset($cdr['callflow'][$i]['caller_profile']['chan_name']);
    foreach($cdr['callflow'][$i]['caller_profile']['origination']['origination_caller_profile'] as $j => $origination_caller_profile) {
      unset($cdr['callflow'][$i]['caller_profile']['origination']['origination_caller_profile'][$j]['network_addr']);
      unset($cdr['callflow'][$i]['caller_profile']['origination']['origination_caller_profile'][$j]['chan_name']);
    }
  }
  return $cdr;
}
