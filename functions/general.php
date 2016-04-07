<?php

function renew_images( $url, $old_url ){
	global $post; 
	$q = get_posts('post_type=page&posts_per_page=-1' ); 
	foreach( $q as $post ){
		setup_postdata($post); 	
		$content = str_replace( $old_url, $url , $post->post_content ); 	
		$post->post_content = $content; 
		wp_update_post( $post ); 	
	}
}

function get_label( $key ){
		return ucwords( str_replace( '_', ' ', $key ) ); 
}

function get_post_var( $key ){		
		return isset( $_POST[ $key ] ) ? $_POST[ $key ] : ''; 
}

function has_errors(){
	global $errors; 
	if( isset($errors) && count( $errors ) > 0 )
		return true; 
		
	return false; 
}

function show_original_value( $key ){
		if( !has_errors() )
			return; 
		
		return get_post_var( $key ); 
}
function is_empty( $key ){
		global $errors; 
		if( isset($errors[$key]) && 'empty' == $errors[$key] ){
			return true; 
		}
		
		return false; 
		
}
