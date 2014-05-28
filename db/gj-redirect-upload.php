<?php

class gjRedirectUpload {

  function uploadCSV() {

  $csv = $_FILES['gj_upload'];

    if ($csv['name'] === '') {
      echo "You must provide a csv to upload.";
      exit;
    } else {
      $row = 1;
      $redirect = array();
      if (($handle = fopen($uploadedfile['tmp_name'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, ",")) !== FALSE) {
          array_push($redirect, $data);
        }
        fclose($handle);
      }
    }

  }

}
