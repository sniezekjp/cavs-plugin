<?php

add_shortcode('main_logo', 'do_main_logo'); 
add_shortcode('join_now', 'do_join_now'); 
add_shortcode('schedule', 'do_homepage_schedule'); 
add_shortcode('team_news', 'do_team_news'); 
add_shortcode('featured_article', 'do_featured'); 
add_shortcode('sponsors', 'do_sponsors'); 
add_shortcode('section', 'do_section'); 
add_shortcode('subscription_form', 'jp_subscription_form');


//add_action( 'before_homepage_loop', 'filter_content' ); 
function filter_content(){
	remove_action('the_content', 'wpautop'); 
	add_filter('get_the_excerpt', 'featured_excerpt_more_link');
	add_filter('excerpt_more', 'featured_excerpt_more');
}

function featured_excerpt_more($more){
	return ' ... ';
}

function featured_excerpt_more_link($more) {
       global $post;
	   return $more . '<br /><a class="moretag" href="'. get_permalink($post->ID) . '">[Continue reading...]</a>';
}


function do_main_logo( $atts, $content = null ){ 
	extract( shortcode_atts(
		array(
			'style' => ''
		), $atts)
	); 
	
	ob_start(); 
?>

<div class="<?php echo $style == 'dark' ? 'black-bg' : ''  ?>">
	<div id="main-logo" class="container">
	
		<div class="search hidden-sm" id="home-search">
			<form action="<?php echo home_url(); ?>" method="get">
				<input type="text" name="s" value="<?php echo isset( $_GET['s'] ) ? $_GET['s'] : ''; ?>" placeholder="SEARCH" />
			</form>
		</div>	

		<div class="row">
			<div class="col-md-12">
				
				<a href="<?php echo home_url(); ?>">
					<img class="img-responsive" src="<?php echo CAVS_URL . 'images/cavs-logo-main.png'; ?>" alt="NOVA Cavaliers" />
				</a>
				
				<div class="row">
					<h1 class="stand-out">10th Anniversary</h1>
					<p>Northern Virginia Boy's AAU Basketball</p>
				</div><!-- row -->
			
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- main-logo -->
</div><!-- black-bg -->	

<?php
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents; 
}

function do_join_now( $atts, $content = null ){ 
	extract( shortcode_atts(
		array(
			'background' => 'gray'
		), $atts)
	); 
	ob_start(); 
?>
<div id="join" class="<?php echo $background == 'gray' ? 'gray-bg' : ''; ?>">
		<div class="container">
			<div class="row">
				
				<div class="col-md-6 col-sm-6">
					<div class="join-now">
						<h2 class="stand-out">Join the NOVA Cavs</h2>
						<p>Click below for our player registration form</p>
						<a href="<?php echo home_url('player-interest-form') ?>" class="btn btn-cavs">Register Now</a>
					</div><!-- join-now -->
				</div><!-- col-md-6 -->
				
				<div id="bball-logos" class="col-md-6 col-sm-6">
					<div class="row">
						<a style="display:block;" class="col-sm-12" target="_blank" href="http://www.aauboysbasketball.org/">
							<img class="img-responsive pull-left aau-logo" src="<?php echo CAVS_URL . 'images/aau-logo.png'; ?>" alt="NOVA Cavaliers" />
						</a>
						<a style="display:block;" class="col-sm-12" href="<?php echo home_url(); ?>">
							<img class="img-responsive pull-left nova-logo" src="<?php echo CAVS_URL . 'images/cavs-logo-secondary.png'; ?>" alt="NOVA Cavaliers" />				
						</a>
					</div><!-- row -->
				</div><!-- col-md-6 -->
			
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- #join -->	
<?php
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents; 
}

