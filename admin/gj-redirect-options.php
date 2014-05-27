<?php
/*
* Options page for gj-redirect.
*/

if ('gj-redirect-options.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
  die();
}

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'gj_redirect_settings';

?>

<h2 class="nav-tab-wrapper">
  <a href="?page=gj_redirect&tab=gj_redirect_settings" class="nav-tab <?php echo $active_tab == 'gj_redirect_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
  <a href="?page=gj_redirect&tab=gj_redirect_redirects&paged=1" class="nav-tab <?php echo $active_tab == 'gj_redirect_redirects' ? 'nav-tab-active' : ''; ?>">Redirects</a>
  <a href="?page=gj_redirect&tab=gj_redirect_help" class="nav-tab <?php echo $active_tab == 'gj_redirect_help' ? 'nav-tab-active' : ''; ?>">Help</a>
</h2>

<div class="wrap"><?php

  if( $active_tab == 'gj_redirect_settings' ) {
    if (file_exists(__DIR__. '/gj-redirect-settings.php')) {
      include_once(__DIR__. '/gj-redirect-settings.php');
    }
    else {
      echo 'Settings file is missing';  
    }
  }

  if( $active_tab == 'gj_redirect_redirects' ) {
    if (file_exists(__DIR__. '/gj-redirect-redirects.php')) {
      include_once(__DIR__. '/gj-redirect-redirects.php');
    }
    else {
      echo 'Redirects file is missing';  
    }
  }

  if( $active_tab == 'gj_redirect_help' ) {
    if (file_exists(__DIR__. '/gj-redirect-help.php')) {
      include_once(__DIR__. '/gj-redirect-help.php');
    }
    else {
      echo 'Help file is missing';  
    }
  } ?>

</div>
