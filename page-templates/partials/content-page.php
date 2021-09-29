<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package foggedclarity
 */
?>
<?php tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemType="https://schema.org/WebPage">
	<?php tha_entry_top(); ?>
	<div class="entry-content" itemprop="mainContentOfPage">

		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'foggedclarity' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->
	<?php edit_post_link( __( 'Edit', 'foggedclarity' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	<?php tha_entry_bottom(); ?>
</article><!-- #post-## -->
<?php tha_entry_after(); ?>
