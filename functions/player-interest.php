<?php

add_shortcode( 'player_interest_form', 'do_player_interest_form' ); 
function do_player_interest_form( $atts, $content = null ){
		global $errors; 
		extract(
			shortcode_atts(array(
				'default' => 'default value'
			), $atts)		
		);
		
		$args = array('full_name', 'current_grade', 'current_school', 'email_address', 'phone_number', 'position', 'teams_played_for', 'height', 'additional_info');
		
		ob_start(); 				
		
		if( isset( $_POST['player_interest_nonce'] ) && isset( $errors ) && count( $errors ) == 0 ) :  ?>
				<div class="">
					<p style="color:green;">Your form has been submitted successfully.</p>
				</div>
			<?php
			
		elseif( count( $errors ) > 0 ) : ?>
			<div>
				<p style="color:red;">Please fill in all the required fields.</p>
			</div>
			<?php
					
		endif; 
		
		?>
		
		<form id="player-interest" action="<?php echo get_permalink(); ?>" method="post">
			<table class="table">
				<?php foreach( $args as $key ) : ?>
				<tr>
					<td>
						<div class="form-group">
							<label for="<?php echo $key; ?>"><?php echo get_required_label($key); ?></label>
						</div>
					</td>
					<td><?php echo get_input($key); ?></td>
				</tr>
				<?php endforeach; ?>
				
				<tr>
					<td>
						<input type="hidden" name="action" value="player_interest_form" />
						<?php wp_nonce_field('player_interest', 'player_interest_nonce');  ?>
					</td>
					<td><input id="submit" type="submit" name="submit" value="Submit" class="btn" /></td>
				</tr>
				
			</table>
			
		</form>
		
		<?php
		$contents = ob_get_contents(); 
		ob_end_clean(); 
		return $contents; 
}

function get_required_label($key){
		if( is_field_required( $key) ){
				return get_label( $key ) . '*'; 
		}
		else{
				return get_label( $key );
		}
}

function get_input( $key ){ 
		$error = is_empty( $key ) ? ' has-error' : ''; 
		switch( $key ){
			case "position" : 
				$html  = '<div class="form-group' . $error . '">';
				$html .= '<select name="'.$key.'" class="form-control">'; 
				$html .='<option value="">-</option>'; 
				$html .='<option value="both" '. selected( show_original_value($key), 'both', false ) .'>Both</option>'; 
				$html .='<option value="perimeter" '. selected( show_original_value($key), 'perimeter', false ) .'>Perimeter</option>'; 
				$html .='<option value="post" '. selected( show_original_value($key), 'post', false ) .'>Post</option>'; 
				$html .= '</select>'; 
				$html .= '</div>';
				return $html; 
				break; 
			
			case "additional_info" : 
				$html = '<textarea name="'.$key.'" id="'.$key.'" cols="30" rows="10" class="input-text">';
				$html .= show_original_value( $key ); 
				$html .= '</textarea>'; 
				return $html; 
				
				break; 
							
			default : 
				$html = '<div class="form-group' . $error . '">';
				$html .= sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s" class="form-control input-text"/> ', $key, show_original_value($key) ); 
				$html .= '</div>'; 
				return $html; 
			
		}
}


add_action('init', 'do_player_interest_form_submit'); 

function do_player_interest_form_submit(){ 
		
		if( !isset( $_REQUEST['player_interest_nonce'] ) )
			return false; 
		
		global $errors; 
		$errors = array(); 
		
		if( !wp_verify_nonce( $_REQUEST['player_interest_nonce'], 'player_interest' ) )
			die('Could not verify request...'); 
			
		$args = array('full_name', 'current_grade', 'current_school', 'email_address', 'phone_number', 'position', 'teams_played_for', 'height', 'additional_info');
		
		foreach( $args as $key ){ 
				if( is_field_required( $key) ){
					 if( !isset( $_POST[ $key ] ) || '' == $_POST[ $key ] ){
						 $errors[ $key ] = 'empty';  
					 }
				}
		}
		
		if( has_errors() )
			return; 
		
		$data['post_title']  = strip_tags( $_POST['full_name'] ); 
		$data['post_type']   = 'player'; 
		$data['post_status'] = 'publish';
		$id = wp_insert_post( $data ); 
		
		foreach( $args as $arg ){
			update_post_meta( $id, $arg, sanitize_post_field( $arg, get_post_var($arg), $id, 'db' ) ); 
		}
		
		unset( $errors ); 
				
}


function is_field_required( $field ){
		switch( $field ){
			case "additional_info" : 
				return false; 
				break; 
			default :
				return true; 
				break; 
		}
}
