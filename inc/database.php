<?php

class gjRedirectDB
{

  /**
   * WordPress database connection object
   *
   * @var $wpdb object
   */
  private $wpdb;

  /**
   * Table where we store the data
   *
   * @var $table string
   */
  protected $table;

  /**
   * Array of ids to delete
   *
   * @var $deletes array
   */
  protected $deletes;

  /**
   * Class constructor
   *
   * @return void
   */
  function __construct()
  {
    global $wpdb;

    $this->wpdb  = $wpdb;
    $this->table = $wpdb->prefix.'gj_redirects';
  }

  /**
   * Returns a count of the number of redirects
   *
   * @return int
   */
  public function countRows($type='OBJECT')
  {
    return $this->wpdb->get_results("SELECT COUNT(*) FROM $this->table");
  }

  /**
   * Gets our redirects
   *
   * @param $offset
   * @param $length
   * @param $orderColumn string
   * @param $orderDirection string
   * @param $type string
   *
   * @return array
   */
  public function getRedirects($offset, $length, $orderColumn = 'id', $orderDirection = 'ASC', $type='OBJECT')
  {
    $sql = $this->wpdb->prepare(
      "SELECT * 
        FROM $this->table 
        WHERE 1 
        ORDER BY %s %s 
        LIMIT %d, %d",
        $orderColumn, $orderDirection, $offset, $length
    );
    return $this->wpdb->get_results($sql, $type);
  }

  /**
   * Matches redirects
   *
   * @param $url string
   * @param $path string
   * @param $type string
   *
   * @return array
   */
  public function matchRedirects($url, $path, $type='OBJECT')
  {
    $sql = $this->wpdb->prepare(
      "SELECT * 
        FROM $this->table
        WHERE (`url` = %s AND `scope`='exact') 
        OR (`url` = %s AND `scope`='ignorequery')",
        "$url", "$path"
    );

    return $this->wpdb->get_results($sql, $type);
  }

  /**
   * Sets the protected var deletes
   *
   * @param $id int
   *
   * @return void
   */
  public function setDeletes($id)
  {
    $this->deletes = $id;
  }

  /**
   * Deletes redirects
   *
   * @return bool
   */
  public function deleteRedirects()
  {
    if($this->deletes) {
      foreach($this->deletes as $id) {

        $sql = $this->wpdb->prepare(
          "DELETE FROM $this->table
            WHERE id = %d",
          $id
        );
        $result = $this->wpdb->query($sql);
      }
    }

    $this->deletes = false;

    return $result > 0 ? true :false;
  }

  /**
   * Delete all redirects, truncate the table
   *
   * @return void
   */
  public function deleteAllRedirects()
  {
    return $this->wpdb->query("TRUNCATE TABLE $this->table");
  }

  /**
   * Create redirect(s)
   *
   * @param $createItems array
   *
   * @return array
   */
  public function createRedirects($createItems)
  {
    $result = array();

    if(is_array($createItems)) {
      foreach($createItems as $key=>$value) {
        $result[] = $this->wpdb->insert(
          $this->table,
          array(
            'url'      => $value['url'],
            'redirect' => $value['redirect'],
            'status'   => $value['status'],
            'scope'    => $value['scope']
          ) 
        );
      }
    }

    return $result;
  }

  /**
   * Update redirect(s)
   *
   * @param $updateItems array
   *
   * @return array
   */
  public function updateRedirects($updateItems)
  {
    $result = array();

    if(is_array($updateItems)) {
      foreach($updateItems as $key=>$value) {
        $result[] = $this->wpdb->update(
          $this->table,
          array(
            'url'      => $value['url'],
            'redirect' => $value['redirect'],
            'status'   => $value['status'],
            'scope'    => $value['scope']
          ),
          array('id' => $value['id'])
        );
      }
    }

    return $result;
  }

}
