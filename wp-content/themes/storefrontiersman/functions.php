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
    
// remove stuff:
add_action( 'init', 'da_remove_storefront_actions' );

function da_remove_storefront_actions() {
// remove search from header:
remove_action( 'storefront_header', 'storefront_product_search', 	40 );
}

// remove author card from posts (copied the Storefront function and took author card div out, my version of the function now takes precedence)
if ( ! function_exists( 'storefront_post_meta' ) ) {
	/**
	 * Display the post meta
	 *
	 * @since 1.0.0
	 */
	function storefront_post_meta() {
		?>
		<aside class="entry-meta">
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search.

			?>

			<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'storefront' ) );

			if ( $categories_list ) : ?>
				<div class="cat-links">
					<?php
					echo '<div class="label">' . esc_attr( __( 'Posted in', 'storefront' ) ) . '</div>';
					echo wp_kses_post( $categories_list );
					?>
				</div>
			<?php endif; // End if categories. ?>

			<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'storefront' ) );

			if ( $tags_list ) : ?>
				<div class="tags-links">
					<?php
					echo '<div class="label">' . esc_attr( __( 'Tagged', 'storefront' ) ) . '</div>';
					echo wp_kses_post( $tags_list );
					?>
				</div>
			<?php endif; // End if $tags_list. ?>

		<?php endif; // End if 'post' == get_post_type(). ?>

			<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<div class="comments-link">
					<?php echo '<div class="label">' . esc_attr( __( 'Comments', 'storefront' ) ) . '</div>'; ?>
					<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'storefront' ), __( '1 Comment', 'storefront' ), __( '% Comments', 'storefront' ) ); ?></span>
				</div>
			<?php endif; ?>
		</aside>
		<?php
	}
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
