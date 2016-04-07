<?php
class Sponsor_Widget extends WP_Widget {

	public function __construct() {
		// widget actual processes
		parent::__construct(
			'sponsor_widget', // Base ID
			'List Sponsors', // Name
			array( 'description' => __( 'List Your Sponsors', 'text_domain' ), ) // Args
		);		
	}

	public function widget( $args, $instance ) {	
		global $post; 
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$sponsors = get_posts( 'post_type=sponsor' ); 
		$width = (isset( $instance['show_full_width']) && $instance['show_full_width']  == '') ? 'col-md-6 col-sm-6 col-xs-6' : 'col-md-12 col-sm-12 col-xs-12';
		
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			
			echo '<div class="row">'; 
			foreach( $sponsors as $post ){
				setup_postdata($post);
				?>
				<div id="sponsor-list" class="<?php echo $width; ?>">
					<a class="thumbnail" href="<?php echo get_post_meta($post->ID, '_sponsor_url', true);   ?>">
						<?php
						if( has_post_thumbnail() ) {
							the_post_thumbnail('large', array('img-responsive') ); 				
						}
						?>
					</a>
				</div>
				<?php		 			
			}
			echo "</div>";
			wp_reset_postdata(); 
				
		echo $args['after_widget'];
	}

 	public function form( $instance ) {
		// outputs the options form on admin
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'Sponsors', 'text_domain' );
			}
			if ( isset( $instance[ 'show_full_width' ] ) ) {
				$width = $instance[ 'show_full_width' ];
			}
			else {
				$width = '';
			}			
			?>
			<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_name( 'show_full_width' ); ?>">Full width: </label>
				<input type="checkbox" name="<?php echo $this->get_field_name( 'show_full_width' ); ?>" id="<?php echo $this->get_field_id( 'show_full_width' ); ?>" <?php checked(1, $width);  ?> value="1" />
			</p>
			<?php 		
	}

	public function update( $new_instance, $old_instance ) {
		
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['show_full_width'] = ( ! empty( $new_instance['show_full_width'] ) ) ? 1 : 0;

		return $instance;		
	}
}