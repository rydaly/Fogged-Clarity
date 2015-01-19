<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package foggedclarity
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<div class="entry-meta">
						<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'foggedclarity' ); ?></h1>
						<hr class="divider">
					</div>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It seems that you&rsquo;ve stumbled into the foggy depths. Perhaps searching will help:', 'foggedclarity' ); ?></p>

					<?php get_search_form(); ?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>