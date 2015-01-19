<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package foggedclarity
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'foggedclarity' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<hr class="divider">
			</header><!-- .page-header -->

			<div class="entry-content" itemprop="articleBody">
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'page-templates/partials/content-entry', 'search' ); ?>

			<?php endwhile; ?>

			<?php foggedclarity_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'page-templates/partials/content', 'none' ); ?>

		<?php endif; ?>
		</div><!-- #entry-content -->
		</main><!-- #main -->
	</section><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
