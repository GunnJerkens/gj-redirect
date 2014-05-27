<?php

class gjRedirectInject {

  function __construct() {
    add_action('template_redirect', array(&$this, 'gj_redirect_inject'));
  }

  function gj_redirect_inject() {

    $url = $_SERVER['REQUEST_URI'];

    $matchRedirects = new gjRedirectDB;
    $matchResponse = $createRedirects->matchRedirects($url);
    $matchResponse = $createResponse[0];

    if($createResponse != NULL && $createResponse->status !== 'disabled') {

      $httpVerify = stripos($createReponse->redirect, 'http://');

      if($httpVerify !== false) {

        $redirect = $createResponse->redirect;

      } else {

        $redirect = 'http://'.$_SERVER['HTTP_HOST'].$createResponse->redirect;

      }

      wp_redirect( $redirect, $createResponse->status );
      exit;

    }

  }

}
new gjRedirectInject();
