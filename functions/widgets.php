<?php

include_once( 'widgets' . DS . 'Sponsor_Widget.php' ); 
include_once( 'widgets' . DS . 'Subscribe_Widget.php' ); 

function register_sponsor_widget() {
    register_widget( 'Sponsor_Widget' );
    register_widget( 'Subscribe_Widget' );
}
add_action( 'widgets_init', 'register_sponsor_widget' );