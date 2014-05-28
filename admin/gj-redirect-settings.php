<?php

ini_set('auto_detect_line_endings',TRUE);

if(isset($_FILES['gj_redirects_csv'])) {

  $uploadedFile = $_FILES['gj_redirects_csv'];

  $response = gjRedirectBulkUpload($uploadedFile);

}


if($response['status'] === 'success') {

  echo '<div id="message" class="updated"><p>'.$response['message'].'</p></div>';

} else if ($response['status'] === 'error') {

  echo '<div id="message" class="error"><p>'.$response['message'].'</p></div>';

} ?>

<h3>Bulk Upload</h3>

  <form name="gj_redirects_upload" method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="file" name="gj_redirects_csv" class="btn" value="<?php echo $upload; ?>" size="20">
    <button id="button" class="btn button" type="submit">Upload CSV</button>
  </form>

<h3>Metabox</h3>

  <form name="gj_redirects_metabox" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    
    <button id="button" class="btn button" type="submit">Update Settings</button>
  </form>

<h3>Delete All Redirects</h3>

  <form name="gj_redirects_delete">

    <button id="button" class="btn button" type="submit">Delete All</button>
  </form>

