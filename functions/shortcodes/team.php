<?php

add_shortcode('team_events', 'show_team_events'); 

function show_team_events( $atts, $content = null ){
	//$events = new WP_Query('post_type=event');
	global $wp_query; 
	extract( shortcode_atts(
		array(
			'type' => 'game',
			'title' => 'Game Schedule'
		), $atts)
	);
	
	$args = array(
		'type' => $type,
		'orderby' => 'meta_value',
		'meta_key' => '_event_date',
		'order' => 'ASC'
	);
	p2p_type( 'team_to_event' )->each_connected( $wp_query, $args, 'events' );
	
	ob_start(); ?>	
	<h3><?php echo $title; ?></h3>	
	<table class="table">
		<tr>
			<td>Date</td>
			<td>Time</td>
			<td>Location</td>
			<td>Opponent</td>
		</tr>	
	<?php
	if( $events->have_posts() ) : while( $events->have_posts() ) : $events->the_post(); ?>
        	<?php p2p_type( 'location_to_event' )->each_connected( $post->games, array(), 'location' ); ?>
        	
        	<?php if( count( $post->events ) > 0 ) : ?>
        	<?php foreach( $post->events as $post ) : setup_postdata($post); ?>
        		<?php 
        			$against  = get_post_meta( $post->ID, '_event_opponent', true ); 
            		$time     = get_post_meta( $post->ID, '_event_time', true );	                		
            		$date     = get_post_meta( $post->ID, '_event_date', true );	
        			$location = 'TBD'; 
        			if( count($post->location) > 0 ) : 
        				foreach( $post->location as $post ) : 
        					setup_postdata($post); 
        					$location = '<a href="'.get_permalink().'">' . get_the_title() . '</a>' ; 
        				endforeach; 
        			endif;                			  
        		?>                		                			                		
            		<tr>
            			<td><?php echo date( 'm/d', strtotime($date) ); ?></td>
            			<td><?php echo $time; ?></td>
            			<td><?php echo $location; ?></td>
            			<td><?php echo $against; ?></td>
            		</tr>                		
        		
			<?php endforeach; wp_reset_postdata(); endif;  ?>		
	</table>
	<?php
	endwhile; endif; 
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents; 
}
