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
    } else {
      $result = 'You must set deletes prior to calling this function.';
    }

    $this->deletes = false;

    if($result > 0) {
      $result = true;
    } else {
      $result = false;
    }

    return $result;

  }

  function deleteAllRedirects() {
    global $wpdb;

    $table_name = $wpdb->prefix . $this->table;

    $result = $wpdb->query(
      "TRUNCATE TABLE $table_name"
      );

    return $result;

  }

  function createRedirects($createItems) {

    global $wpdb;
    $table_name = $wpdb->prefix . $this->table;

    $keys = array('id','mode','url','redirect','status');

    foreach ($createItems as $array) {
      $addItems[] = array_combine($keys, $array);
    }

    foreach($addItems as $key=>$value) {

      $result[] = $wpdb->insert( $table_name, array(
        'url' => $value['url'],
        'redirect' => $value['redirect'],
        'status' =>$value['status']
        ) );

    }

    return $result;

  }


}
