<?php

class gjRedirectInject {

  function __construct() {
    add_action('template_redirect', array(&$this, 'gj_redirect_inject'));
  }

  function gj_redirect_inject() {

    if(is_404()) {

      $url = $_SERVER['REQUEST_URI'];

      $get_gjRedirectDB = new gjRedirectDB;
      $matchResponse = $get_gjRedirectDB->matchRedirects($url);
      $matchResponse = isset($matchResponse[0]) ? $matchResponse[0] : null;

      if($matchResponse != NULL && $matchResponse->status !== 'disabled' && $matchResponse->redirect !== "") {

        $httpVerify = stripos($matchResponse->redirect, 'http://');

        if($httpVerify !== false) {

          $redirect = $matchResponse->redirect;

        } else {

          $redirect = 'http://'.$_SERVER['HTTP_HOST'].$matchResponse->redirect;

        }

        wp_redirect( $redirect, $matchResponse->status );
        exit;

      } elseif($matchResponse == NULL) {

        if(get_option('gj_redirect_capture_status') === 'enabled') {

          $redirect[] = array(
            'url' => $url,
            'redirect' => '',
            'status' => 'disabled',
            'scope' => 'exact'
          );

          $get_gjRedirectDB->createRedirects($redirect);

        }

      }

    }

  }

}
new gjRedirectInject();
