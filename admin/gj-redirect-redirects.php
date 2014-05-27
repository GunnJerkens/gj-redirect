<?php

if(!empty($_POST)) {

  $postData = $_POST;
  $deleteArray = [];

  foreach($postData as $post) {

    if(isset($post['delete']) && $post['delete'] === 'on') {
      $deleteItem = (int) $post['id'];
      $deleteArray[] = $deleteItem;
    }

  }

  unset($post);

  $deleteResponse = true;

  if(!empty($deleteArray)) {
    $deleteRedirects = new gjRedirectDB;
    $deleteRedirects->setDeletes($deleteArray);
    $deleteResponse = $deleteRedirects->deleteRedirects();

  }

  foreach($postData as $post) {

    if(isset($post['mode']) && $post['mode'] === 'create' && isset($post['url'])) {

      $createArray[] = $post;

    }

  }

  unset($post);

  $createResponse = true;

  if(!empty($createArray)) {

    $createRedirects = new gjRedirectDB;
    $createResponse = $createRedirects->createRedirects($createArray);

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

    $updateRedirects = new gjRedirectDB;
    $updateResponse = $updateRedirects->updateRedirects($updateArray);

    foreach ($updateResponse as $response) {

      if($response === 0) {

        $createResponse = false;

      }

    }

  }

  // This is our error handling for the moment. Sorry (shrug).
  if(!$deleteResponse || !$createResponse || !$updateResponse) {

    $result = false;

  } else {

    $result = true;

  }

  if($result) {
    echo '<div id="message" class="updated"><p>Items updated successfully.</p></div>';
  } else {
    echo '<div id="message" class="error"><p>Items failed to update.</p></div>';
  }

}

$getRedirects = new gjRedirectDB;
$redirects = $getRedirects->getRedirects(); ?>

<style>
#button {
  margin-top: 20px;
}
</style>

<form name="gj_redirects" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
  <input type="hidden" name="form_name" value="gj_redirects">
  <table class="wp-list-table widefat fixed gj-redirects">
    <thead class="">
      <tr>
        <th scope="col" id="cb" class="column-cb check-column">
          <input id="cb-select-all-1" type="checkbox">
        </th>
        <th><span>Page Location</span></th>
        <th><span>Redirect Location</span></th>
        <th><span>Redirect Type</span></th>
      </tr>
    </thead>
    <tbody><?php

    foreach ($redirects as $redirect) { ?>

      <tr id="redirect-<?php echo $redirect->id; ?>" class="alternate redirect" data-id="<?php echo $redirect->id; ?>">
        <input type="hidden" name="<?php echo $redirect->id; ?>[id]" value="<?php echo $redirect->id; ?>">
        <input type="hidden" class="mode" name="<?php echo $redirect->id; ?>[mode]" value="">
        <th class="check-column">
          <input type="checkbox" name="<?php echo $redirect->id; ?>[delete]" id="redirect_<?php echo $redirect->id; ?>">
        </th>
        <td><input type="text" class="detect-change" name="<?php echo $redirect->id; ?>[url]" value="<?php echo $redirect->url; ?>" /></td>
        <td><input type="text" class="detect-change" name="<?php echo $redirect->id; ?>[redirect]" value="<?php echo $redirect->redirect; ?>" /></td>
        <td>
          <select class="detect-change" name="<?php echo $redirect->id; ?>[status]">
            <option value="disabled" <?php echo $redirect->status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
            <option value="301" <?php echo $redirect->status === '301' ? 'selected' : ''; ?>>301</option>
            <option value="302" <?php echo $redirect->status === '302' ? 'selected' : ''; ?>>302</option>
          </select>
        </td>
      </tr><?php
    } ?>

    </tbody>
  </table>
  <div id="button" class="btn button addRow">Add Row</div>
  <button id="button" class="btn button" type="submit">Update Settings</button>
</form>



