<?php

function register_customcode_dropdown( $buttons ) {
   array_push( $buttons, "Shortcodes" );
   return $buttons;
}

function add_customcode_dropdown( $plugin_array ) {
   $plugin_array['Shortcodes'] = CAVS_URL . 'assets' . DS . 'js' . DS . 'tinymce-dropdown.js';
   return $plugin_array;
}

function customcode_dropdown() {

   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
      return;
   }

   if ( get_user_option('rich_editing') == 'true' ) {
      add_filter( 'mce_external_plugins', 'add_customcode_dropdown' );
      add_filter( 'mce_buttons', 'register_customcode_dropdown' );
   }

}

add_action('init', 'customcode_dropdown');