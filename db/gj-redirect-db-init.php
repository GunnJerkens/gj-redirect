<?php

class gjRedirectDatabase {

  static function initDB() {

    global $wpdb;
    $table_name = $wpdb->prefix . "gj_redirects";

    // Redirects Table
    $redirects = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
       url VARCHAR(255) NOT NULL,
       redirect VARCHAR(255) NOT NULL,
       status VARCHAR(255) NOT NULL,
       options VARCHAR(255) NOT NULL,
       PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($redirects);

    add_option( "gj_redirect_db_version", "0.3" );
  }

}
new gjRedirectDatabase();