function do_homepage_schedule( $atts, $content = null ){
	global $post; 
	extract( shortcode_atts(
		array(
			'background' => 'gray'
		), $atts)
	); 
	$args = array(
		'post_type' => 'event',
		'type'      => 'game',
		'meta_key' => '_event_date',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_compare' => '>=',
		'meta_query' => array( 
            array(
                'key' => '_event_date', 
                'value' => date("Y-m-d"),
                'compare' => '>=', 
                'type' => 'DATE',
                )
            ),
	);
	
	$q = new WP_Query( $args );
	$item_width   = 120; 
	$events_count = $q->found_posts + 1; 
	$total_width  = $events_count * $item_width; 
	
	p2p_type( 'location_to_event' )->each_connected( $q, array(), 'locations' );
	p2p_type( 'team_to_event' )->each_connected( $q, array(), 'teams' );
	
	ob_start(); 
?>
			<div id="schedule-container">
				<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
				<div class="viewport" <?php get_viewport_style(); ?>>
				<ul id="schedule-list" class="schedule-list overview overview-<?php echo $events_count; ?>" style="width: <?php echo $total_width; ?>px;    <?php get_overview_style(); ?>">				
				<?php 
					while( $q->have_posts() ) : $q->the_post();  
						$id = get_the_ID(); 
						$date = get_post_meta(get_the_ID(), '_event_date', true); 
						$date = date("m/d", strtotime( $date )); 
						$time = get_post_meta( get_the_ID(), '_event_time', true );
						$against = get_post_meta( get_the_ID(), '_event_opponent', true ); 						
						$teams = $post->teams; 						
						
						$team     = 'TBD'; 
						$location = 'TBD'; 
						
						if( count($post->locations) > 0 ){
							foreach( $post->locations as $post ){
								setup_postdata($post); 
								$location = get_the_title(); 
							}
							wp_reset_postdata(); 	
						}			
						
						if( count($teams) > 0 ){
							foreach( $teams as $post ){
								setup_postdata($post); 
								$team = get_the_title(); 
							}
							wp_reset_postdata(); 
						}
				?>
					<li class="one_sixth event game">
						<?php if( is_user_logged_in() && current_user_can('edit_posts') ) : ?>
							<span class="edit-event"><a href="<?php echo get_edit_post_link( $id, '&' ); ?>">{Edit Event}</a></span>
						<?php endif; ?>
						<span class="white"><?php echo $location; ?> <br /><?php echo $date; ?> @ <em><?php echo $time; ?></em></span><br />
						<span><?php echo $team; ?></span><br />
						<?php echo $against; ?>
					</li>
				<?php endwhile;  ?>				
																																				
				</ul><!-- overview -->
				</div><!-- viewport -->
			</div><!-- #schedule-list -->
			<script>
				(function($){
					$( '#schedule-container' ).tinyscrollbar({
						axis: 'x',
						sizethumb: 40,
						invertscroll: false
					});
				})(jQuery)
			</script>
	
<?php
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents;
}

function get_overview_style(){
	if( is_user_logged_in() && current_user_can('edit_posts') ){
		echo ' padding-top: 20px;'; 
	}
}

function get_viewport_style(){
	if( is_user_logged_in() && current_user_can('edit_posts') ){
		echo ' style="height: 120px;"'; 
	}	
}


