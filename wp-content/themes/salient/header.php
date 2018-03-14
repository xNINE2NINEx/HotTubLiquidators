<!doctype html>
<html <?php language_attributes(); ?>><head>

<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<?php $options = get_option('salient'); ?>

<?php if(!empty($options['responsive']) && $options['responsive'] == 1) { ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

<?php } else { ?>
	<meta name="viewport" content="width=1200" />
<?php } ?>	

<meta name="description" content="Hot Tub Liquidators is Nevada's Number 1 (#1) spa and hot tub dealer since 2003." />
<meta name="keywords" content="new spas and hot tubs, Las Vegas used hot tubs and spas, Las Vegas spa dealer, Las Vegas hot tub dealer, Nevada spa dealer, Hot Tub Liquidator" />

<!--Shortcut icon-->
<?php if(!empty($options['favicon'])) { ?>
	<link rel="shortcut icon" href="<?php echo $options['favicon']?>" />
<?php } ?>


<title><?php if (is_single() || is_page()) { wp_title('',true); } elseif(is_front_page()) { bloginfo('description'); } else { bloginfo('description'); } ?> : <?php echo('Hot Tub Liquidators');?> :</title>

<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet">

<script type="application/ld+json">
  {
    "@context": "http://schema.org",
    "@type": "Blog",
    "url": "http://hottubliquidators.com/blog"
  }
</script>
<script type="application/ld+json">
  {
    "@context": "http://schema.org",
    "@type": "Organization",
    "name": "Hot Tub Liquidators",
    "url": "http://hottubliquidators.com/",
    "sameAs": [
      "https://www.facebook.com/hottubliquidators",
			"https://www.youtube.com/user/CoastSpas"
    ]
  }
</script>

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
 fbq('init', '1945655765647598'); 
fbq('track', 'PageView');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=1945655765647598&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

<?php wp_head(); ?>

</head>

<?php
 global $post; 
 global $woocommerce; 

if($woocommerce && is_shop() || $woocommerce && is_product_category() || $woocommerce && is_product_tag()) {
	$header_title = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_title', true);
	$header_bg = get_post_meta(woocommerce_get_page_id('shop'), '_nectar_header_bg', true);
} 
else if(is_home() || is_archive()){
	$header_title = get_post_meta(get_option('page_for_posts'), '_nectar_header_title', true);
	$header_bg = get_post_meta(get_option('page_for_posts'), '_nectar_header_bg', true); 
}  else {
	$header_title = get_post_meta($post->ID, '_nectar_header_title', true);
	$header_bg = get_post_meta($post->ID, '_nectar_header_bg', true); 
}

//check if parallax nectar slider is being used
$parallax_nectar_slider = (stripos( $post->post_content, '[nectar_slider') !== FALSE  && stripos( $post->post_content, '[nectar_slider') < 250 ) ? '1' : null;

$logo_class = (!empty($options['use-logo']) && $options['use-logo'] == '1') ? null : 'class="no-image"'; 

?>

<body <?php body_class(); ?> data-bg-header="<?php echo (!empty($header_bg) || !empty($header_title) || $parallax_nectar_slider == '1') ? 'true' : 'false'; ?>" data-header-color="<?php echo (!empty($options['header-color'])) ? $options['header-color'] : 'light' ; ?>" data-smooth-scrolling="<?php echo $options['smooth-scrolling']; ?>" data-responsive="<?php echo (!empty($options['responsive']) && $options['responsive'] == 1) ? '1'  : '0' ?>" >

<!-- Google Code for Remarketing Tag -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 848923252;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/848923252/?guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- END Google Code for Remarketing Tag -->

<?php if(!empty($options['boxed_layout']) && $options['boxed_layout'] == '1') { echo '<div id="boxed">'; } ?>

<?php $using_secondary = (!empty($options['header_layout'])) ? $options['header_layout'] : ' '; 

if($using_secondary == 'header_with_secondary') { ?>

<div class="header-secondary-cover"></div>
	<div id="header-secondary-outer">
		<div class="container">
			<nav>	
				<?php if(has_nav_menu('secondary_nav')) { ?>
					<ul class="sf-menu">	
				   	   <?php wp_nav_menu( array('walker' => new Nectar_Arrow_Walker_Nav_Menu, 'theme_location' => 'secondary_nav', 'container' => '', 'items_wrap' => '%3$s' ) ); ?>
				    </ul>
				<?php }	?>
				
        
        <?php if(!empty($options['enable_social_in_header']) && $options['enable_social_in_header'] == '1') { ?>
					<ul id="social">
                    	<li><a target="" href="http://hottubliquidators.com/yelp-reviews/"><i class="icon-yelp"></i></a></li> 
                        <li><a target="_blank" href="http://bit.ly/2HxIPju"><i class="icon-google-plus"></i> </a></li>
						<li><a target="_blank" href="https://www.facebook.com/hottubliquidators"><i class="icon-facebook"></i> </a></li>
						<li><a target="_blank" href="https://www.youtube.com/user/CoastSpas"><i class="icon-youtube"></i> </a></li>
						
						
					</ul>
				<?php } ?>
			</nav>
		</div>
	</div>

<?php } ?>

