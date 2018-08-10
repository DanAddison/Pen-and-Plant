<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

//  my google fonts:
add_action( 'wp_enqueue_scripts', 'da_google_fonts' );

function my_google_fonts() {
	wp_enqueue_style( 'da-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:300,400" rel="stylesheet"', false ); 
}

// add font awesome brands (some fa is already in the theme eg user, home, cart icons, but not brands):
add_action( 'wp_enqueue_scripts', 'da_social_icons_enqueue_fab' );

function da_social_icons_enqueue_fab() {
	wp_enqueue_style( 'font-awesome-5-brands', '//use.fontawesome.com/releases/v5.0.13/css/brands.css' );
}
 
// remove 'sort by average rating' from the dropdown on a product page (reinstate if there are eventually lots of ratings):
add_filter ( 'woocommerce_catalog_orderby', 'da_catalog_orderby', 20);
function da_catalog_orderby( $orderby ){
unset ($orderby['rating']);
unset ($orderby['popularity']);
return $orderby;
}
    
// remove stuff:
add_action( 'init', 'da_remove_storefront_actions' );

function da_remove_storefront_actions() {
// remove secondary menu from header:
remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );

// remove search from header:
remove_action( 'storefront_header', 'storefront_product_search', 	40 );

// remove post meta (author card, category, leave a comment link) from posts:
remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );

// remove breadcrumbs:
remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
}

/*
// change cart to basket:
// note: this does not also update any 'view cart' buttons, and if you change cart page name and slug to basket then cart cannot be found at all...
add_filter( 'woocommerce_product_add_to_cart_text', 'da_add_to_cart_text', 10, 2 );

add_filter( 'woocommerce_product_single_add_to_cart_text', 'da_add_to_cart_text', 10, 2 ); 
function da_add_to_cart_text( $text, $product ) {
	$text = __( 'Add to basket', 'woocommerce' );
	return $text;
}
*/

// add home icon to handheld footer bar:
add_filter( 'storefront_handheld_footer_bar_links', 'da_add_home_link' );
function da_add_home_link( $links ) {
	$new_links = array(
		'home' => array(
			'priority' => 10,
			'callback' => 'da_home_link',
		),
	);

	$links = array_merge( $new_links, $links );

	return $links;
}

function da_home_link() {
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home' ) . '</a>';
}


// remove footer credits:
add_action( 'init', 'da_remove_footer_credit', 10 );

function da_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'da_storefront_credit', 20 );
} 

function da_storefront_credit() {
	?>
	<div class="site-info">
        <p>&copy; <?php echo get_bloginfo( 'name' ) . ' ' . get_the_date( 'Y' ); ?></p>
        <p class="credit">Website by <a href="https://www.danaddison.co.uk/">Dan Addison</a></p>
	</div><!-- .site-info -->
	<?php
}

// add secondary menu to footer:
add_action( 'storefront_footer', 'storefront_secondary_navigation', 10 );

// add social menu to footer
// first we register it by filtering the storefront_register_nav_menus and adding our new menu to that array:
add_filter( 'storefront_register_nav_menus', 'da_storefront_register_social_menu');
function da_storefront_register_social_menu($menus){
	$menus['social'] =  __( 'Social Menu', 'storefront' );
	return $menus;
}
// now display the social menu in the footer:
add_action( 'storefront_footer', 'da_storefront_social_navigation', 20 );
function da_storefront_social_navigation() {
		if ( has_nav_menu( 'social' ) ) {
			?>
			<nav class="social-navigation" role="navigation" aria-label="<?php esc_html_e( 'Social Navigation', 'storefront' ); ?>">
				<?php
					wp_nav_menu(
						array(
							'theme_location'	=> 'social',
							'fallback_cb'		=> '',
						)
					);
				?>
			</nav><!-- #site-navigation -->
			<?php
	}
}
