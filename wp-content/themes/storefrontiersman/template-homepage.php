<?php
/**
 * Dan's template for displaying the homepage.
 *
 * Template name: Homepage
 *
 * @package storefront
 */
get_header(); ?>

<div class="splash">

  <div class="splash__image">
		<!-- <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/images/testWork4.png" alt=""> -->
	</div>
    
	<div class="splash__content">
    
    <div class="site-identity">
			<h1>Pen & Plant</h1>
			<p><?php bloginfo( 'description' ); ?></p>	
		</div>

		<div class="links">
			<a href="http://penandplant.localhost/bio/">Bio</a>
			<a href="http://penandplant.localhost/portfolio/">Portfolio</a>
			<a href="http://penandplant.localhost/shop/">Shop</a>
		</div>


	</div><!-- splash__content -->

</div>

