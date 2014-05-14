<?php
// TODO: Write documentation

register_activation_hook(__FILE__, array('gjRedirectDatabase', 'initDB'));

class gjRedirectDatabase {

  static function initDB() {
    // Create our tables
  }

}
