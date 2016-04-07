<?php

//add_shortcode('lead', 'do_lead_old');
function do_lead_old( $atts, $content = null ){
	return '<p class="lead">' . $content . '</p>'; 
}

add_shortcode( 'line', 'do_line' ); 
function do_line( $atts, $content = null ){
	return '<hr />';
}