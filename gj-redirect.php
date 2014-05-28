<?php
/*
Plugin Name: GJ Redirect
Plugin URI: http://gunnjerkens.com
Description: Adds a meta box to pages allowing you to 301 redirect pages to the home page.
Version: 0.3
Author: Gunn|Jerkens
Author URI: http://gunnjerkens.com
*/

require_once(plugin_dir_path(__FILE__).'gj-redirect-metaboxes.php');
require_once(plugin_dir_path(__FILE__).'gj-redirect-inject.php');
require_once(plugin_dir_path(__FILE__).'admin/gj-redirect-admin-functions.php');
require_once(plugin_dir_path(__FILE__).'db/gj-redirect-db-functions.php');

class gjRedirect {

  function __construct() {
    add_action('admin_menu', array(&$this,'gj_redirect_admin_actions'));
    add_action('admin_enqueue_scripts', array(&$this, 'gj_redirect_admin_scripts'));
    register_activation_hook(__FILE__, array($this, 'create_table'));
  }

  function gj_redirect_admin_actions() {
    add_options_page( 'GJ Redirect', 'GJ Redirect', 'administrator', 'gj_redirect', array(&$this,'gj_redirect_admin_options'));
  }

  function gj_redirect_admin_options() {
    include('admin/gj-redirect-options.php');
  }

  function gj_redirect_admin_scripts() {
    if(is_admin()) {
      wp_enqueue_script('gj_redirect_admin_js', plugin_dir_url(__FILE__) . 'js/gj-redirect-admin.js', false, '0.3');
      wp_enqueue_style('gj_redirect_admin_css', plugin_dir_url(__FILE__) . 'css/gj-redirect-admin.css');
    }
  }

  static function create_table() {

    add_option( "gj_redirect_db_version", "0.3" );

    global $wpdb;
    $table_name = $wpdb->prefix . "gj_redirects";

    // Redirects Table
    $redirects = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
       url VARCHAR(255) NOT NULL,
       redirect VARCHAR(255) NOT NULL,
       status VARCHAR(255) NOT NULL,
       scope VARCHAR(255) NOT NULL,
       PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($redirects);

    activate_plugin('gj-redirect.php');

  }

}
new gjRedirect();
