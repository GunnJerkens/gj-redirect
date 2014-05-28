<?php

ini_set('auto_detect_line_endings',TRUE);

if(isset($_FILES['gj_redirects_csv'])) {

  $uploadedFile = $_FILES['gj_redirects_csv'];

  $response = gjRedirectBulkUpload($uploadedFile);

}

if(isset($_POST['metabox_status'])) {

  $metabox_status = $_POST['metabox_status'];
  update_option('metabox_status', $metabox_status); 

  $response = gjRedirectMessaging('success', 'Options saved.');

} else {

  $metabox_status = get_option('metabox_status');

}

if(isset($_POST['delete_redirects'])) {

  if($_POST['delete_redirects'] === 'true') {

    // Delete All Redirects
    $get_gjRedirectDB = new gjRedirectDB;
    $deleteSuccess = $get_gjRedirectDB->deleteAllRedirects();

    if($deleteSuccess) {

      $response = gjRedirectMessaging('success', 'All redirects have been deleted.');

    } else {

      $response = gjRedirectMessaging('error', 'Redirects failed to delete.');

    }

  } else {

    $response = gjRedirectMessaging('error', 'You must select "Yes I\'m Sure" to continue with deletion.');

  }

}

if($response['status'] === 'success') {

  echo '<div id="message" class="updated"><p>'.$response['message'].'</p></div>';

} else if ($response['status'] === 'error') {

  echo '<div id="message" class="error"><p>'.$response['message'].'</p></div>';

} ?>

<table class="gj-redirects-settings">
  <tr>
    <td><h3>Bulk Upload</h3><td>
  </tr>
  <tr>
    <form name="gj_redirects_upload" method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <td><input type="file" name="gj_redirects_csv" class="btn" value="<?php echo $upload; ?>" size="20"></td>
      <td><button class="btn button" type="submit">Upload CSV</button></td>
    </form>
  </tr>
  <tr>
    <td><h3>Metabox</h3></td>
  </tr>
  <tr>
    <form name="gj_redirects_metabox" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <td>
          <select name="metabox_status">
            <option value="enabled" <?php echo $metabox_status === 'disabled' ? 'selected' : ''; ?>>Enabled</option>
            <option value="disabled" <?php echo $metabox_status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
          </select>
      </td>
      <td><button class="btn button" type="submit">Update Settings</button></td>
    </form>
  </tr>
  <tr>
    <td><h3>Delete All Redirects</h3></td>
  </tr>
  <tr>
    <form name="gj_redirects_delete" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <td>
        <select name="delete_redirects">
          <option value="false" selected>Do Not Delete</option>
          <option value="true">Yes I'm Sure</option>
        </select>
      </td>
      <td><button class="btn button" type="submit">Delete All</button></td>
    </form>
  </tr>

</table>