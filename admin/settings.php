<?php

if((!empty($_POST) || !empty($_FILES)) && (1 !== check_admin_referer('gj-redirect'))) {
  die('Permission denied');
}

ini_set('auto_detect_line_endings',TRUE);

$response = array();

if(isset($_FILES['gj_redirects_csv'])) {
  $uploadedFile = $_FILES['gj_redirects_csv'];
  $response = gjRedirectBulkUpload($uploadedFile);
}

if(isset($_POST['gj_redirects_capture'])) {
  $capture_urls = $_POST['gj_redirect_capture_urls'];
  update_option('gj_redirect_capture_urls', $capture_urls);

  $capture_status = $_POST['gj_redirect_capture_status'];
  update_option('gj_redirect_capture_status', $capture_status);

  $capture_redirect = $_POST['gj_redirect_capture_redirect'];
  update_option('gj_redirect_capture_redirect', $capture_redirect);

  $response = gjRedirectMessaging('success', 'Options saved.');

} else {

  $capture_urls     = get_option('gj_redirect_capture_urls');
  $capture_status   = get_option('gj_redirect_capture_status');
  $capture_redirect = get_option('gj_redirect_capture_redirect');

}

if(isset($_POST['gj_redirect_metabox_status'])) {
  $metabox_status = $_POST['gj_redirect_metabox_status'];
  update_option('gj_redirect_metabox_status', $metabox_status); 

  $response = gjRedirectMessaging('success', 'Options saved.');
} else {
  $metabox_status = get_option('gj_redirect_metabox_status');
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

if(isset($response['status'])) {
  if($response['status'] === 'success') {
    echo '<div id="message" class="updated"><p>'.$response['message'].'</p></div>';
  } else if ($response['status'] === 'error') {
    echo '<div id="message" class="error"><p>'.$response['message'].'</p></div>';
  }
} ?>

<table class="gj-redirects-settings">
  <tr>
    <td><h3>Capture 404s</h3></td>
  </tr>
  <form name="gj_redirects_capture" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <?php wp_nonce_field('gj-redirect'); ?>
    <input type="hidden" name="gj_redirects_capture" value=true>
    <tr>
      <td>
        <p>Capture urls when a 404 is triggered: </p>
      </td>
      <td>
        <select name="gj_redirect_capture_urls">
          <option value="enabled" <?php echo $capture_urls === 'disabled' ? 'selected' : ''; ?>>Enabled</option>
          <option value="disabled" <?php echo $capture_urls === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <p>Default status of redirect: </p>
      </td>
      <td>
        <select name="gj_redirect_capture_status">
          <option value="disabled" <?php echo $capture_status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
          <option value="301" <?php echo $capture_status === '301' ? 'selected' : ''; ?>>301</option>
          <option value="302" <?php echo $capture_status === '302' ? 'selected' : ''; ?>>302</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>
        <p>Default redirect point: </p>
      </td>
      <td>
        <input name="gj_redirect_capture_redirect" value="<?php echo $capture_redirect != "" ? $capture_redirect : '/'; ?>">
      </td>
    </tr>
    <tr>
      <td></td>
      <td><button class="btn button" type="submit">Update Settings</button></td>
    </tr>
  </form>
  <tr>
    <td><h3>Bulk Upload</h3><td>
  </tr>
  <tr>
    <form name="gj_redirects_upload" method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <?php echo wp_nonce_field('gj-redirect'); ?>
      <td><input type="file" name="gj_redirects_csv" class="btn" value="<?php echo isset($upload) ? $upload : ''; ?>" size="20"></td>
      <td><button class="btn button" type="submit">Upload CSV</button></td>
    </form>
  </tr>
  <tr>
    <td><h3>Delete All Redirects</h3></td>
  </tr>
  <tr>
    <form name="gj_redirects_delete" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <?php echo wp_nonce_field('gj-redirect'); ?>
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