function do_team_news( $atts, $content = null ){
	global $post; 
	extract( shortcode_atts(
		array(
			'background' => 'gray',
			'category'   => 'team_news',
			'title'      => 'Team News'
		), $atts)
	); 
	
	$news = get_posts('post_type=post&category_name=team-news&posts_per_page=5'); 
	ob_start(); 
?>

<div id="team-news" class="carousel slide">
<div id="news" class="<?php echo $background == 'gray' ? 'gray-bg' : ''; ?>">	
		<div class="container">
			<h2 class="stand-out"><?php echo $title; ?></h2>
			<div class="row">
			
  <!-- Indicators -->
  <ol class="carousel-indicators">
  	<?php $to = 0; while( $to < count($news) )  : ?>
    <li data-target="#team-news" data-slide-to="<?php echo $to; ?>" class="<?php echo $to == 0 ? 'active' : ''; ?>"></li>
    <?php $to++; endwhile; ?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
  
  <?php $x=0; foreach( $news as $post ) : setup_postdata($post); ?>
    <div class="item <?php echo $x == 0 ? 'active' : ''; $x++;  ?>">
				<div class="col-md-6">				
					<div class="post">
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</div>
				</div><!-- col-md-6 -->
				
				<div class="col-md-6">
					
					<?php if( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('home-featured-image', array('class'=> 'img-responsive') ); ?>
						</a>
					<?php endif; ?>
										
				</div><!-- col-md-6 -->
    </div><!-- item -->
	<?php endforeach; wp_reset_postdata(); ?>

</div><!-- carousel-inner -->

  <!-- Controls -->
  <a class="left carousel-control" href="#team-news" data-slide="prev">
    <span class="icon-prev"></span>
  </a>
  <a class="right carousel-control" href="#team-news" data-slide="next">
    <span class="icon-next"></span>
  </a>

				
			
			</div><!-- row -->
		</div><!-- container -->
	
	</div><!-- #new -->	
</div><!-- carousel -->	
<?php
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents;
}

function do_featured( $atts, $content = null ){ 
	global $post; 
	extract( shortcode_atts(
		array(
			'background'    => 'gray',
			'title' => '', 
			'category' => 'featured',
		), $atts)
	); 
	ob_start(); 
	$feats = get_posts('post_type=post&posts_per_page=1&category_name=' . $category);
	if( 0 == count($feats) ) return false; 
?>
<div id="featured" class="<?php echo $background == 'gray' ? 'gray-bg' : ''; ?>">
		<div class="container">
			<?php if( '' != $title ) : ?>
				<h2 class="stand-out"><?php echo $title; ?></h2>
			<?php endif; ?>
			
			<div class="row">
				<?php foreach( $feats as $post ) : setup_postdata($post); ?>
				<div <?php post_class(); ?>>
					<div class="col-md-6">			
						<div class="image-container">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail('large', array('class'=>'img-responsive') ); ?>
							</a>						
						</div>				
					</div><!-- col-md-6 -->
					
					<div id="" class="col-md-6">
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php echo do_shortcode( get_the_excerpt() ); ?>
					</div><!-- col-md-6 -->
				</div><!-- post -->
				<?php endforeach; wp_reset_postdata(); ?>
			
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- #join -->	
<?php
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents; 
}



function do_sponsors( $atts, $content = null ){
	extract( shortcode_atts(
		array(
			'background'    => 'gray',
			'title' => 'Cavaliers Sponsors'
		), $atts)
	); 
	ob_start(); 
?>
	
	<div id="sponsors" class="section <?php echo $background == 'gray' ? 'gray-bg' : ''; ?>">
		
		<div class="container">
			<div class="row">
				
				<h2 class="stand-out"><?php echo $title; ?></h2>				
				
				<div class="col-md-6">
					<?php echo do_shortcode( $content ); ?>
				</div><!-- col-md-6 -->
				
				<div class="col-md-6 ">
					
					<ul class="pull-left">
					<?php $attr['class'] = 'img-responsive'?>
					<?php global $post; foreach( get_sponsors() as $post ) : setup_postdata($post);   ?>
						<li class="col-md-6 col-sm-6">
							<a href="<?php echo get_post_meta( get_the_ID(), '_sponsor_url', true ); ?>" class="thumbnail" target="_blank">								
								<?php the_post_thumbnail( 'medium', $attr ); ?>
							</a>
						</li>												
					<?php endforeach; wp_reset_postdata(); ?>
					</ul>

				</div><!-- col-md-6 -->
			
			</div><!-- row -->		
		</div><!-- container -->
	
	</div><!-- sponsors -->
	
<?php	
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents;
}


function do_section( $atts, $content = null ){
		extract( shortcode_atts(
		array(
			'background'    => 'gray',
			'title' => 'A special thanks to our sponsors'
		), $atts)
	); 
	ob_start();
?>

	<div class="section <?php echo $background == 'gray' ? 'gray-bg' : ''; ?>">
		<div class="container">
			<div class="row">
				<h1 class="stand-out"><?php echo $title; ?></h1>				
				<?php echo do_shortcode( $content ); ?>
			</div>
		
		</div>
	</div>
	
	
<?php	
	$contents = ob_get_contents(); 
	ob_end_clean(); 
	return $contents;	
}

function get_sponsors(){
	return get_posts('post_type=sponsor'); 
}

/*-----------------------------------------------------------------------------------*/
/*	Subscription Shortcode
/*-----------------------------------------------------------------------------------*/

function jp_subscription_form( $atts, $content = null ) {
    extract(shortcode_atts(array(
    'type'	=> 'game',
    ), $atts));

	$form = '<form id="subscribe-now" action="'.home_url('/subscribe/').'" method="post">';
	$form .= '<input type="hidden" name="ip" value="'.$_SERVER['REMOTE_ADDR'].'" />';
	$form .= '<input class="alignleft" style="width:55%" type="text" name="email" id="s2email" value="Enter email" size="20" onfocus="if (this.value == \'Enter email\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Enter email\';}">';
	//$form .= do_shortcode('[button link="#" size="medium" target="self"]Go![/button]');
	$form .= '<input id="subscribe-btn" class="button" type="submit" name="subscribe" value="Subscribe" />'; 
	$form .= '</form>';

    return $form;
}