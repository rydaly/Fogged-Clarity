<?php
/**
 * @package foggedclarity
 */
?>

<?php tha_entry_before(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemType="http://schema.org/BlogPosting" >
  <?php tha_entry_top(); ?>
  

  <div class="entry-content" itemprop="articleBody">

    <?php
      $entry_img_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID), 'large' );
      $entry_img_width = $entry_img_url[1];
      $entry_img_height = $entry_img_url[2];

      ////////

      $attachments = get_posts( array(
        'post_type' => 'attachment',
        'posts_per_page' => -1,
        'post_parent' => $post->ID,
        'exclude'     => get_post_thumbnail_id()
      ) );

      if ( $attachments ) {
        foreach ( $attachments as $attachment ) {
          $thumb = wp_get_attachment_image_src( $attachment->ID, 'large' );
          $thumbpath = $thumb[0];
          $thumbwidth = $thumb[1];
          $thumbheight = $thumb[2];
          // wp_print_r( $thumbpath );
          $entry_img_url = $thumbpath;
        }
      }

      // TODO :: move the function below into custom.php and re-use here and in issuem_custom.php

      // grab a default image if no entry image
      if ( empty( $entry_img_url )) {
        $theme_root = get_theme_root();
        $imgdir = '/fogged-clarity/dist/images/default_post_imgs/';
        $file_path = $theme_root . $imgdir;
        $imgs = glob($file_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $rand_img = $imgs[array_rand($imgs)];
        // replace file server path with uri so we can use path in our CSS below
        $rand_img = str_replace($theme_root, get_theme_root_uri(), $rand_img);
        $entry_img_url = $rand_img;?>
        
        <div class="blog-entry">
          <div class="image-holder" style="background-image:url('<?php echo $entry_img_url ?>'); background-repeat: repeat;">
        </div> <?php
      } else { ?>
        <div class="blog-entry">
          <div class="image-holder" style="background-image:url('<?php echo $entry_img_url ?>'); background-size: cover;">  
        </div> <?php
      }?>

      <div class="blog-entry-content">
        <h3 class="blog-entry-title">
          <a rel="bookmark" href="<?php the_permalink() ?>"><?php the_title() ?></a>
        </h3>
        <?php
          if( has_category( 'visual-arts' ) || has_category( 'interviews' ) || has_category( 'fogged-clarity-sessions' ) ) {
            // do nothing
          } else {
            ?><h4><?php foggedclarity_posted_on() ?></h4><?php
          }
        ?>
        
        <p><?php the_excerpt() ?></p> <?php
   
        wp_link_pages( array(
          'before' => '<div class="page-links">' . __( 'Pages:', 'foggedclarity' ),
          'after'  => '</div>',
        ) );
        ?>
      </div><!-- .entry-content -->
    <?php //endif; ?>

  <?php tha_entry_bottom(); ?>
</article><!-- #post-## -->
<?php tha_entry_after(); ?>