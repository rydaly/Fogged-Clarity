<?php
/**
 * @package foggedclarity
 */
?>

<?php tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemType="http://schema.org/BlogPosting" >
	<?php tha_entry_top(); ?>
	<header class="entry-header">
		<h1 class="entry-title" itemprop="name" ><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<p class="issuem_article_byline">
				<?php
					$auth_name = $cfs->get('post_author_name');

					// if auth_name field is defined, show auth_name
					// if(isset($auth_name) && $auth_name !== '') {
					// 	$output = __('By ', 'foggedclarity');
					// 	$output .= $cfs->get('post_author_name');
					// 	echo $output;
					// } 
					// else show issueM article author
					if (get_post_type($post) === "article") {
						echo __('By ', 'foggedclarity');
						echo get_the_author();
					} 
					// else show nothing
					else {
						echo '';
					}
				?>
			</p>
			<!-- <span class="genericon genericon-time"></span> <?php //foggedclarity_posted_on(); ?>
			<span itemprop="dateModified" style="display:none;">Last modified: <?php //the_modified_date(); ?></span> -->
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary" itemprop="description">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content" itemprop="articleBody">
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'foggedclarity' ) ); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'foggedclarity' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta" itemprop="keywords">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'foggedclarity' ) );
				if ( $categories_list && foggedclarity_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'foggedclarity' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'foggedclarity' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'foggedclarity' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link" itemprop="comment" ><?php comments_popup_link( __( 'Leave a comment', 'foggedclarity' ), __( '1 Comment', 'foggedclarity' ), __( '% Comments', 'foggedclarity' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'foggedclarity' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	<?php tha_entry_bottom(); ?>
</article><!-- #post-## -->
<?php tha_entry_after(); ?>