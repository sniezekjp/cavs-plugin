<?php

function registration_post_type() {
	$labels = array(
		'name' => 'Registrations',
		'singular_name' => 'Registration',
		'add_new' => 'Add Registration',
		'add_new_item' => 'Add New Registration',
		'edit_item' => 'Edit Registration',
		'new_item' => 'New Registration',
		'all_items' => 'All Registrations',
		'view_item' => 'View Registration',
		'search_items' => 'Search Registrations',
		'not_found' =>  'No Registrations found',
		'not_found_in_trash' => 'No Registrations found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Registrations'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => array( 'slug' => 'registration' ),
		'capability_type' => 'post',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_position' => 2,
		'supports' => array( 'title' )
	); 
	
	register_post_type( 'registration', $args );
	
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Tournaments', 'taxonomy general name' ),
		'singular_name'     => _x( 'Tournament', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Tournaments' ),
		'all_items'         => __( 'All Tournaments' ),
		'parent_item'       => __( 'Parent Tournament' ),
		'parent_item_colon' => __( 'Parent Tournament:' ),
		'edit_item'         => __( 'Edit Tournament' ),
		'update_item'       => __( 'Update Tournament' ),
		'add_new_item'      => __( 'Add New Tournament' ),
		'new_item_name'     => __( 'New Tournament Name' ),
		'menu_name'         => __( 'Tournaments' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'tournament' ),
	);

	register_taxonomy( 'tournament', array( 'registration' ), $args );
	
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Ages', 'taxonomy general name' ),
		'singular_name'     => _x( 'Age', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Ages' ),
		'all_items'         => __( 'All Ages' ),
		'parent_item'       => __( 'Parent Age' ),
		'parent_item_colon' => __( 'Parent Age:' ),
		'edit_item'         => __( 'Edit Age' ),
		'update_item'       => __( 'Update Age' ),
		'add_new_item'      => __( 'Add New Age' ),
		'new_item_name'     => __( 'New Age Name' ),
		'menu_name'         => __( 'Ages' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'age' ),
	);
	register_taxonomy( 'age', array( 'registration' ), $args );
	
	
$labels = array(
		'name'              => _x( 'Levels of Play', 'taxonomy general name' ),
		'singular_name'     => _x( 'Level of Play', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Levels of Play' ),
		'all_items'         => __( 'All Levels of Play' ),
		'parent_item'       => __( 'Parent Level of Play' ),
		'parent_item_colon' => __( 'Parent Level of Play:' ),
		'edit_item'         => __( 'Edit Level of Play' ),
		'update_item'       => __( 'Update Level of Play' ),
		'add_new_item'      => __( 'Add Level of Play' ),
		'new_item_name'     => __( 'New Level of Play' ),
		'menu_name'         => __( 'Levels of Play' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'level-of-play' ),
	);
	register_taxonomy( 'level_of_play', array( 'registration' ), $args );	
}
add_action( 'init', 'registration_post_type' );


add_action( 'add_meta_boxes', 'add_registration_meta' ); 
function add_registration_meta(){
	add_meta_box('registration_info', 'Registration Information', 'show_registration_info_meta_box', 'registration', 'normal', 'high');
}

function show_registration_info_meta_box(){
    global $post;
	wp_nonce_field( 'registration_save', 'registration_nonce' );
	
	$reg = array(
		'contact_name'  => 'Full Name',
		'contact_email' => 'Email',
		'order_id'      => 'Order ID'
	);

	?>
		<table>
		<?php foreach( $reg as $key => $val)  : ?>
			<tr>
				<td><label for="<?php echo $key; ?>"><?php echo $val; ?>: </label></td>
				<td><input class="widefat" type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo esc_attr( get_post_meta( $post->ID, $key, true ) ); ?>" /></td>
			</tr>		
		<?php endforeach; ?>					
		</table>
	<?php
}

add_action( 'save_post', 'save_registration_meta', 1, 2 );
function save_registration_meta($post_id, $post){
	if( 'registration' != get_post_type($post_id) ) return; 
	
	if( !isset( $_POST['registration_nonce'] ) ) return $post_id; 
	
	if( !wp_verify_nonce( $_POST['registration_nonce'], 'registration_save' ) )
		die( 'Could not verify request...' ); 
		
	 if ( !current_user_can( 'edit_post', $post->ID ))
	 	return $post->ID;
	 	
	 update_post_meta( $post_id, 'contact_name', 
	 	isset($_POST['contact_name']) ? $_POST['contact_name'] : '' ); 
	 update_post_meta( $post_id, 'contact_email', 
	 	isset($_POST['contact_email'])   ? $_POST['contact_email']   : '' );
}

function edit_reg_menu() {  
    global $menu;  
    global $submenu;  
    remove_menu_page('edit.php?post_type=location');
}  
//add_action( 'admin_menu', 'edit_location_menu' );


add_filter( 'manage_edit-registration_columns', 'jp_edit_registration_cols' ) ;

function jp_edit_registration_cols( $columns ) {

	$columns = array(
		'cb'            => '<input type="checkbox" />',
		'title'         => __( 'Team' ),
		'coach'         => __( 'Coach' ),
		'contact'       => __( 'Contact Info' ),
		'tournament'    => __( 'Tournament' ),
		'age'           => __( 'Age Group' ),
		'level_of_play' => __('Level of Play'),
		'questions'     => __( 'Questions/Requests' ),
		'order_id'      => __( 'Order ID' )
	);

	return $columns;
}

add_action( 'manage_registration_posts_custom_column', 'jp_manage_registration_col', 10, 2 );
function jp_manage_registration_col( $col, $post_id ){
		switch( $col ){
			case "age" :
			case "tournament" : 
			case "level_of_play" : 
				/* Get the genres for the post. */
				$terms = get_the_terms( $post_id, $col );
	
				/* If terms were found. */
				if ( !empty( $terms ) ) {
	
					$out = array();
	
					/* Loop through each term, linking to the 'edit posts' page for the specific term. */
					foreach ( $terms as $term ) {
						$out[] = $term->name;
					}
	
					/* Join the terms, separating them with a comma. */
					if( $col == 'age' )
						echo join( ', ', $out );
					else
						echo join( '<br /> ', $out );
				}
	
				/* If no terms were found, output a default message. */
				else {
					_e( 'No Assigned ' . ucfirst($col) );
				}
				break;
			case "coach" : 
				$name = get_post_meta( $post_id, 'coach_name', true );
				echo $name;
				break;
			case "contact" : 
				$name  = get_post_meta( $post_id, 'contact_name', true );
				$email = get_post_meta( $post_id, 'contact_email', true );
				$phone = get_post_meta( $post_id, 'contact_phone', true );
				echo $name . '<br />' .$email . '<br />' . $phone; 
				break; 
			case "order_id" : 
				$order_id = get_post_meta( $post_id, 'order_id', true ); 
				$view = add_query_arg( array('post' => $order_id, 'action'=>'edit' ) , admin_url('post.php') );
				echo '<a href="'.$view.'">View Order #'.$order_id.'</a>'; 				
				break; 
			case "address" : 
				echo get_post_meta( $post_id, '_location_street', true ) . ' ' . get_post_meta( $post_id, '_location_city', true ) . ' ' . get_post_meta( $post_id, '_location_state', true ) . ' ' . get_post_meta( $post_id, '_location_zip', true ); 
			break; 
			default: 
				echo get_post_meta( $post_id, $col, true ); 
			break; 
		}
}


add_action( 'restrict_manage_posts', 'add_registration_filters' );

function add_registration_filters(){
		$type = ''; 
		
		$type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : ''; 
		
		if( 'registration' == $type ){
	        $tours = get_terms( array('tournament') ); 
	        ?>
	        <select name="tournament">
	        <option value=""><?php _e('Show all Tournaments', 'cavs'); ?></option>
	        <?php
	            $current_v = isset($_GET['tournament']) ? $_GET['tournament'] : '';
	            foreach ($tours as $tour) {
	                printf
	                    (
	                        '<option value="%s"%s>%s</option>',
	                        $tour->slug,
	                        $tour->slug == $current_v ? ' selected="selected"':'',
	                        $tour->name
	                    );
	                }
	        ?>
	        </select>
	        <?php
		        $ages = get_terms( array('age') ); 
	        ?>
	        <select name="age">
	        <option value=""><?php _e('Show all Ages', 'cavs'); ?></option>
	        <?php
	            $current_v = isset($_GET['age']) ? $_GET['age'] : '';
	            foreach ($ages as $age) {
	                printf
	                    (
	                        '<option value="%s"%s>%s</option>',
	                        $age->slug,
	                        $age->slug == $current_v ? ' selected="selected"':'',
	                        $age->name
	                    );
	                }
	        ?>
	        </select>	        
	     <?php	
	        $level_of_play = get_terms( array('level_of_play') ); 
	        ?>
	        <select name="level_of_play">
	        <option value=""><?php _e('Show all Levels', 'cavs'); ?></option>
	        <?php
	            $current_v = isset($_GET['level_of_play']) ? $_GET['level_of_play'] : '';
	            foreach ($level_of_play as $level) {
	                printf
	                    (
	                        '<option value="%s"%s>%s</option>',
	                        $level->slug,
	                        $level->slug == $current_v ? ' selected="selected"':'',
	                        $level->name
	                    );
	                }
	        ?>
	        </select>	
	        <?php		
		}
}






