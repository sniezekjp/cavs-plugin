<?php

add_action( 'woocommerce_checkout_before_customer_details', 'add_player_information', 11 ); 
add_action( 'woocommerce_checkout_update_order_meta', 'save_player_registration_name');
add_action( 'woocommerce_admin_order_data_after_billing_address', 'show_player_name', 10, 1 );
add_action( 'woocommerce_before_add_to_cart_button', 'do_before_add_to_cart' ); 
add_action( 'woocommerce_add_to_cart', 'update_player_registration_name', 10, 6);



add_filter( 'woocommerce_in_cart_product_title', 'add_player_name_to_cart'); 
add_filter( 'woocommerce_order_number', 'use_registered_player_name' ); 



/**
*
* Add player information to the check out page
* @action
*
*/
function add_player_information(){
	global $woocommerce;
	if( !has_player_registration_name() )
		return; 

	echo "<h3>Player Information</h3>";
	echo 'Player name: ';
	echo '<p><input type="text" name="player_name" value="'.$woocommerce->session->player_registration_name.'" /></p>'; 
}



/**
*
* Add player information to the cart page before going to the checkout page
* @filter
*
*/
function add_player_name_to_cart( $title ){
	global $woocommerce; 
	$product = get_page_by_title( $title, 'OBJECT', 'product' ); 
	if( !has_term('player-fee', 'product_cat', $product ) )
		return $title; 
		
	if( !$woocommerce->session->__isset('player_registration_name') )
		return $title; 
	else
		return $title . ' - ' . $woocommerce->session->player_registration_name; 
}




/**
*
* Save Player registration name from the single product page
* @action
*
*/
function save_player_registration_name( $order_id ) {
    if ($_POST['player_name']) update_post_meta( $order_id, 'player_name', esc_attr($_POST['player_name']));
}




/**
*
* Show player name in the order admin page
* @action
*
*/ 
function show_player_name($order){
	if( isset( $order->order_custom_fields['player_name'][0] ) )
   		echo '<p><strong>'.__('Player Name').':</strong> ' . $order->order_custom_fields['player_name'][0] . '</p>';
}


/**
*
* Add Player Name order list page. ie "Order #1111 - Player Name"
* @filter
*
*/
function use_registered_player_name( $title ){
	global $post; 
	$name = get_post_meta( $post->ID, 'player_name', true ) ? get_post_meta( $post->ID, 'player_name', true ) : ''; 
	if( '' != $name ){
		return $title . ' Player - ' . $name; 
	}
	else{
		return $title;
	}
}

/**
*
* Add Player Name input field before the add_to_cart button on the single product page
* @action
*
*/
function do_before_add_to_cart(){
	global $post; 
	$name = isset($_POST['player_name']) ? $_POST['player_name'] : ''; 
		if( is_product() && has_term('player-fee', 'product_cat', $post ) ){
		echo 'Player\'s Name: ';
		echo '<p><input type="text" name="player_name" value="'.$name.'" placeholder="Player\'s Name" /></p>';
	}
}

/**
*
* Save player_name to player_registration_name session variable from add-to-cart form submit
* @filter
*
*/
function update_player_registration_name( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){
	//var_dump($cart_item_key); 
	global $woocommerce; 
	if( isset( $_POST['player_name'] ) ){
		$woocommerce->session->player_name_item_key = $cart_item_key; 
		$woocommerce->session->player_registration_name = $_POST['player_name'];
	}
	return true; 
}



function has_player_registration_name(){
	global $woocommerce; 
	return $woocommerce->session->__isset( 'player_registration_name' ); 
}

function get_player_registration_name(){
	global $woocommerce; 
	return $woocommerce->session->player_registration_name; 
}