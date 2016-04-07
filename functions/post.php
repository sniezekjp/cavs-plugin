<?php


function do_post_loop(){?>
						<div <?php post_class('clearfix'); ?>>
						<p class="post-meta"><?php echo get_the_date(); ?></p>
						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="row">							
							<?php if( has_post_thumbnail() ) : ?>
								
								<div class="col-md-6">
									<p><?php echo do_shortcode(get_the_excerpt()); ?></p>
									
									<div class="tags-social">
										<p class="post-tags"><?php the_tags( 'TAGS: <span class="tags">', ', ', '</span>' ); ?></p>
										<?php get_social_media_buttons(); ?>
									</div><!-- tags-social -->

								</div><!-- col-md-6 -->
															
								<div class="col-md-6">
									<a href="<?php the_permalink(); ?>">
										<?php  the_post_thumbnail('full', array('class'=>'img-responsive' ) ); ?>
									</a>
								</div><!-- col-md-6 -->
							
							<?php else : ?>
								<div class="col-md-12">
									<p class="post-excerpt"><?php echo do_shortcode(get_the_excerpt()); ?></p>
									<p class="post-tags"><?php the_tags( 'TAGS: <span class="tags">', ', ', '</span>' ); ?></p>
									<?php get_social_media_buttons(); ?>
								</div><!-- col-md-6 -->														
							<?php endif; ?>
							
						</div><!-- row -->
					</div><!-- post -->		
<?php					
}