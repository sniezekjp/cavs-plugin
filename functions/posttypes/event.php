<?php
function event_post_type(){
	$labels = array(
		'name' => 'Events',
		'singular_name' => 'Event',
		'add_new' => 'Add Event',
		'add_new_item' => 'Add New Event',
		'edit_item' => 'Edit Event',
		'new_item' => 'New Event',
		'all_items' => 'All Events',
		'view_item' => 'View Event',
		'search_items' => 'Search Events',
		'not_found' =>  'No events found',
		'not_found_in_trash' => 'No events found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Events'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => false,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => 'event' ),
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => 100,
		'supports' => array( 'title', )
	); 
	
	register_post_type( 'event', $args );
}
add_action( 'init', 'event_post_type' );


add_action( 'add_meta_boxes', 'add_event_meta' ); 
function add_event_meta(){
	add_meta_box('event_info', 'Event Information', 'show_event_info_meta_box', 'event', 'side', 'default');
}

function show_event_info_meta_box(){
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
				<td><label for="event_opponent">Opponent: </label></td>
				<td><input class="widefat" type="text" id="event_opponent" name="_event_opponent" value="<?php echo $against; ?>" /></td>								
			</tr>
		</table>
		
		<script>
			(function($){
				$( '#event_date' ).datepicker( { dateFormat: "yy-mm-dd" }); 				
			})(jQuery)
		</script>
	<?php
}

add_action( 'save_post', 'save_event_meta', 1, 2 );
function save_event_meta($post_id, $post){
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


add_action( 'init', 'add_event_taxonomy', 0 );
function add_event_taxonomy(){
	$labels = array(
		'name'              => __( 'Type'),
		'singular_name'     => __( 'Type' ),
		'search_items'      => __( 'Search Type' ),
		'all_items'         => __( 'All Types' ),
		'parent_item'       => __( 'Parent Type' ),
		'parent_item_colon' => __( 'Parent Type:' ),
		'edit_item'         => __( 'Edit Type' ),
		'update_item'       => __( 'Update Type' ),
		'add_new_item'      => __( 'Add New Type' ),
		'new_item_name'     => __( 'New Type' ),
		'menu_name'         => __( 'Type' ),
	);
	
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'type' ),
	);
	
	register_taxonomy( 'type', array( 'event' ), $args );
}


function edit_admin_menus() {  
    global $menu;  
    global $submenu;  
    remove_menu_page('edit.php?post_type=event');
}  
add_action( 'admin_menu', 'edit_admin_menus' ); 


add_filter( 'manage_edit-event_columns', 'jp_edit_event_cols' ) ;

function jp_edit_event_cols( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event' ),
		'location' => __( 'Location' ),
		'_event_date' => __( 'Event Date' ),
		'_event_time' => __( 'Event Time' ),
		'_event_opponent' => __( 'Event Opponent' ),
	);

	return $columns;
}

add_action( 'manage_event_posts_custom_column', 'jp_manage_event_col', 10, 2 );
function jp_manage_event_col( $col, $post_id ){
		switch( $col ){
			case "_event_date" : 
				echo get_post_meta( $post_id, '_event_date', true ); 
			break;

			case "_event_time" : 
				echo get_post_meta( $post_id, '_event_time', true ); 
			break;	

			case "_event_opponent" : 
				echo get_post_meta( $post_id, '_event_opponent', true ); 
			break;		

			case "location" :
				global $post; 
				$connected = get_posts( array(
					  'connected_type' => 'location_to_event',
					  'connected_items' => $post,
					  'nopaging' => true,
					  'suppress_filters' => false
				) );
				foreach( $connected as $post ){
					setup_postdata($post); 
					echo '<a href="'. admin_url('post.php?post='.get_the_ID().'&action=edit') .'">'.get_the_title().'</a>'; 
				}
				wp_reset_postdata(); 
			break; 				 
			default: 
			break; 
		}
}