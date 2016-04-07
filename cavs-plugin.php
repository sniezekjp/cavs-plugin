<?php
/*
Plugin Name:  Cavs Plugin
Plugin URI:   http://jps26.com/
Description:  Plugin Description
Version:      0.1
Author:       26 Media, LLC
Author URI:   http://jps26.com/
*/

//error_reporting( E_ALL ^ E_STRICT );


//add_action( 'init', 'construction' ); 
function construction(){
	if( !is_user_logged_in() ){
		exit('Site is currently under construction.'); 
	}
}

//add_action( 'after_setup_theme', 'do_admin_email' ); 
function do_admin_email(){
    update_option('admin_email', 'novacavs@gmail.com'); 
/*
	if( is_super_admin() ){
		update_option('admin_email', 'support@jps26.com'); 
	}
*/
}

if( !defined('DS') )
	define('DS', DIRECTORY_SEPARATOR ); 

define( 'FNS_DIR', dirname( __FILE__ ) . DS );
define( 'CAVS_URL', plugins_url('cavs-plugin') . DS ); 

add_theme_support( 'woocommerce' );

include_once( 'shortcodes.php' );

include_once( FNS_DIR . 'functions' . DS . 'logger.php'          );
include_once( FNS_DIR . 'functions' . DS . 'general.php'         );
include_once( FNS_DIR . 'functions' . DS . 'support.php'         );
include_once( FNS_DIR . 'functions' . DS . 'settings.php'        );
include_once( FNS_DIR . 'functions' . DS . 'scripts.php'         );
//include_once( FNS_DIR . 'functions' . DS . 'menus.php'           );
include_once( FNS_DIR . 'functions' . DS . 'sidebar.php'         );
include_once( FNS_DIR . 'functions' . DS . 'shortcodes.php'      );
include_once( FNS_DIR . 'functions' . DS . 'posttypes.php'       );
include_once( FNS_DIR . 'functions' . DS . 'widgets.php'         );
include_once( FNS_DIR . 'functions' . DS . 'social.php'          );
include_once( FNS_DIR . 'functions' . DS . 'post.php'            );
include_once( FNS_DIR . 'functions' . DS . 'player-interest.php' );
include_once( FNS_DIR . 'functions' . DS . 'custom-cart.php'     );
include_once( FNS_DIR . 'functions' . DS . 'tinymce.php'         );
include_once( FNS_DIR . 'functions' . DS . 'youtube.php'         );


//add_action( 'init', 'jp_featured_image' ); 
function jp_featured_image(){
$q = get_posts('post_type=post&posts_per_page=-1'); 
foreach( $q as $post ){
	setup_postdata( $post ); 
	delete_post_thumbnail( $post ); 
}
}

add_filter('latest_news_title', 'customize_latest_news_title'); 
function customize_latest_news_title( $title ){
	global $paged; 
	
	if( $paged )
		return 'Latest News - ' . $paged; 
	else
		return 'Latest News';
}


//add_filter('excerpt_more', 'do_excerpt_more_link');
function do_excerpt_more_link( $link ){
	global $post; 
	return ' ... <br /><span class="more-link"> <a href="'.get_permalink( $post->ID ).'" class="more-link">'.apply_filters('the_content_more_link', 'Read More').'</a></span>';
}


function searchfilter($query) {
    if ($query->is_search && !is_admin() ) {
        $query->set('post_type',array('post'));
    }
return $query;
}
add_filter('pre_get_posts','searchfilter');



function remove_portfolio_titles( $title ){
	return '';
}

function logger_play(){
	global $woocommerce; 
	$log = $woocommerce->logger(); 
	$log->add('debug', 'add message here');
}

function annointed_admin_bar_remove() {
        global $wp_admin_bar;

        /* Remove their stuff */
        $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);


function remove_footer_admin () {
	return '';
}
add_filter('admin_footer_text', 'remove_footer_admin');


function remove_loop_button(){
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');