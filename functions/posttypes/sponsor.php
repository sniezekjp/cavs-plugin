<?php

function sponsor_post_type() {
  $labels = array(
    'name' => 'Sponsors',
    'singular_name' => 'Sponsor',
    'add_new' => 'Add Sponsor',
    'add_new_item' => 'Add New Sponsor',
    'edit_item' => 'Edit Sponsor',
    'new_item' => 'New Sponsor',
    'all_items' => 'All Sponsors',
    'view_item' => 'View Sponsor',
    'search_items' => 'Search Sponsors',
    'not_found' =>  'No sponsors found',
    'not_found_in_trash' => 'No sponsors found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Sponsors'
  );

  $args = array(
    'labels' => $labels,
    'public' => false,
    'publicly_queryable' => false,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'sponsor' ),
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => 100,
    'supports' => array( 'title', 'editor', 'thumbnail' )
  ); 

  register_post_type( 'sponsor', $args );
}
add_action( 'init', 'sponsor_post_type' );


add_action( 'add_meta_boxes', 'add_sponsor_meta' ); 
function add_sponsor_meta(){
	add_meta_box('sponsor_address', 'Sponsor URL', 'show_sponsor_address_meta_box', 'sponsor', 'side', 'default');
}

function show_sponsor_address_meta_box(){
    global $post;
	wp_nonce_field( 'sponsor_save', 'sponsor_nonce' );

    $url = get_post_meta($post->ID, '_sponsor_url', true);
	?>
		<table>
			<tr>
				<td><label for="sponsor_url">URL: </label></td>
				<td><input class="widefat" type="text" id="sponsor_url" name="_sponsor_url" value="<?php echo $url; ?>" /></td>
			</tr>
		</table>
	<?php
}

add_action( 'save_post', 'save_sponsor_meta', 1, 2 );
function save_sponsor_meta($post_id, $post){
	if( 'sponsor' != get_post_type($post_id) ) return; 
	
	if( !isset( $_POST['sponsor_nonce'] ) ) return $post_id; 
	
	if( !wp_verify_nonce( $_POST['sponsor_nonce'], 'sponsor_save' ) )
		die( 'Could not verify request...' ); 
		
	 if ( !current_user_can( 'edit_post', $post->ID ))
	 	return $post->ID;
	 	
	 update_post_meta( $post_id, '_sponsor_url', isset($_POST['_sponsor_url']) ? $_POST['_sponsor_url'] : '' ); 

	
}


function edit_sponsor_menu() {  
    global $menu;  
    global $submenu;  
    remove_menu_page('edit.php?post_type=sponsor');
}  
add_action( 'admin_menu', 'edit_sponsor_menu' );


add_filter( 'manage_edit-sponsor_columns', 'jp_edit_sponsor_cols' ) ;

function jp_edit_sponsor_cols( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Sponsor' ),
		'sponsor_url' => __( 'URL' ),
	);

	return $columns;
}

add_action( 'manage_sponsor_posts_custom_column', 'jp_manage_sponsor_col', 10, 2 );
function jp_manage_sponsor_col( $col, $post_id ){
		switch( $col ){
			case "sponsor_url" : 
				echo '<a target="_blank" href="'.get_post_meta( $post_id, '_sponsor_url', true ).'">'.get_post_meta( $post_id, '_sponsor_url', true ).'</a>'; 
			break; 
			default: 
			break; 
		}
}



