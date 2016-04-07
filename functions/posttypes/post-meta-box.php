<?php

add_action( 'add_meta_boxes', 'add_featured_image_meta' ); 
function add_featured_image_meta(){
	add_meta_box('featured_image', 'Show Featured Image', 'show_featured_image_meta_box', 'post', 'normal', 'default');
}

function show_featured_image_meta_box(){
    global $post;
    global $pagenow; 
    
	wp_nonce_field( 'featured_image_save', 'featured_image_nonce' );
    $show   = get_post_meta( $post->ID, 'show_featured_image', true );
	$check = $pagenow == 'post-new.php' ? checked('', $show, false) : checked( 1, $show, false ); 
	
	$show_loop = get_post_meta( $post->ID, 'show_thumbnail_in_loop', true );
	$check_loop = $pagenow == 'post-new.php' ? checked('', $show_loop, false) : checked( 1, $show_loop, false ); 
	?>
		<table>
			<tr>
				<td><label for="show_featured_image">Show Featured Image on Single Post Page: </label></td>
				<td><input class="widefat" type="checkbox" id="show_featured_image" name="show_featured_image" value="1" <?php echo $check;  ?> /></td>
			</tr>
			<tr>
				<td><label for="show_thumbnail_in_loop">Show Featured Image in Latest News Loop: </label></td>
				<td><input class="widefat" type="checkbox" id="show_thumbnail_in_loop" name="show_thumbnail_in_loop" value="1" <?php echo $check_loop;  ?> /></td>
			</tr>			
		</table>
	<?php
}

add_action( 'save_post', 'save_featured_image_meta', 1, 2 );
function save_featured_image_meta($post_id, $post){
	
	if( !isset( $_POST['featured_image_nonce'] ) ) return $post_id; 
	
	if( !wp_verify_nonce( $_POST['featured_image_nonce'], 'featured_image_save' ) )
		die( 'Could not verify request...' ); 
		
	 if ( !current_user_can( 'edit_post', $post->ID ))
	 	return $post->ID;
	 	
	 update_post_meta( $post_id, 'show_featured_image', isset($_POST['show_featured_image']) ? $_POST['show_featured_image'] : '' ); 
	 
	 update_post_meta( $post_id, 'show_thumbnail_in_loop', isset($_POST['show_thumbnail_in_loop']) ? $_POST['show_thumbnail_in_loop'] : '' );
	
	
}
