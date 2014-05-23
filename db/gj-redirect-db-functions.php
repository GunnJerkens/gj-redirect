<?php

class gjRedirectDB {

  var $table = "gj_redirects";

  function getRedirects($type='OBJECT', $where='1=1') {

    global $wpdb;

    $table_name = $wpdb->prefix . $this->table;

    $query = $wpdb->get_results(
      "
      SELECT *
      FROM $table_name
      WHERE $where
      ",
      $type
    );

    return $query;
  }

  private $deletes;

  function setDeletes($id) {
    $this->deletes = $id;
  }

  function getValue() {
    return $this->deletes;
  }


  function deleteRedirects() {

    global $wpdb;

    $table_name = $wpdb->prefix . $this->table;

    if ($this->deletes) {
      $result = $wpdb->query(
        $wpdb->prepare(
          "
          DELETE FROM $table_name 
          WHERE id = %d
          ",
          $this->deletes
        )
      );
    } //  else {
    //   $wpdb->query(
    //     "TRUNCATE TABLE $table_name"
    //    );
    // }

    if($result > 0) {
      $result = true;
    } else {
      $result = false;
    }

    return $result;


  }


}
