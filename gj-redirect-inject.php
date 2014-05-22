<?php

class gjRedirectInject {

  function __construct() {
    add_action('template_redirect', array(&$this, 'gj_redirect_inject'));
    add_action('wp_head', array(&$this, 'doStruff'));
  }

  function gj_redirect_inject() {

    if($_SERVER['REQUEST_URI'] === '/test-injection') {

      wp_redirect( 'http://brooksc.dev', 301 );
      exit;

    }

  }

  // TODO
  // 1) Grab all the data from the redirect table in mysql (use db functions in /db/)
  // 2) Create a function that will return a relative path string
  // 3) ???
  // 4) Profit?

}
new gjRedirectInject();
