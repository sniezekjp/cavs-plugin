<?php
function team_post_type(){
	$labels = array(
		'name' => 'Teams',
		'singular_name' => 'Team',
		'add_new' => 'Add Team',
		'add_new_item' => 'Add New Team',
		'edit_item' => 'Edit Team',
		'new_item' => 'New Team',
		'all_items' => 'All Teams',
		'view_item' => 'View Team',
		'search_items' => 'Search Teams',
		'not_found' =>  'No teams found',
		'not_found_in_trash' => 'No teams found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Teams'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => 'team' ),
		'capability_type' => 'page',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => 100,
		'supports' => array( 'title', 'editor', 'thumbnail' )
	); 
	
	register_post_type( 'team', $args );
}
add_action( 'init', 'team_post_type' );


add_action( 'add_meta_boxes', 'add_team_meta' ); 
function add_team_meta(){
	//add_meta_box('team_info', 'Event Information', 'show_event_info_meta_box', 'event', 'side', 'default');
}

function show_team_info_meta_box(){
    global $post;
	wp_nonce_field( 'event_save', 'event_nonce' );

   $start   = get_post_meta( $post->ID, '_event_time', true );
   $date    = get_post_meta( $post->ID, '_event_date', true );  
   $against = get_post_meta( $post->ID, '_event_opponent', true );
	?>
		<table>
			<tr>
				<td><label for="event_date">Date: </label></td>
				<td><input class="widefat" type="text" id="event_date" name="_event_date" value="<?php echo $date; ?>" /></td>
			</tr>		
			<tr>
				<td><label for="event_time">Time: </label></td>
				<td><input class="widefat" type="text" id="event_time" name="_event_time" value="<?php echo $start; ?>" /></td>
			</tr>
			<tr>
				<td><label for="event_opponent">Oppenent: </label></td>
				<td><input class="widefat" type="text" id="event_opponent" name="_event_opponent" value="<?php echo $against; ?>" /></td>								
			</tr>
		</table>
	<?php
}

//add_action( 'save_post', 'save_event_meta', 1, 2 );
function save_team_meta($post_id, $post){
	if( 'event' != get_post_type($post_id) ) return; 
	
	if( !isset( $_POST['event_nonce'] ) ) return $post_id; 
	
	if( !wp_verify_nonce( $_POST['event_nonce'], 'event_save' ) )
		die( 'Could not verify request...' ); 
		
	 if ( !current_user_can( 'edit_post', $post->ID ))
	 	return $post->ID;
	 	
	 update_post_meta( $post_id, '_event_date', isset($_POST['_event_date']) ? $_POST['_event_date'] : '' ); 
	 update_post_meta( $post_id, '_event_time', isset($_POST['_event_time']) ? $_POST['_event_time'] : '' ); 
	 update_post_meta( $post_id, '_event_opponent', isset($_POST['_event_opponent']) ? $_POST['_event_opponent'] : '' ); 
	
}


function edit_team_menu() {  
    global $menu;  
    global $submenu;  
    remove_menu_page('edit.php?post_type=team');
}  
add_action( 'admin_menu', 'edit_team_menu' ); 


add_filter( 'manage_edit-team_columns', 'jp_team_cols' ) ;
function jp_team_cols( $columns ){

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Team' ),		
		'date' => __( 'Date' ),
	);

	return $columns;
}
