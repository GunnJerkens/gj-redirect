<?php

class gjRedirectDB {

  function wpdb() {

    global $wpdb;

    return $wpdb;

  }

  function table() {

    $wpdb = $this->wpdb();
    $table = $wpdb->prefix.'gj_redirects';

    return $table;

  }

  function countRows($type='OBJECT') {

    $wpdb = $this->wpdb();
    $table_name = $this->table();

    $count = $wpdb->get_results(
      "
      SELECT COUNT(*) 
      FROM $table_name
      "
    );

    return $count;

  }

  function getRedirects($lowerLimit, $upperLimit, $where='1=1', $type='OBJECT') {

    $wpdb = $this->wpdb();
    $table_name = $this->table();

    $query = $wpdb->get_results(
      "
      SELECT *
      FROM $table_name
      LIMIT $lowerLimit, $upperLimit
      ",
      $type
    );

    return $query;
  }

  function matchRedirects($url, $type='OBJECT') {

    $wpdb = $this->wpdb();
    $table_name = $this->table();
    $where = "url = '$url'";

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

    $wpdb = $this->wpdb();
    $table_name = $this->table();

    if($this->deletes) {

      foreach($this->deletes as $id) {

        $result = $wpdb->query(
          $wpdb->prepare(
            "
            DELETE FROM $table_name 
            WHERE id = %d
            ",
            $id
          )
        );

      }

    } else {

      $result = 'You must set deletes prior to calling this function.';

    }

    $this->deletes = false;

    $result > 0 ? $result = true : $result = false;

    return $result;

  }

  function deleteAllRedirects() {

    $wpdb = $this->wpdb();
    $table_name = $this->table();

    $result = $wpdb->query(
      "TRUNCATE TABLE $table_name"
      );

    return $result;

  }

  function createRedirects($createItems) {

    $wpdb = $this->wpdb();
    $table_name = $this->table();

    foreach($createItems as $key=>$value) {

      $result[] = $wpdb->insert(
        $table_name,
        array(
          'url' => $value['url'],
          'redirect' => $value['redirect'],
          'status' => $value['status'],
          'scope' => $value['scope']
        ) 
      );

    }

    return $result;

  }

  function updateRedirects($updateItems) {

    $wpdb = $this->wpdb();
    $table_name = $this->table();

    foreach($updateItems as $key=>$value) {

      $result[] = $wpdb->update(
        $table_name,
        array(
          'url' => $value['url'],
          'redirect' => $value['redirect'],
          'status' => $value['status'],
          'scope' => $value['scope']
        ),
        array('id' => $value['id'])
      );

    }

    return $result;

  }


}
