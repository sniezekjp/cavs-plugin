<?php

function location_post_type() {
	$labels = array(
		'name' => 'Locations',
		'singular_name' => 'Location',
		'add_new' => 'Add Location',
		'add_new_item' => 'Add New Location',
		'edit_item' => 'Edit Location',
		'new_item' => 'New Location',
		'all_items' => 'All Locations',
		'view_item' => 'View Location',
		'search_items' => 'Search Locations',
		'not_found' =>  'No locations found',
		'not_found_in_trash' => 'No locations found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Locations'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => 'location' ),
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => 100,
		'supports' => array( 'title', 'editor', 'thumbnail' )
	); 
	
	register_post_type( 'location', $args );
}
add_action( 'init', 'location_post_type' );


add_action( 'add_meta_boxes', 'add_location_meta' ); 
function add_location_meta(){
	add_meta_box('location_info', 'Location Information', 'show_location_info_meta_box', 'location', 'side', 'default');
}

function show_location_info_meta_box(){
    global $post;
	wp_nonce_field( 'location_save', 'location_nonce' );

   $street = get_post_meta( $post->ID, '_location_street', true );
   $city   = get_post_meta( $post->ID, '_location_city', true );  
   $state  = get_post_meta( $post->ID, '_location_state', true );
   $zip    = get_post_meta( $post->ID, '_location_zip', true );
	?>
		<table>
			<tr>
				<td><label for="_location_street">Street: </label></td>
				<td><input class="widefat" type="text" id="_location_street" name="_location_street" value="<?php echo esc_attr( $street ); ?>" /></td>
			</tr>		
			<tr>
				<td><label for="_location_city">City: </label></td>
				<td><input class="widefat" type="text" id="_location_city" name="_location_city" value="<?php echo esc_attr( $city ); ?>" /></td>
			</tr>
			<tr>
				<td><label for="_location_state">State: </label></td>
				<td><input class="widefat" type="text" id="_location_state" name="_location_state" value="<?php echo esc_attr( $state ); ?>" /></td>								
			</tr>
			<tr>
				<td><label for="_location_zip">Zip: </label></td>
				<td><input class="widefat" type="text" id="_location_zip" name="_location_zip" value="<?php echo esc_attr( $zip ); ?>" /></td>								
			</tr>			
		</table>
	<?php
}

add_action( 'save_post', 'save_location_meta', 1, 2 );
function save_location_meta($post_id, $post){
	if( 'location' != get_post_type($post_id) ) return; 
	
	if( !isset( $_POST['location_nonce'] ) ) return $post_id; 
	
	if( !wp_verify_nonce( $_POST['location_nonce'], 'location_save' ) )
		die( 'Could not verify request...' ); 
		
	 if ( !current_user_can( 'edit_post', $post->ID ))
	 	return $post->ID;
	 	
	 update_post_meta( $post_id, '_location_street', 
	 	isset($_POST['_location_street']) ? $_POST['_location_street'] : '' ); 
	 update_post_meta( $post_id, '_location_city', 
	 	isset($_POST['_location_city'])   ? $_POST['_location_city']   : '' ); 
	 update_post_meta( $post_id, '_location_state', 
	  	isset($_POST['_location_state'])  ? $_POST['_location_state']  : '' ); 	  
	 update_post_meta( $post_id, '_location_zip', 
	   	isset($_POST['_location_zip'])    ? $_POST['_location_zip']    : '' ); 
}

function edit_location_menu() {  
    global $menu;  
    global $submenu;  
    remove_menu_page('edit.php?post_type=location');
}  
add_action( 'admin_menu', 'edit_location_menu' );


add_filter( 'manage_edit-location_columns', 'jp_edit_location_cols' ) ;

function jp_edit_location_cols( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Location' ),
		'address' => __( 'Address' ),
	);

	return $columns;
}

add_action( 'manage_location_posts_custom_column', 'jp_manage_location_col', 10, 2 );
function jp_manage_location_col( $col, $post_id ){
		switch( $col ){
			case "address" : 
				echo get_post_meta( $post_id, '_location_street', true ) . ' ' . get_post_meta( $post_id, '_location_city', true ) . ' ' . get_post_meta( $post_id, '_location_state', true ) . ' ' . get_post_meta( $post_id, '_location_zip', true ); 
			break; 
			default: 
			break; 
		}
}







