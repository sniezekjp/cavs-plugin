<?php

add_shortcode( 'cavs_youtube', 'show_cavs_videos' ); 

function show_cavs_videos( $atts ){

    //if(is_user_logged_in())
        return "<h1 style='text-align:center; '>Coming soon!</h1>";
	
	$data = json_decode( file_get_contents('https://gdata.youtube.com/feeds/api/users/novacavs/uploads?v=2&alt=jsonc') ); 
	
	echo "<pre>";
		//var_dump($data->data);	
	echo "</pre>";
	
	$x = 1;
	$cols = 3; 
	$output = '';
	
	if( 0 != $data->data->totalItems ){
		foreach( $data->data->items as $video ){		    
		    $desc  = $video->description;
		    
		    $output .= '<div class="one-third column">'; 
		    $output .= '<h4>'.$desc.'</h4>';
		    $output .= '<img src="'. $video->thumbnail->hqDefault.'" alt="'.$desc.'" />'; 
		    $output .= '</div>';
		    
/*
			$last = 'no';		
			if( 0 == ($x % $cols) ){
				$last = 'yes'; 
			}				
			$item  = '[one_third last="'.$last.'"]';
			$item .= '<div id="youtube" class="portfolio-item">';
			$item .= '<div class="image"><a href="'.$video->player->default.'" rel="prettyPhoto[yt_gallery]">';
			$item .= '<img src="'.$video->thumbnail->hqDefault.'" alt="'.$video->description.'" /></a></div>';
			$item .= '<div class="portfolio-content" style="margin-top:5px; "><h2><a href="'.$video->player->default.'" rel="prettyPhoto[yt_gallery]">' . $video->description . '</a></h2></div>';	
			$item .= '</div>';
			$item .= '[/one_third]';
			
			$output .= do_shortcode( $item );
*/
	
			$x++; 
		}
	}
	else{
		$output = '<h2>Could not find any videos for this user and category.</h2>';
	}
	
	return $output; 
}