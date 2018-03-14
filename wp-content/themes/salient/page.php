<?php get_header(); ?>

<?php nectar_page_header($post->ID); ?>

<div class="container-wrap">
<div class="container logocentered">
<!--div id="logohover"><img src="/wp-content/themes/salient/img/homebadge.png" width="100%" height="auto" alt="Hot Tub Liquidators Logo"></div-->
<div id="cshover"><img src="/wp-content/themes/salient/img/coastspas_logo.png" width="100%" height="auto" alt="Coast Spas Logo"></div>
</div>
	<div class="main-content">
		
		<div class="row">
			
			<?php 
			 //buddypress
			 global $bp; 
			 if($bp && !bp_is_blog_page()) echo '<h1>' . get_the_title() . '</h1>'; ?>
			
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				
				<?php the_content(); ?>
	
			<?php endwhile; endif; ?>
				
	
		</div><!--/row-->
		
	</div><!--/container-->
	
</div>
<?php get_footer(); ?>