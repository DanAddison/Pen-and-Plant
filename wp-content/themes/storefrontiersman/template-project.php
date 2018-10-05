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

				<a href="<?php $parentLink = get_permalink($post->post_parent); echo $parentLink; ?>" title="Go back to the Portfolio page" class="back">&#8592; Back to Work</a>

				<header>
					<?php the_title( '<h1 class="artwork-title">', '</h1>' ); ?>
				</header>


					<?php
						$fields = get_field_objects();
						
						if( $fields ) : ?>

						<div class="artwork-meta">

							<p class="artwork-date"><?php the_field('artwork_date'); ?></p>
							<p class="artwork-materials"><?php the_field('artwork_materials'); ?></p>
							
							<?php if(get_field('artwork_collaborators')) : ?>
								<p><?php the_field('artwork_collaborators'); ?></p>
							<?php endif; ?>

						</div><!-- .artwork-meta info -->

						<div class="artwork-images">

							<?php			
							$imageArray = get_field('artwork_featured_image'); // Array returned by Advanced Custom Fields
							$imageAlt = esc_attr($imageArray['alt']); // Grab, from the array, the 'alt'
							$imageMediumLargeURL = esc_url($imageArray['sizes']['medium_large']); //grab from the array, the 'sizes', and from it, the 'medium_large'
							?>
							
							<div class="artwork-featured-image">
								<img src="<?php echo $imageMediumLargeURL;?>" alt="<?php echo $imageAlt; ?>">
							</div><!-- .artwork-image - main feature image -->

						</div><!-- .artwork-images container -->

						<div class="artwork-description">
							<p><?php the_field('artwork_description'); ?></p>
						</div>

						<div class="artwork-thumbnails-gallery">

							<?php	while ( have_rows('artwork_thumbnail_gallery') ) : the_row(); 
								$image = get_sub_field('thumbnail_images');
								$thumbnail = 'thumbnail';
								$large = 'large';
								$video = get_sub_field('video_links');
							?>

							<?php if( $video ) : ?>
							<a href="<?php echo ( $video )?>" data-fancybox="group">
								<img src="<?php echo wp_get_attachment_image_url( $image, $thumbnail )?>" alt="<?php echo $image['alt']; ?>" class="thumbnail-image">
							</a>
							<?php else : ?>
							<a href="<?php echo wp_get_attachment_image_url( $image, $large )?>" alt="<?php echo $image['alt']; ?>" data-fancybox="group">
								<img src="<?php echo wp_get_attachment_image_url( $image, $thumbnail )?>" alt="<?php echo $image['alt']; ?>" class="thumbnail-image">
							</a>
							<?php endif; ?>

							<?php endwhile; ?>

						</div><!-- .artwork-thumbnails-gallery -->

					<?php endif; ?>

				</article><!-- #post-## -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
