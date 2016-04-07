<?php

include_once( FNS_DIR . 'functions/custom-cart/player-cart.php'     ); 
include_once( FNS_DIR . 'functions/custom-cart/tournament-cart.php' ); 

add_action('woocommerce_cart_emptied', 'remove_custom_session_vars'); 
function remove_custom_session_vars(){
	global $woocommerce; 
	unset( $woocommerce->session->player_registration_name ); 
	unset( $woocommerce->session->player_name_item_key ); 	
	unset( $woocommerce->session->team_name ); 
	unset( $woocommerce->session->team_name_item_key ); 
}

add_action('woocommerce_calculate_totals', 'item_removed_from_cart', 10, 1); 
function item_removed_from_cart( $cart ){
	global $woocommerce; 	
	
	if( $cart->cart_contents[$woocommerce->session->player_name_item_key] == NULL){
		unset( $woocommerce->session->player_registration_name );
		unset( $woocommerce->session->player_name_item_key );	
	}
	
	if( $cart->cart_contents[$woocommerce->session->team_name_item_key] == NULL){
		unset( $woocommerce->session->team_name );
		unset( $woocommerce->session->team_name_item_key );	
	}
}