<?php

add_action( 'wp_enqueue_scripts', 'load_framework_scripts' );

function load_framework_scripts(){
	
		//wp_enqueue_script( 'bootstrap-js', CAVS_URL . 'assets/js/bootstrap.js' );
		//wp_enqueue_script( 'slimscroll-js', CAVS_URL . 'assets/js/jquery.slimscroll.js' );
		//wp_enqueue_script( 'scroll-horizontal-js', CAVS_URL . 'assets/js/jquery.slimscrollhorizontal.js', array('jquery-ui-draggable') );
		wp_enqueue_script( 'tiny-scroll-js', CAVS_URL . 'assets/js/jquery.tinyscroll.js' );
		wp_register_style('cavs-custom-style', CAVS_URL . 'assets/css/custom.css'); 
		wp_enqueue_style( 'cavs-custom-style' ); 
}


add_action( 'admin_enqueue_scripts', 'load_datepicker' );

function load_datepicker( $page ){
		if( 'post.php' != $page && 'post-new.php' != $page ) return; 
		if( 'event' != get_post_type( get_get_var('post') ) ) return; 
		
		wp_enqueue_script( 'datepicker-js', CAVS_URL . 'assets/js/datepicker.js' );
		
		wp_register_style( 'datepicker-style', CAVS_URL . 'assets/css/datepicker.css' );
		wp_enqueue_style( 'datepicker-style' );
}

function get_get_var( $var ){
		return isset( $_GET[ $var ] ) ? $_GET[ $var ] : ''; 
}


add_action( 'admin_enqueue_scripts', 'admin_settings_icon' );
function admin_settings_icon( $page ){
		if( 'post.php' != $page && 'post-new.php' != $page && 'edit.php' != $page) return; 		
		wp_register_style( 'cavs-setting-icon', CAVS_URL . 'assets/css/admin.css' );
		wp_enqueue_style( 'cavs-setting-icon' );	
}



function themeslug_enqueue_style() {
    wp_enqueue_style('google-font', 'http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic');
	wp_enqueue_style( 'custom-login-style', CAVS_URL . 'assets/css/login.css', false ); 
}
add_action( 'login_enqueue_scripts', 'themeslug_enqueue_style', 10 );

