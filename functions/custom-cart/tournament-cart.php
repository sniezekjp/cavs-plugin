<?php
add_action( 'woocommerce_before_add_to_cart_button', 'do_before_tournament_add_to_cart' ); 
add_action( 'woocommerce_checkout_update_order_meta', 'save_team_name');
add_action( 'woocommerce_checkout_before_customer_details', 'add_team_info_to_checkout', 11 ); 
add_action( 'woocommerce_admin_order_data_after_billing_address', 'show_team_name_in_order', 10, 1 );
add_action( 'woocommerce_add_to_cart', 'update_team_name', 10, 6);

add_filter( 'woocommerce_in_cart_product_title', 'add_team_name_to_cart');
add_filter( 'woocommerce_order_number', 'use_team_name' ); 
add_filter( 'portfolio_title', 'remove_portfolio_titles', 1); 



/**
*
* Add Tournament Input Field on single product page
* @action
*
*/
function do_before_tournament_add_to_cart(){
	global $post; 
	$name = isset($_POST['team_name']) ? $_POST['team_name'] : ''; 
	if( is_product() && has_term('tournament-fee', 'product_cat', $post ) ){
		echo 'Team Name: ';
		echo '<p><input type="text" name="team_name" value="'.$name.'" placeholder="Team Name" /></p>';
	}
}

/**
*
* Save team_name to team_name session variable from add-to-cart form submit
* @filter
*
*/
function update_team_name( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data  ){
	global $woocommerce; 
	if( isset( $_POST['team_name'] ) ){
		$woocommerce->session->team_name_item_key = $cart_item_key; 
		$woocommerce->session->team_name = $_POST['team_name'];
	}
	return true; 
}

/**
*
* Add team_name to checkout product 
* @filter
*
*/
function add_team_name_to_cart( $title ){
	global $woocommerce; 
	$product = get_page_by_title( $title, 'OBJECT', 'product' ); 
	if( !has_term('tournament-fee', 'product_cat', $product ) )
		return $title; 
			
	if( !isset($woocommerce->session->team_name ) )
		return $title; 
	else
		return $title . ' - ' . $woocommerce->session->team_name; 
}


/**
*
* Save team_name after order has been processed
* @filter
*
*/
function save_team_name( $order_id ) {
    if ($_POST['team_name']) update_post_meta( $order_id, 'team_name', esc_attr($_POST['team_name']));
}


/**
*
* Add team_name to the checkout page
* @filter
*
*/
function add_team_info_to_checkout(){
	global $woocommerce;
	if( !isset( $woocommerce->session->team_name ) )
		return; 

	echo "<h3>Team Information</h3>";
	echo 'Team name: ';
	echo '<p><input type="text" name="team_name" value="'.$woocommerce->session->team_name.'" /></p>'; 
}


/**
*
* Add team_name to the orders list page in the admin area
* @filter
*
*/
function use_team_name( $title ){
	global $post; 
	$name = get_post_meta( $post->ID, 'team_name', true ) ? get_post_meta( $post->ID, 'team_name', true ) : ''; 
	if( '' != $name ){
		return $title . ' Team - ' . $name; 
	}
	else{
		return $title;
	}
}


/**
*
* Add team_name the single order admin page
* @action
*
*/
function show_team_name_in_order($order){
	if( isset( $order->order_custom_fields['team_name'][0] ) )
   		echo '<p><strong>'.__('Team Name').':</strong> ' . $order->order_custom_fields['team_name'][0] . '</p>';
}



