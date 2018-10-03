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

function da_google_fonts() {
	wp_enqueue_style( 'da-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:300,400" rel="stylesheet"', false ); 
}

// add font awesome brands (some fa is already in the theme eg user, home, cart icons, but not brands):
add_action( 'wp_enqueue_scripts', 'da_social_icons_enqueue_fab' );

function da_social_icons_enqueue_fab() {
	wp_enqueue_style( 'font-awesome-5-brands', '//use.fontawesome.com/releases/v5.0.13/css/brands.css' );
}

// add hero image to all pages:
/*
add_action ( 'storefront_before_content', 'storefront_page_header' );

function storefront_page_header() {
	if( get_field('hero_image') ) : ?>

	<div class="hero">

		<div class="hero__image" style="background-image: url(<?php the_field('hero_image'); ?>)">
	
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>	
			</header>
				
		</div>

	</div>
		
	<?php endif;
}
*/

// initialise extra new widget areas:
function da_widgets_init() {
	
	register_sidebar( array(
		'id'            => 'above_footer_widget',
		'name'          => __( 'Above Footer', 'text_domain' ),
		'description'   => __( 'Widgets added here will display above all other footer widgets', 'text_domain' ),
		'before_widget' => '<div class="instagram-widget">',
		'after_widget'  => '</div>',
		) );	
}
add_action( 'widgets_init', 'da_widgets_init' );
	
// create instagram feed widget:	
function da_instagram_widget() {
	if( ! is_active_sidebar( 'above_footer_widget' ) )
		return;
	if ( is_page ( array( 'bio', 'contact', 'publications' ) ) || tribe_is_event_query() ) {
		dynamic_sidebar( 'above_footer_widget' );
	}
}
add_action( 'storefront_page_after', 'da_instagram_widget', 10 );
 
// remove 'sort by average rating' from the dropdown on a product page (reinstate if there are eventually lots of ratings):
add_filter ( 'woocommerce_catalog_orderby', 'da_catalog_orderby', 20);
function da_catalog_orderby( $orderby ){
unset ($orderby['rating']);
unset ($orderby['popularity']);
$orderby['date'] = __('Sort by date: newest to oldest', 'woocommerce');
return $orderby;
}
    
// remove stuff:
add_action( 'init', 'da_remove_storefront_actions' );
function da_remove_storefront_actions() {

// remove search from header:
remove_action( 'storefront_header', 'storefront_product_search',	40 );

// remove secondary menu that usually lives in the header:
remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );

// remove page header when using hero if I want page title inside hero section:
// remove_action( 'storefront_page', 'storefront_page_header', 10 );

// remove post meta (author card, category, leave a comment link) from posts:
remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );

// remove breadcrumbs:
remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
}


// remove the sidebars on all pages and archives, except those stated:
add_action( 'wp', 'da_remove_sidebar_shop_page' );
function da_remove_sidebar_shop_page() {
	
	if ( ! is_page ( array( 'bio', 'contact' ) )) {
		
		remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
		add_filter( 'body_class', 'da_remove_sidebar_class_body', 10 );
	}
}

function da_remove_sidebar_class_body( $wp_classes ) {
	
	$wp_classes[] = 'page-template-template-fullwidth-php';
	return $wp_classes;
}

// Add sections to shop page:
add_action( 'woocommerce_before_main_content', 'da_shop_page_sections' );
function da_shop_page_sections() {
	
	if ( is_shop() ) {	
		add_action( 'woocommerce_before_shop_loop', 'storefront_featured_products', 8 );
		add_action( 'woocommerce_before_shop_loop', 'storefront_product_categories', 8 );
	}
}

// change wording of featured products section from 'we recommend':
add_filter( 'storefront_featured_products_args', 'da_featured_products' );
function da_featured_products( $args ){
	$args['title'] = __( 'So Hot Right Now', 'storefront' );
	return $args;
}

// changing number of columns for product category rows:
add_filter( 'storefront_product_categories_args', 'da_product_categories' );
function da_product_categories( $args ){
	$args = array(
		'limit' => 5,
		'columns' => 5,
		'orderby' => 'name',
		'title'	=> __( 'Shop by Category', 'storefront' ),
	);
	return $args;
}


// remove phone field in checkout:
add_filter( 'woocommerce_checkout_fields', 'da_checkout_fields', 20 );
function da_checkout_fields( $fields ){
	unset( $fields['billing']['billing_phone']);
	return $fields;
}

// Add 'how did you hear about us' feedback to checkout:
add_filter( 'woocommerce_checkout_fields', 'da_hear_about_us', 30 );
function da_hear_about_us ( $fields ){
	$fields['order']['hear_about_us'] = array(
		'type' => 'select',
		'label' => 'How did you hear about us?',
		'options' => array(
			'default' => '--select an option--',
			'wom' => 'Word of mouth',
			'google' => 'Google',
			'social' => 'Social media',
			'print' => 'Print'
		)
		);
	return $fields;
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

// register social menu by filtering the storefront_register_nav_menus and adding our new menu to that array:
add_filter( 'storefront_register_nav_menus', 'da_storefront_register_social_menu');
function da_storefront_register_social_menu($menus){
	$menus['social'] =  __( 'Social Menu', 'storefront' );
	return $menus;
}

// remove footer credits and add my own within a new footer that also includes social menu:
add_action( 'init', 'da_custom_footer', 10 );

function da_custom_footer () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
		add_action( 'storefront_footer', 'da_storefront_footer', 20 );
} 

function da_storefront_footer() {
	?>
	<div class="sub-footer">

		<div class="site-legal">
			<p>&copy; <?php echo get_bloginfo( 'name' ) . ' ' . get_the_date( 'Y' ); ?></p>
		</div><!-- .site-legal -->

			<nav class="social-navigation" role="navigation" aria-label="<?php esc_html_e( 'Social Navigation', 'storefront' ); ?>">
				<?php
					wp_nav_menu(
						array(
							'theme_location'	=> 'social',
							'fallback_cb'		=> '',
						)
					);
				?>
			</nav><!-- social-navigation -->

		<div class="site-credit">
			<p class="credit">Website by <a href="https://www.danaddison.co.uk/">Dan Addison</a></p>
		</div><!-- .site-credit -->

	</div><!-- sub-footer -->
	<?php
}
