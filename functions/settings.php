<?php
add_action( 'admin_menu', 'register_my_custom_menu_page' );

function register_my_custom_menu_page(){
    add_menu_page( 'NOVA Cavs Settings', 'NOVA Cavs', 'settings_cap', 'cavs-settings', 'my_custom_menu_page', '', '2.2' ); 
    
    $cap = 'level_9';
    
    if(is_super_admin()){
        $data = get_userdata( get_current_user_id() );
        //echo $data->caps;
        //echo "<pre>";
        //var_dump($data);
    }
	
	add_submenu_page(
	    'cavs-settings',
	    'Events', /*page title*/
	    'Events', /*menu title*/
	    $cap, /*roles and capability needed*/
	    'edit.php?post_type=event',
	    '' /*replace with your own function*/
	);
	
	add_submenu_page(
	    'cavs-settings',
	    'New', /*page title*/
	    'Locations', /*menu title*/
	    $cap, /*roles and capability needed*/
	    'edit.php?post_type=location',
	    '' /*replace with your own function*/
	);
	
	add_submenu_page(
	    'cavs-settings',
	    'Sponsors', /*page title*/
	    'Sponsors', /*menu title*/
	    $cap, /*roles and capability needed*/
	    'edit.php?post_type=sponsor',
	    '' /*replace with your own function*/
	);
	
	add_submenu_page(
	    'cavs-settings',
	    'Teams', /*page title*/
	    'Teams', /*menu title*/
	    $cap, /*roles and capability needed*/
	    'edit.php?post_type=team',
	    '' /*replace with your own function*/
	);	
	
	add_submenu_page(
	    'cavs-settings',
	    'Interested Players', /*page title*/
	    'Interested Players', /*menu title*/
	    $cap, /*roles and capability needed*/
	    'edit.php?post_type=player',
	    '' /*replace with your own function*/
	);			
	
	
}

function my_custom_menu_page(){?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div>
<h2>NOVA Cavs Settings Page</h2>
</div>

<?php
}



add_action( 'admin_menu', 'edit_nova_cavs_menus', 100 ); 
function edit_nova_cavs_menus() {
	if( isOnNovaCavsPage() ){
		addOpenMenuClass(); 
	}
}  


add_action('in_admin_footer', 'add_admin_script'); 
function add_admin_script(){
	if( isOnNovaCavsPage() ) {
		removeNotCurrentSubmenu();
	}
		
}

function isOnNovaCavsPage(){
		global $pagenow; 
		$pages = array( 'location', 'event', 'player', 'sponsor', 'team' ); 
		
		if( 'edit.php' == $pagenow ){
				if( isset( $_GET['post'] ) ){
					return true; 
				}
				else if( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], $pages ) ){
					return true; 
				}
		}
		else if( 'post.php' == $pagenow ){
				if( isset( $_GET['post'] ) && in_array( get_post_type( $_GET['post'] ), $pages ) ){
					return true; 
				}
		}
		else{
			return false; 
		}
}

function removeNotCurrentSubmenu(){ ?>
		<script>
			(function($){
				//$( '#toplevel_page_cavs-settings, #toplevel_page_cavs-settings > a ' ).addClass( 'wp-menu-open wp-has-current-submenu' );
				$( '#toplevel_page_cavs-settings, #toplevel_page_cavs-settings > a' ).removeClass( 'wp-not-current-submenu' );				
			})(jQuery)
		</script>		
<?php
}

function addOpenMenuClass(){
	global $menu; 
	$menu['2.2'][4] = $menu['2.2'][4] . ' wp-menu-open wp-has-current-submenu open-if-no-js';	
}

