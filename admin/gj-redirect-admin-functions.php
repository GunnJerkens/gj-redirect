<?php

function gjRedirectModifyTable($postData) {

  $get_gjRedirectDB = new gjRedirectDB;

  foreach($postData as $post) {

    if(isset($post['delete']) && $post['delete'] === 'on') {
      $deleteItem = (int) $post['id'];
      $deleteArray[] = $deleteItem;
    }

  }

  unset($post);

  $deleteResponse = true;

  if(!empty($deleteArray)) {
    $get_gjRedirectDB->setDeletes($deleteArray);
    $deleteResponse = $get_gjRedirectDB->deleteRedirects();

  }

  foreach($postData as $post) {

    if(isset($post['mode']) && $post['mode'] === 'create' && isset($post['url'])) {

      $createArray[] = $post;

    }

  }

  unset($post);

  $createResponse = true;

  if(!empty($createArray)) {

    $createResponse = $get_gjRedirectDB->createRedirects($createArray);

    foreach($createResponse as $response) {

      if($response === 0) {

        $createResponse = false;

      }
    }

  }

  foreach($postData as $post) {

    if(isset($post['mode']) && $post['mode'] === 'update') {

      $updateArray[] = $post;

    }

  }

  unset($post);

  $updateResponse = true;

  if(!empty($updateArray)) {

    $updateResponse = $get_gjRedirectDB->updateRedirects($updateArray);

    foreach ($updateResponse as $response) {

      if($response === 0) {

        $createResponse = false;

      }

    }

  }

  // This is our error handling for the moment. Sorry (shrug).
  if(!$deleteResponse || !$createResponse || !$updateResponse) {

    $response = gjRedirectMessaging('error', 'Items failed to update.');

  } else {

    $response = gjRedirectMessaging('success', 'Items updated successfully.');

  }

  return $response;

}

function gjRedirectPaginateTable($showItems) {

  $get_gjRedirectDB = new gjRedirectDB;

  $rows = $get_gjRedirectDB->countRows();

  $totalItems = (array) $rows[0];
  $totalItems = $totalItems['COUNT(*)'];
  $pages = ceil($totalItems / $showItems);

  $currentPage = 1;

  $url = parse_url($_SERVER['REQUEST_URI']);
  parse_str($url['query'], $urlArray);

  if(isset($urlArray['paged'])) {
    $currentPage = $urlArray['paged'];
  }

  $lowerLimit = ($currentPage * $showItems) - ($showItems);
  $upperLimit = $currentPage * $showItems;

  $pagination = array(
    'rows' => $rows,
    'show_items' => $showItems,
    'total_items' => $totalItems,
    'pages' => $pages,
    'current_page' => $currentPage,
    'lower_limit' => $lowerLimit,
    'upper_limit' => $upperLimit
  );

  return $pagination;

}

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