<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package foggedclarity
 */
?>
<?php tha_entry_before(); ?>
<section class="no-results not-found">
<?php tha_entry_top(); ?>
	<header class="page-header">
		<div class="entry-meta">
			<h1 class="page-title"><?php _e( 'Nothing Found', 'foggedclarity' ); ?></h1>
			<hr class="divider">
		</div>
	</header><!-- .page-header -->
	<?php tha_content_before(); ?>
	<div class="page-content">
		<?php tha_entry_top(); ?>
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'foggedclarity' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php _e( 'Sorry, but nothing matched your search terms. Perhaps searching for something else will yield results:', 'foggedclarity' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php _e( 'It seems that you&rsquo;ve stumbled into the foggy depths. Perhaps searching will help:', 'foggedclarity' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
		<?php tha_entry_bottom(); ?>
	</div><!-- .page-content -->
	<?php tha_content_after(); ?>
	<?php tha_entry_bottom(); ?>
</section><!-- .no-results
<?php tha_entry_after(); ?>
