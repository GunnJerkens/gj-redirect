<?php

// global $gjRedirectDB;

if(isset($_POST)) {

  $postData = $_POST;
  $deleteArray = [];

  foreach($postData as $post) {

    if($post[1] === 'on') {
      $deleteItem = (int) $post[0];
      $deleteArray[] = $deleteItem;
    }

  }

  $deleteRedirects = new gjRedirectDB;
  $deleteRedirects->setDeletes($deleteArray);
  $result = $deleteRedirects->deleteRedirects();

  if($result) {
    echo '<div id="message" class="updated"><p>Items deleted successfully.</p></div>';
  } else {
    echo '<div id="message" class="error"><p>Items failed to delete.</p></div>';
  }

}



$getRedirects = new gjRedirectDB;
$redirects = $getRedirects->getRedirects(); ?>

<form name="gj_redirects" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
  <input type="hidden" name="form_name" value="gj_redirects">
  <table class="wp-list-table widefat fixed">
    <thead class="">
      <tr>
        <th scope="col" id="cb" class="column-cb">
          <input id="cb-select-all-1" type="checkbox">
        </th>
        <th><span>Redirect UID</span></th>
        <th><span>Page Location</span></th>
        <th><span>Redirect Location</span></th>
        <th><span>Redirect Type</span></th>
      </tr>
    </thead>
    <tbody><?php

    foreach ($redirects as $redirect) { ?>

      <tr id="redirect-<?php echo $redirect->id; ?>" class="alternate">
        <input type="hidden" name="<?php echo $redirect->id; ?>[]" value="<?php echo $redirect->id; ?>">
        <th>
          <input type="checkbox" name="<?php echo $redirect->id; ?>[]" id="redirect_<?php echo $redirect->id; ?>">
        </th>
        <td><span><?php echo $redirect->id; ?></span></td>
        <td><input id="upload_image" type="text" size="36" name="<?php echo $redirect->id; ?>[]" value="<?php echo $redirect->url; ?>" /></td>
        <td><input id="upload_image" type="text" size="36" name="<?php echo $redirect->id; ?>[]" value="<?php echo $redirect->redirect; ?>" /></td>
        <td>
          <select name="<?php echo $redirect->id; ?>[]">
            <option value="disabled" <?php echo $redirect->status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
            <option value="301" <?php echo $redirect->status === '301' ? 'selected' : ''; ?>>301</option>
            <option value="302" <?php echo $redirect->status === '302' ? 'selected' : ''; ?>>302</option>
          </select>
        </td>
      </tr><?php
    } ?>

    </tbody>
  </table>
  <br>
  <button class="btn button" type="submit">Update Settings</button>

</form>


