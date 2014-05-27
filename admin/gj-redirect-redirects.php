<?php

$get_gjRedirectDB = new gjRedirectDB;

if(!empty($_POST)) {

  $postData = $_POST;

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

// This is some sheisty pagination in PHP. I'm not proud.
$rows = $get_gjRedirectDB->countRows();
$showItems = 50;
$totalItems = (array) $rows[0];
$totalItems = $totalItems['COUNT(*)'];
$pages = ceil($totalItems / $showItems);

$url = parse_url($_SERVER['REQUEST_URI']);
if(isset($url['query'])) {

  parse_str($url['query'], $urlArray);
  $currentPage = $urlArray['paged'];

}

$lowerLimit = ($currentPage * $showItems) - ($showItems);
$upperLimit = $currentPage * $showItems;

$redirects = $get_gjRedirectDB->getRedirects($lowerLimit, $upperLimit); ?>


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

<div class="tablenav bottom">
  <div class="tablenav-pages">
    <span class="displaying-num"><?php echo $totalItems.' items'; ?></span>
    <span class="pagination-links"><a class="first-page <?php echo $currentPage - 1 > 0 ? '' : 'disabled'; ?>" title="Go to the first page" href="?page=gj_redirect&tab=gj_redirect_redirects&paged=1">«</a>
    <a class="prev-page <?php echo $currentPage - 1 > 0 ? '' : 'disabled'; ?>" title="Go to the previous page" href="?page=gj_redirect&tab=gj_redirect_redirects&paged=<?php echo $currentPage - 1 > 0 ? $currentPage - 1 : $currentPage; ?>">‹</a>
    <span class="paging-input"><?php echo $currentPage; ?> of <span class="total-pages"><?php echo $pages; ?></span></span>
    <a class="next-page <?php echo $currentPage + 1 > $pages ? 'disabled' : ''; ?>" title="Go to the next page" href="?page=gj_redirect&tab=gj_redirect_redirects&paged=<?php echo $currentPage + 1 > $pages ? $currentPage : $currentPage + 1; ?>">›</a>
    <a class="last-page <?php echo $currentPage + 1 > $pages ? 'disabled' : ''; ?>" title="Go to the last page" href="?page=gj_redirect&tab=gj_redirect_redirects&paged=<?php echo $pages; ?>">»</a></span>
  </div>
</div>










