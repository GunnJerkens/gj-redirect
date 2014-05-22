<?php

class gjRedirectDB {

  public function getRedirects($type='OBJECT', $where='1=1') {
    global $wpdb;

    $table_name = $wpdb->prefix . "gj_redirects";
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

}
global $gjRedirectDB;
$gjRedirectDB = new gjRedirectDB;

