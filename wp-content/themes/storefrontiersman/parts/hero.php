<?php if( get_field('hero_image') ) : ?>

<div class="hero">

	<div class="hero__image" style="background-image: url(<?php echo wp_get_attachment_url(); ?>)"></div>

	<div class="page__header">
		<h1><?php the_title; ?></h1>
	</div>

</div>

<?php endif; ?>