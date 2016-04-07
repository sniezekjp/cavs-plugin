<?php

/* NOTE: Update nonce name and action after domain mapping!!! */

class Tournament {
		private $_nonce_name   = 'reg_tour_n_field'; 
		private $_nonce_action = 'reg_tour_nonce'; 
		private $_action       = 'register_team'; 
		private $_fields       = array(); 
		private $_errors       = false;
		private $_fee_id       = '1757';
		
		public function __construct(){
				
				add_action( 'init', array($this, 'processForm') ); 
				add_action( 'woocommerce_cart_emptied', array($this, 'unsetRegistrationSession') ); 
				add_action( 'woocommerce_new_order', array($this, 'registerTeams' ) ); 
				add_shortcode( 'tournament_form', array( $this, 'showForm' ) ); 
				
				$this->_fields = array( 
					'coach_name'    => 'Coach\'s Name', 
					'team_name'     => 'Team Name',
					'contact_name'  => 'Contact Name',
					'contact_email' => 'Contact Email',
					'contact_phone' => 'Contact Phone #',
					'tournament'    => 'Tournament',
					'age'           => 'Age Group',
					'level_of_play' => 'Level of Play',
					'questions'     => 'Questions or <br />Scheduling Requests'
				); 
		}
		public function showForm($attrs){

            if(!is_super_admin()) {
                //return "<p>Under construction.</p>";
            }

            $a = shortcode_atts( array(
                'price' => '$330'
            ), $attrs );
				
				$html  = '<h4>Tournament Fee: '.$a['price'].'/team</h4>';
				$html .= '<p>Pay by check or credit card.</p>';
				
				if( $this->_errors ){
					$html .= '<p style="color:red;">All fields are required.</p>';
				}
														
				$html .= '<form id="tournament" action="#" method="post">';
				$html .= '<div class="tournament-table"><table>'; 
				foreach( $this->_fields as $key => $val ){
					$html .= '<tr>'; 
						$html .= '<td>';
							$html .= '<label for="'.$key.'">'.$val.'</label>'; 
						$html .= '</td>';  					
						$html .= '<td>';
							$html .= $this->_getInputField( $key ); 
						$html .= '</td>';
					$html .= '</tr>';
				}				
				$html .= '<tr><td></td><td><input type="submit" id="submit" class="btn" value="Register & Pay"/></td></tr>';
				$html .= '</table></div>';
				$html .= $this->_getNonceFields() . '</form>';
				
				$contents = ''; 
				if( is_super_admin() ){
					ob_start(); 								
						echo '<pre>'.$this->_showFormData().'</pre>'; 
						$contents = ob_get_contents(); 
					ob_end_clean(); 
				}
											
				return $html . $contents ;  			
		}
		
		private function _getInputField( $key ){
				switch( $key ){
					case "tournament" : 
					case "level_of_play":
					case "age":
						$t = get_terms( $key, array('hide_empty' => false) ); 
						$output = '<select name="_team['.$key.']" id="'.$key.'">';
						$selected = isset( $_POST['_team'][$key] ) ? $_POST['_team'][$key] : '';  
						$sel = '';
						$output .= '<option value="-">-</option>';
						foreach( $t as $t ){
                            if($key === "tournament" && $t->slug !== 'battle-of-nova-2016-2') {
                                continue;
                            } else {
                                if($t->name === "-") { continue; }
                            }
							if( $t->slug == $selected )
								$sel = 'selected="selected" '; 
											
							$output .= '<option '.$sel.' value="'.$t->slug.'">'.$t->name.'</option>'; 
							$sel = ''; 
						}
						$output .= '</select>';
						return $output; 
						break;
					case "questions" : 
						return '<textarea class="input-text" name="_team[questions]" id="questions" cols="30" rows="10">None</textarea>';
						break; 
					default :
						$val = isset( $_POST['_team'][$key] ) ? $_POST['_team'][$key] : ''; 
						return '<input type="text" class="input-text" id="'.$key.'" name="_team['.$key.']" value="'.$val.'"/>'; 
						break; 
				}
		}				
		
