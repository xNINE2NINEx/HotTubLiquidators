<?php 
/*template name: Contact*/
get_header(); ?>


<?php nectar_page_header($post->ID);  ?>

<?php 
$options = get_option('salient'); 

$map_data = null;
$count = 0;

for($i = 1; $i <= 10; $i++){
	if(!empty($options['map-point-'.$i]) && $options['map-point-'.$i] != 0 ) {
		$count++;
		$map_data[$count]['lat'] = $options['latitude'.$i];
		$map_data[$count]['lng'] = $options['longitude'.$i];
		$map_data[$count]['mapinfo'] = $options['map-info'.$i];
	}	
}

function json_map_data() {
	global $map_data; 
	return $map_data;
}

wp_localize_script( 'nectarMap', 'map_data', json_map_data() );

?>

<div class="container-wrap">
	
	<!--div id="contact-map" class="nectar-google-map" data-enable-animation="< ?php if(!empty($options['enable-map-animation'])) echo $options['enable-map-animation']; ?>" data-enable-zoom="< ?php if(!empty($options['enable-map-zoom'])) echo $options['enable-map-zoom']; ?>" data-zoom-level="< ?php if(!empty($options['zoom-level'])) echo $options['zoom-level']; ?>" data-center-lat="< ?php if(!empty($options['center-lat'])) echo $options['center-lat']; ?>" data-center-lng="< ?php if(!empty($options['center-lng'])) echo $options['center-lng']; ?>" data-marker-img="< ?php if(!empty($options['marker-img'])) echo $options['marker-img']; ?>"></div-->

	<div class="row">
<iframe src="https://www.google.com/maps/embed?pb=!1m19!1m8!1m3!1d3226.328416535899!2d-115.03006!3d36.03669!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x80c8d113eb8b1d5b%3A0x8d4e18b81875b4d9!2sHOT+TUB+LIQUIDATORS%2C+129+N+Gibson+Rd%2C+Henderson%2C+NV+89014!3m2!1d36.036702999999996!2d-115.029929!5e0!3m2!1sen!2sus!4v1546551535408" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen=""></iframe>
</div>

	
	<div class="container main-content">
		
		<div class="row">
	
			<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				
				<?php the_content(); ?>
	
			<?php endwhile; endif; ?>
				
	
		</div><!--/row-->
		
	</div><!--/container-->

</div>
<?php get_footer(); ?>