<div id="header-space"></div>
<div id="header-outer" data-using-secondary="<?php echo ($using_secondary == 'header_with_secondary') ? '1' : '0'; ?>" data-using-logo="<?php if(!empty($options['use-logo'])) echo $options['use-logo']; ?>" data-logo-height="<?php if(!empty($options['logo-height'])) echo $options['logo-height']; ?>" data-padding="<?php echo (!empty($options['header-padding'])) ? $options['header-padding'] : "28"; ?>" data-header-resize="<?php if(!empty($options['header-resize-on-scroll'])) echo $options['header-resize-on-scroll']; ?>">
	
	<?php get_template_part('includes/header-search'); ?>
	<div class="mainheader desktophide">
  <img src="/wp-content/themes/salient/img/mainheader.png" alt="Hot Tub Liquidators" width="1150" height="auto">
  </div>
  <div class="mainheader mobileshow">
  <div class="container">
  <img src="/wp-content/themes/salient/img/mainheader_logo.png" alt="Hot Tub Liquidators" width="304" height="auto" class="logo">
  <img src="/wp-content/themes/salient/img/mainheader_header.png" alt="Nevada's Largest Hot Tub Display : Premium New & Pre-Owned Spas for less" width="780" height="auto" class="headz">
  <div class="cleared"></div>
  </div>
  </div>
  
	<header id="top">
		
		<div class="container">
			
			<div class="row">
				  
				<div class="col span_3">
					
					<a id="logo" href="<?php echo home_url(); ?>" <?php echo $logo_class; ?>>
						
						<?php if(!empty($options['use-logo'])) {
							
							$default_logo_id = (!empty($options['retina-logo'])) ? 'id="default-logo"' : null;
							
							 echo '<img '.$default_logo_id.' alt="'. get_bloginfo('name') .'" src="' . $options['logo'] . '" />';
							 
							 if(!empty($options['retina-logo'])) echo '<img id="retina-logo" alt="'. get_bloginfo('name') .'" src="' . $options['retina-logo'] . '" />';
							 
							 } else { echo get_bloginfo('name'); } ?> 
					</a>

				</div><!--/span_3-->
				
				<div class="col span_9 col_last">
					
					<a href="#" id="toggle-nav"><i class="icon-reorder"></i></a>
					
					<?php if (!empty($options['enable-cart']) && $options['enable-cart'] == '1') { 
						if ($woocommerce) { ?> 
							<!--mobile cart link-->
							<a id="mobile-cart-link" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><i class="icon-salient-cart"></i></a>
						<?php } 
					} ?>
					
					<nav>
						<ul class="sf-menu">	
							<?php 
							if(has_nav_menu('top_nav')) {
							    wp_nav_menu( array('walker' => new Nectar_Arrow_Walker_Nav_Menu, 'theme_location' => 'top_nav', 'container' => '', 'items_wrap' => '%3$s' ) ); 
							}
							else {
								echo '<li><a href="">No menu assigned!</a></li>';
							}
							?>
							<li id="search-btn"><div><a href=""><span class="icon-salient-search" aria-hidden="true"></span></a></div></li>
						</ul>
					</nav>
					
				</div><!--/span_9-->
			
			</div><!--/row-->
			
		</div><!--/container-->
		
	</header>
	
	
	<?php if (!empty($options['enable-cart']) && $options['enable-cart'] == '1') { ?>
		<?php
		if ($woocommerce) { ?>
			
		<div class="cart-outer">
			<div class="cart-menu-wrap">
				<div class="cart-menu">
					<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><div class="cart-icon-wrap"><i class="icon-shopping-cart"></i> <div class="cart-wrap"><span><?php echo $woocommerce->cart->cart_contents_count; ?> </span></div> </div></a>
				</div>
			</div>
			
			<div class="cart-notification">
				<span class="item-name"></span> <?php echo __('was successfully added to your cart.'); ?>
			</div>
			
			<?php
				// Check for WooCommerce 2.0 and display the cart widget
				if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
					the_widget( 'WC_Widget_Cart', 'title= ' );
				} else {
					the_widget( 'WooCommerce_Widget_Cart', 'title= ' );
				}
			?>
				
		</div>
		
	 <?php } 
	 
   } ?>		
	

</div><!--/header-outer-->


<div id="mobile-menu">
	
	<div class="container">
		<ul>
			<?php 
				if(has_nav_menu('top_nav')) {
					
				    wp_nav_menu( array('theme_location' => 'top_nav', 'menu' => 'Top Navigation Menu', 'container' => '', 'items_wrap' => '%3$s' ) ); 
					echo '<li id="mobile-search">  
					<form action="'.home_url().'" method="GET">
			      		<input type="text" name="s" value="" placeholder="'.__('Search..', NECTAR_THEME_NAME) .'" />
					</form> 
					</li>';
				}
				else {
					echo '<li><a href="">No menu assigned!</a></li>';
				}
			?>		
		</ul>
	</div>
	
</div>
