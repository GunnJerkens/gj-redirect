<?php

if((!empty($_POST) || !empty($_FILES)) && (1 !== check_admin_referer('gj-redirect'))) {
  die('Permission denied');
}

$response = array();

if(!empty($_POST)) {
  if($_POST['form_name'] === 'gj_redirects') {
    $postData = $_POST;
    $response = gjRedirectModifyTable($postData);
  } elseif($_POST['form_name'] === 'gj_redirects_count') {
    $redirects_count = (int) $_POST['gj_redirect_count_number'];
    update_option('gj_redirect_count_number', $redirects_count);
  }
}

$redirects_count = get_option('gj_redirect_count_number') != null ? get_option('gj_redirect_count_number') : 50;
$query = array(
  'items' => (isset($_GET['count']) ? (int) $_GET['count'] : $redirects_count),
  'sort_column' => (isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id'),
  'sort_direction' => (isset($_GET['sort_direction']) ? $_GET['sort_direction'] : 'ASC')
);

$sort = gjRedirectSortTable($query);
$pagination = gjRedirectPaginateTable($query['items']);
$url = gjRedirectsBuildURL($query);
$count_replace = '&count='.$query['items'];
$count_url = str_replace($count_replace, '', $url);

$get_gjRedirectDB = new gjRedirectDB;
$redirects = $get_gjRedirectDB->getRedirects($pagination['sql_offset'], $pagination['sql_length'], $query['sort_column'], $query['sort_direction']);

// This is our error handling
if(isset($response['status'])) {
  if($response['status'] === 'success') {
    echo '<div id="message" class="updated"><p>'.$response['message'].'</p></div>';
  } else if (isset($response['status'] && $response['status'] === 'error') {
    echo '<div id="message" class="error"><p>'.$response['message'].'</p></div>';
  }
} ?>

<div class="gj-item count">
  <form name="gj_redirects_count" method="post" action="<?php echo $count_url; ?>">
    <?php wp_nonce_field('gj-redirect'); ?>
    <input type="hidden" name="form_name" value="gj_redirects_count">
    <select name="gj_redirect_count_number">
      <option value="25" <?php echo $redirects_count == '25' ? 'selected' : ''; ?>>25</option>
      <option value="50" <?php echo $redirects_count == '50' ? 'selected' : ''; ?>>50</option>
      <option value="100" <?php echo $redirects_count == '100' ? 'selected' : ''; ?>>100</option>
      <option value="250" <?php echo $redirects_count == '250' ? 'selected' : ''; ?>>250</option>
    </select>
    <button class="btn button" type="submit">Apply</button>
  </form>
</div>

<form name="gj_redirects" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
  <input type="hidden" name="form_name" value="gj_redirects">
  <table class="wp-list-table widefat fixed gj-redirects">
    <thead class="">
      <tr>
        <th scope="col" id="cb" class="column-cb check-column">
          <input id="cb-select-all-1" type="checkbox">
        </th>
        <th scope="col" id="url" class="manage-column column-url <?php echo $sort['url']; ?>">
          <a href="<?php echo gjRedirectSortURL($query, 'url'); ?>"><span>Page Location</span><span class="sorting-indicator"></span></a>
        </th>
        <th scope="col" id="redirect" class="manage-column column-redirect <?php echo $sort['redirect']; ?>">
          <a href="<?php echo gjRedirectSortURL($query, 'redirect'); ?>"><span>Redirect Location</span><span class="sorting-indicator"></span></a>
        </th>
        <th scope="col" id="status" class="manage-column column-status <?php echo $sort['status']; ?>">
          <a href="<?php echo gjRedirectSortURL($query, 'status'); ?>"><span>Redirect Type</span><span class="sorting-indicator"></span></a>
        </th>
        <th scope="col" id="scope" class="manage-column column-scope <?php echo $sort['scope']; ?>">
          <a href="<?php echo gjRedirectSortURL($query, 'scope'); ?>"><span>Redirect Scope</span><span class="sorting-indicator"></span></a>
        </th>
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
        <td><input type="text" class="detect-change full-width" name="<?php echo $redirect->id; ?>[url]" value="<?php echo $redirect->url; ?>" /></td>
        <td><input type="text" class="detect-change full-width" name="<?php echo $redirect->id; ?>[redirect]" value="<?php echo $redirect->redirect; ?>" /></td>
        <td>
          <select class="detect-change" name="<?php echo $redirect->id; ?>[status]">
            <option value="disabled" <?php echo $redirect->status === 'disabled' ? 'selected' : ''; ?>>Disabled</option>
            <option value="301" <?php echo $redirect->status === '301' ? 'selected' : ''; ?>>301</option>
            <option value="302" <?php echo $redirect->status === '302' ? 'selected' : ''; ?>>302</option>
          </select>
        </td>
        <td>
          <select class="detect-change" name="<?php echo $redirect->id; ?>[scope]">
            <option value="exact" <?php echo $redirect->scope === 'exact' ? 'selected' : ''; ?>>Exact Match</option>
            <option value="ignorequery" <?php echo $redirect->scope === 'ignorequery' ? 'selected' : ''; ?>>Ignore Query</option>
            <!-- <option value="any" <?php // echo $redirect->scope === 'any' ? 'selected' : ''; ?>>Any Match</option> -->
          </select>
        </td>
      </tr><?php
    } ?>

    </tbody>
  </table>

  <div class="gj-buttons">
    <div class="btn button table-button add-row">Add Row</div>
    <button class="btn button table-button" type="submit">Update Settings</button>
  </div>

  <div class="tablenav bottom">
    <div class="tablenav-pages">
      <span class="displaying-num"><?php echo $pagination['total_items'].' items'; ?></span>
      <span class="pagination-links">
        <a 
          class="first-page <?php echo $pagination['current_page'] - 1 > 0 ? '' : 'disabled'; ?>" 
          title="Go to the first page" href="<?php echo $url.'&paged=1'; ?>">«
        </a>
        <a 
          class="prev-page <?php echo $pagination['current_page'] - 1 > 0 ? '' : 'disabled'; ?>" 
          title="Go to the previous page" 
          href="<?php echo $url.'&paged='.($pagination['current_page'] - 1 > 0 ? $pagination['current_page'] - 1 : $pagination['current_page']); ?>">‹
        </a>
        <span 
          class="paging-input"><?php echo $pagination['current_page']; ?> of 
          <span class="total-pages"><?php echo $pagination['pages'] == 0 ? '1' : $pagination['pages']; ?></span>
        </span>
        <a 
          class="next-page <?php echo $pagination['current_page'] + 1 > $pagination['pages'] ? 'disabled' : ''; ?>" 
          title="Go to the next page" 
          href="<?php echo $url.'&paged='.($pagination['current_page'] + 1 > $pagination['pages'] ? $pagination['current_page'] : $pagination['current_page'] + 1); ?>">›
        </a>
        <a 
          class="last-page <?php echo $pagination['current_page'] + 1 > $pagination['pages'] ? 'disabled' : ''; ?>" 
          title="Go to the last page" 
          href="<?php echo $url.'&paged='.$pagination['pages']; ?>">»
        </a>
      </span>
    </div>
  </div>

</form>
