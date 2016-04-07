<?php
class Subscribe_Widget extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
			'subscribe_widget', // Base ID
			'Subscribe Widget', // Name
			array( 'description' => __( 'Have users subscribe to your latest posts. ', 'text_domain' ), ) // Args
		);		
	}

	public function widget( $args, $instance ) {	
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title']; ?>
			
			<div class="row">
				<div class="col-md-12">
					<p>Get the latest updates from the <br />NOVA Cavaliers</p>
					<form action="<?php echo home_url('/subscribe/'); ?>" method="post">
						<input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
						<p><input class="input-text" type="text" name="email" id="s2email" value="Enter email" size="20" onfocus="if (this.value == 'Enter email') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Enter email';}"></p>
						<p><input id="" type="submit" class="button small default" name="subscribe" value="Subscribe"></p>
					</form>
				</div>
			</div>	
			
		<?php
			
				
			echo $args['after_widget'];
	}

 	public function form( $instance ) {
		// outputs the options form on admin
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'Subscribe', 'text_domain' );
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php 		
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;		
	}
}