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
require_once(plugin_dir_path(__FILE__).'db/gj-redirect-db.php');

register_activation_hook(__FILE__, array('gjRedirectDatabase', 'initDB'));

class gjRedirect {

  function __construct() {
    add_action('admin_menu', array(&$this,'gj_redirect_admin_actions'));
  }

  function gj_redirect_admin_actions() {
    add_users_page("users.php", "GJ Redirect", 'administrator', "gj_redirect", "gj_redirect_admin_options");
  }

  function gj_redirect_admin_options() {
    include ('admin/gj-redirect-options.php');
  }

}
new gjRedirect();
