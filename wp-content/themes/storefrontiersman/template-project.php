<?php
/**
 * 
 * Template Name: Project
 * 
 * 
 * The template for displaying all project pages (children of Portfolio page). It's currently just the template for displaying all pages including the content-page.php part within it.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'storefront_page_before' ); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php
					/**
					 * Functions hooked in to storefront_page add_action
					 *
					 * @hooked storefront_page_header          - 10
					 * @hooked storefront_page_content         - 20
					 */
					do_action( 'storefront_page' ); ?>

				</article><!-- #post-## -->

				<?php
				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 */
				do_action( 'storefront_page_after' ); ?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
