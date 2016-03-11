<?php
/*
* Options page for gj-redirect.
*/

if ('gj-redirect-options.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
  die();
}

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'gj_redirect_redirects';

?>

<h2 class="nav-tab-wrapper">
  <a href="?page=gj_redirect&tab=redirects" class="nav-tab <?php echo $active_tab == 'gj_redirect_redirects' ? 'nav-tab-active' : ''; ?>">Redirects</a>
  <a href="?page=gj_redirect&tab=settings" class="nav-tab <?php echo $active_tab == 'gj_redirect_settings' ? 'nav-tab-active' : ''; ?>">Settings</a>
</h2>

<div class="wrap"><?php

  if( $active_tab == 'redirects' ) {
    if (file_exists(__DIR__. '/redirects.php')) {
      include_once(__DIR__. '/redirects.php');
    }
    else {
      echo 'Redirects file is missing';  
    }
  }

  if( $active_tab == 'settings' ) {
    if (file_exists(__DIR__. '/settings.php')) {
      include_once(__DIR__. '/settings.php');
    }
    else {
      echo 'Settings file is missing';  
    }
  } ?>

</div>
