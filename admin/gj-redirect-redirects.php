<?php

global $gjRedirectDB;

$redirects = $gjRedirectDB->getRedirects(); ?>

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
        <th>
          <input type="checkbox" name="id[]" id="redirect_<?php echo $redirect->id; ?>">
        </th>
        <td><span><?php echo $redirect->id; ?></span></td>
        <td><input id="upload_image" type="text" size="36" name="gj_login_logo" value="<?php echo $redirect->url; ?>" /></td>
        <td><input id="upload_image" type="text" size="36" name="gj_login_logo" value="<?php echo $redirect->redirect; ?>" /></td>
        <td>
          <select>
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
  <input class="btn button" type="submit" name="Submit" value="Update Settings" />

</form>


