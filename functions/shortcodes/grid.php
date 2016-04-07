<?php
add_shortcode( 'half_width', 'do_half_width' ); 
function do_half_width( $atts, $content = null ){
	
	ob_start(); 
?>
	<div class="col-md-6">		
		<?php echo do_shortcode( $content );  ?>		
	</div>	
<?php
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents;
}