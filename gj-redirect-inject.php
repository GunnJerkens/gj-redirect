<?php

class gjRedirectInject {

  private $capture;

  function __construct() {

    $this->capture = $this->setCapture();

    add_action('template_redirect', array(&$this, 'gj_redirect_inject'));

  }

  function setCapture() {

    if(get_option('gj_redirect_capture_urls') === 'enabled') {

      $capture = true;

    } else {

      $capture = false;

    }

    return $capture;

  }

  function gj_redirect_inject() {

    if(is_404()) {

      $get_gjRedirectDB = new gjRedirectDB;
      $url = $_SERVER['REQUEST_URI'];
      $parsed = parse_url($url);
      $matchResponse = null;

      if(isset($parsed['query'])) {

        $ignoreQueryResponse = $get_gjRedirectDB->matchRedirects($parsed['path'], 'ignorequery');
        $matchResponse = isset($ignoreQueryResponse[0]) ? $ignoreQueryResponse[0] : null;

      }

      if($matchResponse == null) {

        $exactMatchResponse = $get_gjRedirectDB->matchRedirects($url, 'exact');
        $matchResponse = isset($exactMatchResponse[0]) ? $exactMatchResponse[0] : null;

      }

      if($matchResponse != NULL && $matchResponse->status !== 'disabled' && $matchResponse->redirect !== "") {

        $httpVerify = stripos($matchResponse->redirect, 'http://');

        if($httpVerify !== false) {

          $redirect = $matchResponse->redirect;

        } else {

          $redirect = 'http://'.$_SERVER['HTTP_HOST'].$matchResponse->redirect;

        }

        wp_redirect( $redirect, $matchResponse->status );
        exit;

      } elseif($matchResponse == NULL && $this->capture) {

          $redirects[] = $this->logRedirect($url);

          $get_gjRedirectDB->createRedirects($redirects);

      }

    }

  }

  function logRedirect($url) {

    $capture_redirect = get_option('gj_redirect_capture_redirect');
    $capture_status = get_option('gj_redirect_capture_status');

    $redirect_url = $capture_redirect ? $capture_redirect : '';
    $redirect_status = $capture_status ? $capture_status : 'disabled';

    $redirect = array(
      'url' => $url,
      'redirect' => $redirect_url,
      'status' => $redirect_status,
      'scope' => 'exact'
    );


    return $redirect;

  }

}
new gjRedirectInject();