		public function processForm(){
				global $woocommerce;
				
				//added development functionality
				if( isset( $_GET['clear_cart'] ) && true == $_GET['clear_cart']){
						woocommerce_empty_cart(); 
				}
				if( isset( $_GET['fill_cart'] ) && true == $_GET['fill_cart']){
						foreach( $this->_fields as $key => $val ){
								if( $key == 'full_name' ){
									$team['_team'][$key] = 'John Sniezek';
								}
								if( $key == 'email' ){
									$team['_team'][$key] = 'sniezekjp@aol.com';
								}
								if( $key == 'team_name' ){
									$team['_team'][$key] = 'NOVA Cavs';
								}
								if( $key == 'tournament' ){
									$team['_team'][$key]['battle-of-nova'] = 'battle-of-nova';																			$team['_team'][$key]['summer-breeze'] = 'summer-breeze';				 
								}
						}
							woocommerce_empty_cart(); 
							$woocommerce->session->_team = $team['_team']; 
							foreach( $team['_team']['tournament'] as $key => $val ){
								$woocommerce->cart->add_to_cart( $this->_fee_id ); 
							}
							wp_redirect( get_permalink( get_option('woocommerce_cart_page_id') ) ); 						
				}				
				//end of development functionality
								 
				if( isset($_POST['_team']) ){
						$this->_validateNonce(); 
						
						if( $this->_validateFields() ){
								woocommerce_empty_cart(); 
								$woocommerce->session->_team = $_POST['_team'];
								$woocommerce->cart->add_to_cart( $this->_fee_id ); 
								wp_redirect( get_permalink( get_option('woocommerce_cart_page_id') ) ); 
								exit; 
						}
						else{
								$this->_errors = true; 								
								return false; 
						}
				}
		}
		
		private function _validateFields(){
				foreach( $this->_fields as $key => $val ){
						if( !isset( $_POST['_team'][$key] ) || empty( $_POST['_team'][$key] )){
								return false; 
						}
						
				}
				return true;
		}
		
		private function _validateNonce(){
				if( !isset( $_POST[$this->_nonce_name] ) || !wp_verify_nonce( $_POST[$this->_nonce_name], $this->_nonce_action ) ){
						exit('There was an error processing your request'); 
				}
				else{
					return true; 
				}
			
		}
		
		
		private function _getNonceFields(){
				return wp_nonce_field( $this->_nonce_action, $this->_nonce_name, true, false );
		}
		
		private function _showFormData(){
				if( isset($_POST['_team']) )
					return print_r( $_POST['_team'], true ); 
		}
		
		public function unsetRegistrationSession(){
				global $woocommerce; 
				unset($woocommerce->session->_team); 
		}
		
		public function registerTeams( $order_id ){				
				global $woocommerce;
						
				$data['post_title']  = wp_strip_all_tags( $woocommerce->session->_team['team_name'] );
				$data['post_type']   = 'registration'; 
				$data['post_status'] = 'publish';							
				
				if( $id = wp_insert_post( $data ) ){
				    
				    update_post_meta( $id, 'coach_name', $woocommerce->session->_team['coach_name'] ); 
					update_post_meta( $id, 'contact_name', $woocommerce->session->_team['contact_name'] ); 
					update_post_meta( $id, 'contact_email', $woocommerce->session->_team['contact_email'] ); 
					update_post_meta( $id, 'contact_phone', $woocommerce->session->_team['contact_phone'] );
					update_post_meta( $id, 'order_id', $order_id ); 
					update_post_meta( $id, 'questions', $woocommerce->session->_team['questions'] ); 
					
					wp_set_object_terms( $id, $woocommerce->session->_team['tournament'], 'tournament', true ); 
					wp_set_object_terms( $id, $woocommerce->session->_team['age'], 'age', true ); 
					wp_set_object_terms( $id, $woocommerce->session->_team['level_of_play'], 'level_of_play', true ); 
				}
				else{
					//add error handler
				}

		}
}

$tournament = new Tournament; 
