<?php

class gjRedirectInject {

  function __construct() {
    add_action('template_redirect', array(&$this, 'gj_redirect_inject'));
  }

  function gj_redirect_inject() {

    $url = $_SERVER['REQUEST_URI'];

    $get_gjRedirectDB = new gjRedirectDB;
    $matchResponse = $get_gjRedirectDB->matchRedirects($url);
    $matchResponse = $matchResponse[0];

    if($matchResponse != NULL && $matchResponse->status !== 'disabled' && $matchResponse->redirect !== "") {

      $httpVerify = stripos($matchResponse->redirect, 'http://');

      if($httpVerify !== false) {

        $redirect = $matchResponse->redirect;

      } else {

        $redirect = 'http://'.$_SERVER['HTTP_HOST'].$matchResponse->redirect;

      }

      wp_redirect( $redirect, $matchResponse->status );
      exit;

    }

  }

}
new gjRedirectInject();
