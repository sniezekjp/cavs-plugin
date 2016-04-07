<?php

include_once( 'posttypes' . DS . 'sponsor.php'         ); 
include_once( 'posttypes' . DS . 'team.php'            );
include_once( 'posttypes' . DS . 'event.php'           ); 
include_once( 'posttypes' . DS . 'location.php'        ); 
include_once( 'posttypes' . DS . 'player_interest.php' );
include_once( 'posttypes' . DS . 'post-meta-box.php'   );
include_once( 'posttypes' . DS . 'registration.php'    );

function my_connection_types() {
	p2p_register_connection_type( array(
		'name' => 'location_to_event',
		'from' => 'location',
		'to' => 'event',
		 'title' => array(
		    'from' => 'Event',
		    'to' => 'Location'
		  )
	));
	
	p2p_register_connection_type( array(
		'name' => 'team_to_event',
		'from' => 'team',
		'to' => 'event',
		 'admin_dropdown' => 'any',
		 'title' => array(
		 	'from' => 'Event',
		 	'to' => 'Team'
		 )
	));	
}
add_action( 'p2p_init', 'my_connection_types' );