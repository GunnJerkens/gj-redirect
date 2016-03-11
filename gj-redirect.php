<?php
/*
Plugin Name: GJ Redirect
Plugin URI: http://gunnjerkens.com
Description: Intercepts and redirects defined pages during WP routing instead of using htaccess or other method.
Version: 0.3.0
Author: Gunn|Jerkens
Author URI: http://gunnjerkens.com
*/

require_once(plugin_dir_path(__FILE__).'inc/inject.php');
require_once(plugin_dir_path(__FILE__).'inc/admin.php');
require_once(plugin_dir_path(__FILE__).'inc/database.php');

class gjRedirect
{

  function __construct() {
    add_action('admin_menu', array(&$this,'gj_redirect_admin_actions'));
    add_action('admin_enqueue_scripts', array(&$this, 'gj_redirect_admin_scripts'));
    register_activation_hook(__FILE__, array($this, 'create_table'));
    update_option('gj_redirect_version', '0.3');
  }

  function gj_redirect_admin_actions() {
    add_options_page( 'GJ Redirect', 'GJ Redirect', 'administrator', 'gj_redirect', array(&$this,'gj_redirect_admin_options'));
  }

  function gj_redirect_admin_options() {
    include('admin/options.php');
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
new gjRedirectDB();
new gjRedirectInject();
