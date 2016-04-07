<?php

register_nav_menus( array(
	'header_nav' => 'Header Nav',
	'secondary_nav' => 'Secondary Nav'
));

function get_header_nav(){
    $menu_name = 'header_nav';

    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
	
		$menu_items = wp_get_nav_menu_items($menu->term_id);
	
		$menu_list = '<ul id="menu-' . $menu_name . '">';
	
		foreach ( (array) $menu_items as $key => $menu_item ) {
		    $title = $menu_item->title == 'Teams' ? 'Teams <b class="caret"></b>' : $menu_item->title;		    
		    $url = $menu_item->url;		    
			$menu_list .= '<li><a href="' . $url . '">' . $title . '</a></li>';
		}
		
		$menu_list .= '</ul>';
    } 
    else {
		$menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
    }
    
    return $menu_list; 
}

//add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
function special_nav_class($classes, $item){
     if($item->title == "Teams"){ //Notice you can change the conditional from is_single() and $item->title
             $classes[] = 'dropdown';
     }
     return $classes;
}

add_filter('the_title', 'menu_title', 1, 2); 
function menu_title( $title = null, $id = null ){
	if($title == 'Teams' || $title == 'About Us' )
		return $title . ' <b class="caret"></b>';
	else
		return $title; 
}


class Dropdown_Nav_Walker extends Walker_Nav_Menu {
  
// add classes to ul sub-menus
function start_lvl( &$output, $depth, $args = array() ) {
    // depth dependent classes
    $indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); // code indent
    $display_depth = ( $depth + 1); // because it counts the first submenu as 0
    $classes = array(
        'sub-menu dropdown-menu ',
        ( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
        ( $display_depth >=2 ? 'sub-sub-menu' : '' ),
        'menu-depth-' . $display_depth
        );
    $class_names = implode( ' ', $classes );
  
    // build html
    $output .= "\n" . $indent . '<ul class="' . $class_names . '">' . "\n";
}


// add main/sub classes to li's and links
 function start_el( &$output, $item, $depth, $args ) {
    global $wp_query;
    $indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' ); // code indent
  
    // depth dependent classes
    $depth_classes = array(
        ( $depth == 0 ? 'main-menu-item' : 'sub-menu-item' ),
        ( $depth >=2 ? 'sub-sub-menu-item' : '' ),
        ( $depth % 2 ? 'menu-item-odd' : 'menu-item-even' ),
        'menu-item-depth-' . $depth
    );
    $depth_class_names = esc_attr( implode( ' ', $depth_classes ) );
  
    // passed classes
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    $class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );
  
    // build html
    $output .= $indent . '<li id="nav-menu-item-'. $item->ID . '" class="' . $depth_class_names . ' ' . $class_names . '">';
  
    // link attributes
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
    
    if( $depth == 0 && $item->classes[0] == 'dropdown' ){
	    $attributes .= 'data-toggle="dropdown"';
		$attributes .= ' class="menu-link dropdown-toggle ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . '"';
	}
  
    $item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
        $args->before,
        $attributes,
        $args->link_before,
        apply_filters( 'the_title', $item->title, $item->ID ),
        $args->link_after,
        $args->after
    );
  
    // build html
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
}
}