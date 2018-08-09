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
add_action( 'wp_enqueue_scripts', 'my_google_fonts' );

function my_google_fonts() {
	wp_enqueue_style( 'my-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:300,400" rel="stylesheet"', false ); 
}
 
// remove 'sort by average rating' from the dropdown on a product page (reinstate if there are eventually lots of ratings):
add_filter ( 'woocommerce_catalog_orderby', 'storefrontiersman_catalog_orderby', 20);
function storefrontiersman_catalog_orderby( $orderby ){
unset ($orderby['rating']);
unset ($orderby['popularity']);
return $orderby;
}
    
// remove search from header:
add_action( 'init', 'da_remove_storefront_header_search' );

function da_remove_storefront_header_search() {
remove_action( 'storefront_header', 'storefront_product_search', 	40 );
}

// Adds a top bar to Storefront, before the header:
/*
add_action( 'storefront_before_header', 'storefront_add_topbar' );

function storefront_add_topbar() {
    ?>
    <div id="topbar">
        <div class="col-full">
            <p>Your text here</p>
        </div>
    </div>
    <?php
}
*/

// remove breadcrumbs:
add_action('init', 'da_remove_wc_breadcrumbs');

function da_remove_wc_breadcrumbs(){
    remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
};

// remove footer credits:
add_action( 'init', 'custom_remove_footer_credit', 10 );

function custom_remove_footer_credit () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
    add_action( 'storefront_footer', 'custom_storefront_credit', 20 );
} 

function custom_storefront_credit() {
	?>
	<div class="site-info">
        <p>&copy; <?php echo get_bloginfo( 'name' ) . ' ' . get_the_date( 'Y' ); ?></p>
        <p>Website by <a href="https://www.danaddison.co.uk/">Dan Addison</a></p>
	</div><!-- .site-info -->
	<?php
}
