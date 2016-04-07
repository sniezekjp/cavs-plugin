<?php

function interested_post_type() {
	$labels = array(
		'name' => 'Interested Players',
		'singular_name' => 'Player',
		'add_new' => 'Add Player',
		'add_new_item' => 'Add New Player',
		'edit_item' => 'Edit Player',
		'new_item' => 'New Player',
		'all_items' => 'All Interested Players',
		'view_item' => 'View Player',
		'search_items' => 'Search Interested Players',
		'not_found' =>  'No Interested Players found',
		'not_found_in_trash' => 'No Interested Players found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Interested Players'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => false,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => 'player' ),
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => 100,
		'supports' => array( 'title' )
	); 
	
	register_post_type( 'player', $args );
}
add_action( 'init', 'interested_post_type' );


add_action( 'add_meta_boxes', 'add_interested_player_meta' ); 
function add_interested_player_meta(){
	add_meta_box('player_info', 'Interested Player Information', 'show_player_info_meta_box', 'player', 'normal', 'default');
}

function show_player_info_meta_box(){
    global $post;
	wp_nonce_field( 'player_save', 'player_nonce' );
	
		$args = array('full_name', 'current_grade', 'current_school', 'email_address', 'phone_number', 'position', 'teams_played_for', 'height', 'additional_info');
			
	?>
		<table>
			<?php foreach($args as $arg) : ?>
				<tr>
					<td><label for="<?php echo $arg; ?>"><?php echo get_label($arg);  ?>: </label></td>
					<td><?php echo get_input_field( $arg ); ?></td>
				</tr>		
			<?php endforeach; ?>			
		</table>
	<?php
}

function get_input_field( $key ){
		global $post; 
		switch( $key ){
			case "additional_info" : 
				return '<textarea name="'.$key.'" id="'.$key.'" cols="30" rows="10">'.get_post_meta($post->ID, $key, true).'</textarea>';
				break; 
			default; 
				return '<input type="text" name="'.$key.'" id="'.$key.'" value="'.get_post_meta($post->ID, $key, true).'" />'; 
		}
}


add_action( 'save_post', 'save_interested_player_meta', 1, 2 );
function save_interested_player_meta($post_id, $post){
	if( 'player' != get_post_type($post_id) ) return; 
	
	if( !isset( $_POST['player_nonce'] ) ) return $post_id; 
	
	if( !wp_verify_nonce( $_POST['player_nonce'], 'player_save' ) )
		die( 'Could not verify request...' ); 
		
	 if ( !current_user_can( 'edit_post', $post->ID ))
	 	return $post->ID;
	 	
		$args = array('full_name', 'current_grade', 'current_school', 'email_address', 'phone_number', 'position', 'teams_played_for', 'height', 'additional_info');
		
		foreach( $args as $arg ){				
				update_post_meta( $post_id, $arg, get_post_var($arg) );			
		}	 	
	 	
	 	
}

function edit_player_menu() {  
    global $menu;  
    global $submenu;  
    remove_menu_page('edit.php?post_type=player');
}  
add_action( 'admin_menu', 'edit_player_menu' ); 


add_filter( 'manage_edit-player_columns', 'jp_interested_cols' ) ;

function jp_interested_cols( $columns ) {
	echo 'cols';

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Player' ),
		'current_grade' => __( 'Grade' ),
		'current_school' => __( 'Current School' ),
		'email_address' => __( 'Email Address' ),
		'phone_number' => __( 'Phone Number' ),
		'position' => 'Position',
		'teams_played_for' => 'Teams Played For',
		'height' => 'Height',
		'additional_info' => 'Additional Information',		
	);

	return $columns;
}

add_action( 'manage_player_posts_custom_column', 'jp_manage_interested_col', 10, 2 );
function jp_manage_interested_col( $col, $post_id ){
		switch( $col ){			 
			default: 
				echo get_post_meta( $post_id, $col, true ); 
			break; 
		}
}
