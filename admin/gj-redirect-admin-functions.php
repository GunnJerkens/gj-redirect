<?php

function gjRedirectBulkUpload($uploadedFile) {

  $get_gjRedirectDB = new gjRedirectDB;

  if ($uploadedFile['name'] === '') {

    $response = gjRedirectMessaging('error', 'Error: You must provide a file to upload.');

  } else {

    $row = 1;
    $createArray = array();

    if (($handle = fopen($uploadedFile['tmp_name'], "r")) !== FALSE) {

      while (($data = fgetcsv($handle, ",")) !== FALSE) {

        array_push($createArray, $data);

      }

      fclose($handle);

    }

  }

  $labels = array("url", "redirect", "status", "scope");

  if($labelsCheck = ($labels === $createArray[0])) {

    foreach ($createArray as $key=>$value) {

      if (count($labels) == count($value)) {
        $createArray[$key] = array_combine($labels, $value);
      }
    }

    unset($createArray[0]);

  } else {

    $response = gjRedirectMessaging('error', 'Error: Labels do not match, check documentation. ');

  }

  $createResponse = true;

  if(!empty($createArray)) {

    $createResponse = $get_gjRedirectDB->createRedirects($createArray);

    foreach($createResponse as $response) {

      if($response === 0) {

        $createResponse = false;

      }
    }

    $response = gjRedirectMessaging('success', 'Upload was successful.');

  } else {

    $response = gjRedirectMessaging('error', 'Error: Array of items to create was blank.');

  }

  return $response;

}

function gjRedirectMessaging($status, $message) {

  $response = array (
    'status' => $status,
    'message' => $message
  );

  return $response;

}