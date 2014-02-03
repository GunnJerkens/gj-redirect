<?php
/**
 * @package GJ_Redirect
 * @version 0.1
 */

/*
Plugin Name: GJ Redirect
Plugin URI: http://gunnjerkens.com
Description: Adds a meta box to pages allowing you to 301 redirect pages to the home page.
Version: 0.1
Author: Gunn|Jerkens
Author URI: http://gunnjerkens.com
*/


add_action( 'load-post.php', 'gj_metaboxes' );
add_action( 'load-post-new.php', 'gj_metaboxes' );
add_action( 'wp_head', 'gj_metaboxes_action');

function gj_metaboxes() {
  add_action( 'add_meta_boxes', 'gj_metaboxes_add' );
  add_action( 'save_post', 'gj_metaboxes_save_post', 10, 2 );
}

// Setup the metabox
function gj_metaboxes_add() {
  add_meta_box(
    'gj-redirect',
    esc_html__( 'GJ Redirect', 'gj-redirect' ),
    'gj_metabox_callback',
    '',
    'side',
    'default'
  );
}

// Callback for the template
function gj_metabox_callback($object) {
    wp_nonce_field( basename( __FILE__ ), 'gj_redirect_nonce' ); 
    $gj_redirect = get_post_meta( $object->ID, 'gj_redirect', true);
    ?>
    <p>
      <label for="gj-redirect"><?php _e( "301 Redirect", 'GJ Redirect' ); ?></label>
      <input type="checkbox" name="gj-redirect" id="gj-redirect" <?php if ($gj_redirect) { echo "checked='checked'"; } ?> />
    </p><?php
}

// Save the meta box
function gj_metaboxes_save_post( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['gj_redirect_nonce'] ) || !wp_verify_nonce( $_POST['gj_redirect_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = ( isset( $_POST['gj-redirect'] ) ? 'true' : '' );

  /* Get the meta key. */
  $meta_key = 'gj_redirect';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

// Complete the redirect --- TODO: Add custom URL options
function gj_metaboxes_action() {
  $post_id = get_the_ID();
  if ( !empty( $post_id ) ) {
    $postRedirect = get_post_meta( $post_id, 'gj_redirect', true );
    if ($postRedirect === 'true') {
      wp_redirect( home_url() ); 
      exit;
    }
  }
}

?>