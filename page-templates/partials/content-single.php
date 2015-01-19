<?php
/**
 * @package foggedclarity
 */
?>
<?php tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemType="http://schema.org/BlogPosting">
	<?php tha_entry_top(); ?>
	<header class="entry-header">
		<h1 class="entry-title" itemprop="name" ><?php the_title(); ?></h1>
		<div class="entry-meta">
			<hr class="divider">
			<p class="issuem_article_byline">
				<?php
					$output = '';
					$auth_name = $cfs->get('post_author_name');
					
					// don't show byline on interviews or fc sessions
					$category = get_the_category()[0]->cat_name;

					if( $category === 'Interviews' ) 
					{
						$output = 'A Fogged Clarity Interview';
					} 
					else if( $category === 'Fogged Clarity Sessions' ) 
					{
						$output = 'A Fogged Clarity Session';
					} 
					else 
					{
						// if auth_name field is defined, show auth_name
						if(isset($auth_name) && $auth_name !== '') {
							$output = __('By ', 'fc');
							$output .= $cfs->get('post_author_name');
						} 
						// else show issueM article author
						else if (get_post_type($post) === "article") {
							$output  = __('By ', 'fc');
							$output .= get_the_author();
						} 
						// else show the wordpress author
						else {
							// TODO :: add proper authors to legacy posts and uncomment below
							$output  = __('By ', 'fc');
							$output .= get_the_author();
						}
					}
					echo $output;
				?>
			</p>
			<!-- <span class="genericon genericon-time"></span> <?php //foggedclarity_posted_on(); ?> -->
			<?php if ( function_exists( 'sharing_display' ) ) { sharing_display( '', true ); } ?>

		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content" itemprop="articleBody" >
		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'foggedclarity' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

	<footer class="entry-meta" itemprop="keywords" >
			<?php if ( function_exists( 'sharing_display' ) ) { sharing_display( '', true ); } ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			// $category_list = get_the_category_list( __( ', ', 'foggedclarity' ) );

			// /* translators: used between list items, there is a space after the comma */
			// $tag_list = get_the_tag_list( '', __( ', ', 'foggedclarity' ) );

			// if ( ! foggedclarity_categorized_blog() ) {
			// 	// This blog only has 1 category so we just need to worry about tags in the meta text
			// 	if ( '' != $tag_list ) {
			// 		$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'foggedclarity' );
			// 	} else {
			// 		$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'foggedclarity' );
			// 	}

			// } else {
			// 	// But this blog has loads of categories so we should probably display them here
			// 	if ( '' != $tag_list ) {
			// 		$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'foggedclarity' );
			// 	} else {
			// 		$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'foggedclarity' );
			// 	}

			// } // end check for categories on this blog

			// printf(
			// 	$meta_text,
			// 	$category_list,
			// 	$tag_list,
			// 	get_permalink()
			// );
		?>

		<?php edit_post_link( __( 'Edit', 'foggedclarity' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php tha_entry_bottom(); ?>
</article><!-- #post-## -->
<?php tha_entry_after(); ?>
