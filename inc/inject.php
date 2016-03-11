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
  private function setCapture()
  {
    return (get_option('gj_redirect_capture_urls') === 'enabled') ? true : false;
  }

  /**
   * Injects our application in when a 404 route is called
   *
   * @return void
   */
  public function templateInject()
  {
    if(is_admin() || !is_404()) {
      return;
    }

    $request   = $_SERVER['REQUEST_URI'];
    $parsed    = parse_url($request);
    $redirects = $this->database->matchRedirects($request, $parsed['path']);

    if(is_array($redirects) && sizeof($redirects) > 0) {
      foreach($redirects as $redirectObj) {
        if(isset($redirectObj->status) && $redirectObj->status !== 'disabled') {
          $url = $this->buildRedirect($redirectObj);

          wp_redirect($url, $redirectObj->status);
          exit;
        }
      }
    }

    $redirects[] = $this->logRedirect($request);
    $this->database->createRedirects($redirects);
  }

  /**
   * Builds the url for the redirect
   *
   * @param $redirectObj object
   *
   * @return string
   */
  private function buildRedirect($redirectObj)
  {
    if(stripos($redirectObj->redirect, 'http://') || stripos($redirectObj->redirect, 'https://')) {
      return $redirectObj->redirect;
    }

    return (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $redirectObj->redirect;
  }

  /**
   * Logs our redirect
   *
   * @param $url string
   *
   * @return array
   */
  private function logRedirect($url)
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
