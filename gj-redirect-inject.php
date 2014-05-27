<?php

class gjRedirectInject {

  function __construct() {
    add_action('template_redirect', array(&$this, 'gj_redirect_inject'));
  }

  function gj_redirect_inject() {

    $url = $_SERVER['REQUEST_URI'];

    $createRedirects = new gjRedirectDB;
    $createResponse = $createRedirects->matchRedirects($url);



    if(isset($createResponse->redirect) && isset($createResponse->status) && $createResponse->status !== 'disabled') {

      $httpVerify = stripos($createReponse->redirect, 'http://');

      if($httpVerify !== false) {

        $redirect = $createResponse->redirect;

      } else {

        $redirect = $_SERVER['HTTP_HOST'].$createResponse->redirect;

      }

      wp_redirect( $redirect, $createResponse->status );
      exit;

    }

  }

}
new gjRedirectInject();
