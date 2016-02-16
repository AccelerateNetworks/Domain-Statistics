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
