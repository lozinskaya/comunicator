<?php
// require_once( ABSPATH . 'wp-admin/includes/media.php' );

function wp_upload_attachment($file_post) {
    if($file_post['error'] !== UPLOAD_ERR_OK)
        return false;

    $file_name = $file_post['name'];
    $file_temp = $file_post['tmp_name'];

    $filename = basename($file_name);

    include_once( ABSPATH . 'wp-admin/includes/image.php' );

    $upload_file = wp_upload_bits($filename, null, file_get_contents($file_temp));

    $wp_filetype = wp_check_filetype( $filename, null );
    $attachment = array(
      'post_mime_type' => $wp_filetype['type'],
      'post_title' => sanitize_file_name( $filename ),
      'post_content' => '',
      'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $upload_file['file'] );

    $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );

    wp_update_attachment_metadata( $attach_id, $attach_data );
    return $attach_id;
}

function check_file_type($file, $allow_types) {
  $check_type = false;
  foreach($allow_types as $allow_type) 
      if(substr($file['type'], 0, strlen($allow_type) + 1) ==  "{$allow_type}/")
      {
          $check_type = true;
          break;
      }

  return $check_type;
}

require_once 'Attachment.php';
require_once 'ImageAttachment.php';
require_once 'VideoAttachment.php';
?>