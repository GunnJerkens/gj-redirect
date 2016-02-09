<?php

class gjRedirectInject
{

  /**
   * Holds our database class
   *
   * @var object
   */
  private $database;

  /**
   * Whether or not to capture 404 requests
   *
   * @var bool
   */
  private $capture;

  /**
   * Class constructor
   *
   * @return void
   */
  function __construct()
  {
    $this->database = new gjRedirectDB();
    $this->capture  = $this->setCapture();

    add_action('template_redirect', array(&$this, 'templateInject'));
  }

  /**
   * Sets our class var $capture
   *
   * @return bool
   */
  function setCapture()
  {
    return (get_option('gj_redirect_capture_urls') === 'enabled') ? true : false;
  }

  /**
   * Injects our application in when a 404 route is called
   *
   * @return void
   */
  function templateInject()
  {

    if(is_404()) {
      $url              = $_SERVER['REQUEST_URI'];
      $parsed           = parse_url($url);
      $matchResponse    = null;

      if(isset($parsed['query'])) {
        $ignoreQueryResponse = $this->database->matchRedirects($parsed['path'], 'ignorequery');
        $matchResponse = isset($ignoreQueryResponse[0]) ? $ignoreQueryResponse[0] : null;
      }

      if($matchResponse == null) {
        $exactMatchResponse = $this->database->matchRedirects($url, 'exact');
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
          $this->database->createRedirects($redirects);
      }
    }
  }

  /**
   * Logs our redirect
   *
   * @param $url string
   *
   * @return array
   */
  function logRedirect($url)
  {
    $capture_redirect = get_option('gj_redirect_capture_redirect');
    $capture_status   = get_option('gj_redirect_capture_status');

    return array(
      'url'      => $url,
      'redirect' => $capture_redirect ? $capture_redirect : '',
      'status'   => $capture_status ? $capture_status : 'disabled',
      'scope'    => 'exact'
    );
  }

}
