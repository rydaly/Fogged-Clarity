<?php

add_action( 'init', 'register_custom_issuem', 11 ); // run after issuem has registered

function register_custom_issuem() {
  // remove issuem shortcodes and override
  remove_shortcode( 'issuem_articles', 'do_issuem_articles' );
  remove_shortcode( 'issuem_featured_rotator', 'do_issuem_featured_rotator' );
  remove_shortcode( 'issuem_archives', 'do_issuem_archives' );
  add_shortcode( 'issuem_articles', 'do_custom_issuem_articles' );
  add_shortcode( 'issuem_featured_rotator', 'do_custom_issuem_featured_rotator' );
  add_shortcode( 'issuem_archives', 'do_custom_issuem_archives' );
  add_shortcode( 'issuem_contributors', 'do_fc_issuem_contributors' );
}

if ( !function_exists( 'do_fc_issuem_contributors' ) ) {
  /**
   * Outputs Contributors Page HTML from shortcode call - gets all authors in current issue
   *
   * @param array $atts Arguments passed through shortcode
   * @return string HTML output of authors, bios and links to posts in current issue
   */

  function do_fc_issuem_contributors( $atts ) {
    global $post;
    
    $issuem_settings = get_issuem_settings();
    $results = '';
    $wrap_results = '';
    $main_results = '';
    $articles = array();
    $post__in = array();
    
    $defaults = array(
      'posts_per_page'      => -1,
      'offset'              => 0,
      'orderby'             => 'author', //author
      'order'               => 'DESC',
      'article_format'      => empty( $article_format ) ? $issuem_settings['article_format'] : $article_format,
      'show_featured'       => 1,
      'issue'               => get_active_issuem_issue(),
      'article_category'    => 'all',
      'use_category_order'  => 'false',
    );
  
    // Merge defaults with passed atts
    // Extract (make each array element its own PHP var
    extract( shortcode_atts( $defaults, $atts ) );
    
    $args = array(
      'posts_per_page'  => $posts_per_page,
      'offset'          => $offset,
      'post_type'       => 'article',
      'orderby'         => $orderby,
      'order'           => $order
    );
    
    if ( !$show_featured ) {
      
      $args['meta_query'] = array(
        'relation' => 'AND',
        array(
          'key' => '_featured_rotator',
          'compare' => 'NOT EXISTS'
        ),
        array(
          'key' => '_featured_thumb',
          'compare' => 'NOT EXISTS'
        )
      );
      
    }
  
    $issuem_issue = array(
      'taxonomy'  => 'issuem_issue',
      'field'   => 'slug',
      'terms'   => $issue
    );
    
    $args['tax_query'] = array(
      $issuem_issue
    );
    
    if ( !empty( $issuem_settings['use_wp_taxonomies'] ) ) 
      $cat_type = 'category';
    else
      $cat_type = 'issuem_issue_categories';
      
    if ( 'true' === $use_category_order && 'issuem_issue_categories' === $cat_type ) {

      $count = 0;
      
      if ( 'all' === $article_category ) {
      
        $all_terms = get_terms( 'issuem_issue_categories' );
        
        foreach( $all_terms as $term ) {
        
          $issue_cat_meta = get_option( 'issuem_issue_categories_' . $term->term_id . '_meta' );
            
          if ( !empty( $issue_cat_meta['category_order'] ) )
            $terms[ $issue_cat_meta['category_order'] ] = $term->slug;
          else
            $terms[ '-' . ++$count ] = $term->slug;
            
        }
        
      } else {
      
        foreach( split( ',', $article_category ) as $term_slug ) {
          
          $term = get_term_by( 'slug', $term_slug, 'issuem_issue_categories' );
        
          $issue_cat_meta = get_option( 'issuem_issue_categories_' . $term->term_id . '_meta' );
            
          if ( !empty( $issue_cat_meta['category_order'] ) )
            $terms[ $issue_cat_meta['category_order'] ] = $term->slug;
          else
            $terms[ '-' . ++$count ] = $term->slug;
            
        }
      
      }
      
      krsort( $terms );
      $articles = array();
      
      foreach( $terms as $term ) {
      
        $category = array(
          'taxonomy'  => $cat_type,
          'field'   => 'slug',
          'terms'   => $term,
        );  
        
        $args['tax_query'] = array(
          'relation'  => 'AND',
          $issuem_issue,
          $category
        );
        
        $articles = array_merge( $articles, get_posts( $args ) );
      }
    
      //And we want all articles not in a category
      $category = array(
        'taxonomy'  => $cat_type,
        'field'   => 'slug',
        'terms'   => $terms, 
        'operator'  => 'NOT IN',
      );

      $args['tax_query'] = array(
        'relation' => 'AND',
        $issuem_issue,
        $category
      );

      $articles = array_merge( $articles, get_posts( $args ) );

      //Now we need to get rid of duplicates (assuming an article is in more than one category
      if ( !empty( $articles ) ) {
        
        foreach( $articles as $article ) {
        
          $post__in[] = $article->ID;
          
        }
        
        $args['post__in'] = array_unique( $post__in );
        $args['orderby']  = 'post__in';
        unset( $args['tax_query'] );
          
        $articles = get_posts( $args );
      
      }
      
    } else {
      
      if ( !empty( $article_category ) && 'all' !== $article_category ) {
          
        $category = array(
          'taxonomy'  => $cat_type,
          'field'   => 'slug',
          'terms'   => split( ',', $article_category ),
        );  
        
        $args['tax_query'] = array(
          'relation'  => 'AND',
          $issuem_issue,
          $category
        );
        
      }
        
      $articles = get_posts( $args );
      
    }

    // helper function for sorting author arrays below
    function array_combine_($keys, $values)
    {
      $result = array();
      foreach ($keys as $i => $k) {
        $result[$k][] = $values[$i];
      }
      array_walk($result, create_function('&$v', '$v = (count($v) == 1)? array_pop($v): $v;'));
      return $result;
    }

    global $cfs;

    $author_list = array();
    $authors_array = array();

    //echo '<h3>' . get_issuem_issue_title() . '</h3>';

    foreach( $articles as $article ) {
      $author_name = get_issuem_author_name( $article );
      $author_bio = $cfs->get('artist_bio', $article->ID);
      $ar_title = get_the_title( $article->ID );
      $ar_link = get_permalink( $article->ID );

      array_push( $authors_array, [ $author_bio, $ar_title, $ar_link ] );
      array_push( $author_list, $author_name );
    }

    $authors_array = array_combine_( $author_list, $authors_array );

    foreach ( $authors_array as $author_key => $author_val ) {
      echo '<div id="bio">';

      if( is_array( $author_val[0] ) ) {
        // more than one article from this author
        $bio = $author_val[0][0];

        echo $bio;
        echo '<div class="workLink"><h3>In This Issue</h3>';

        foreach ( $author_val as $key => $link_val ) {
          // loop through and get all article titles and links
          $title = $link_val[1];
          $link = $link_val[2];

          echo '<a href="' . $link . '" title="' . $title . '">' . $title . '</a>';
        }
        echo '</div></div>';
      } else {
        // author has only one article
        $bio = $author_val[0];
        $title = $author_val[1];
        $link = $author_val[2];

        echo $bio;
        echo '<div class="workLink"><h3>In This Issue</h3>';
        echo '<a href="' . $link . '" title="' . $title . '">' . $title . '</a></div></div>';
      }
    }
    wp_reset_postdata();
  }
}



/*******************************************************************************************/



if ( !function_exists( 'do_custom_issuem_articles' ) ) {
  /**
   * Outputs Article HTML from shortcode call
   *
   * @since 1.0.0
   *
   * @param array $atts Arguments passed through shortcode
   * @return string HTML output of IssueM Articles
   */

  function do_custom_issuem_articles( $atts, $article_format = NULL ) {
    
    global $post;
    
    $issuem_settings = get_issuem_settings();
    $results = '';
    $cat_filter_list = [];  // array of categories in current issue
    $music_count = 0;       // flag for 'music' since multiple categories are contained within it
    $wrap_results = '';
    $main_results = '';
    $articles = array();
    $post__in = array();
    
    $defaults = array(
      'posts_per_page'      => -1,
      'offset'              => 0,
      'orderby'             => 'menu_order',
      'order'               => 'DESC',
      'article_format'      => empty( $article_format ) ? $issuem_settings['article_format'] : $article_format,
      'show_featured'       => 1,
      'issue'               => get_active_issuem_issue(),
      'article_category'    => 'all',
      'use_category_order'  => 'false',
    );
  
    // Merge defaults with passed atts
    // Extract (make each array element its own PHP var
    extract( shortcode_atts( $defaults, $atts ) );
    
    $args = array(
      'posts_per_page'  => $posts_per_page,
      'offset'          => $offset,
      'post_type'       => 'article',
      'orderby'         => $orderby,
      'order'           => $order
    );
    
    if ( !$show_featured ) {
      
      $args['meta_query'] = array(
        'relation' => 'AND',
        array(
          'key' => '_featured_rotator',
          'compare' => 'NOT EXISTS'
        ),
        array(
          'key' => '_featured_thumb',
          'compare' => 'NOT EXISTS'
        )
      );
      
    }
  
    $issuem_issue = array(
      'taxonomy'  => 'issuem_issue',
      'field'   => 'slug',
      'terms'   => $issue
    );
    
    $args['tax_query'] = array(
      $issuem_issue
    );
    
    if ( !empty( $issuem_settings['use_wp_taxonomies'] ) ) 
      $cat_type = 'category';
    else
      $cat_type = 'issuem_issue_categories';
      
    if ( 'true' === $use_category_order && 'issuem_issue_categories' === $cat_type ) {

      $count = 0;
      
      if ( 'all' === $article_category ) {
      
        $all_terms = get_terms( 'issuem_issue_categories' );
        
        foreach( $all_terms as $term ) {
        
          $issue_cat_meta = get_option( 'issuem_issue_categories_' . $term->term_id . '_meta' );
            
          if ( !empty( $issue_cat_meta['category_order'] ) )
            $terms[ $issue_cat_meta['category_order'] ] = $term->slug;
          else
            $terms[ '-' . ++$count ] = $term->slug;
            
        }
        
      } else {
      
        foreach( split( ',', $article_category ) as $term_slug ) {
          
          $term = get_term_by( 'slug', $term_slug, 'issuem_issue_categories' );
        
          $issue_cat_meta = get_option( 'issuem_issue_categories_' . $term->term_id . '_meta' );
            
          if ( !empty( $issue_cat_meta['category_order'] ) )
            $terms[ $issue_cat_meta['category_order'] ] = $term->slug;
          else
            $terms[ '-' . ++$count ] = $term->slug;
            
        }
      
      }
      
      krsort( $terms );
      $articles = array();
      
      foreach( $terms as $term ) {
      
        $category = array(
          'taxonomy'  => $cat_type,
          'field'   => 'slug',
          'terms'   => $term,
        );  
        
        $args['tax_query'] = array(
          'relation'  => 'AND',
          $issuem_issue,
          $category
        );
        
        $articles = array_merge( $articles, get_posts( $args ) );
      }
    
      //And we want all articles not in a category
      $category = array(
        'taxonomy'  => $cat_type,
        'field'   => 'slug',
        'terms'   => $terms, 
        'operator'  => 'NOT IN',
      );

      $args['tax_query'] = array(
        'relation' => 'AND',
        $issuem_issue,
        $category
      );

      $articles = array_merge( $articles, get_posts( $args ) );

      //Now we need to get rid of duplicates (assuming an article is in more than one category
      if ( !empty( $articles ) ) {
        
        foreach( $articles as $article ) {
        
          $post__in[] = $article->ID;
          
        }
        
        $args['post__in'] = array_unique( $post__in );
        $args['orderby']  = 'post__in';
        unset( $args['tax_query'] );
          
        $articles = get_posts( $args );
      
      }
      
    } else {
      
      if ( !empty( $article_category ) && 'all' !== $article_category ) {
          
        $category = array(
          'taxonomy'  => $cat_type,
          'field'   => 'slug',
          'terms'   => split( ',', $article_category ),
        );  
        
        $args['tax_query'] = array(
          'relation'  => 'AND',
          $issuem_issue,
          $category
        );
        
      }
        
      $articles = get_posts( $args );
      
    }

    if (is_front_page() || get_current_type() === "tax") {
      // wp_print_r( get_active_issuem_issue() );
      // wp_print_r( get_term_by( 'slug', $issue, 'issuem_issue' )->description );
      $main_results  = '<header class="entry-header">';
      $main_results .= '<h1 class="entry-title" itemprop="name">Editors Note</h1>';
      $main_results .= '<hr class="divider"></header>';
      $main_results .= '<div class="eds-note">' . get_term_by( 'slug', $issue, 'issuem_issue' )->description . '</div>';
      $main_results .= '<h1 class="entry-title" itemprop="name">Contents</h1>';
      $main_results .= '<hr class="divider">';
      $main_results .= '<a class="filter_btn">filter <i class="fa fa-angle-down"></i></a>';
      $main_results .= '<div class="filter-group">';
      $main_results .= '<label><input type="radio" name="filter-group" value="All" checked><span class="filter-group-item all">All</span></label>';

      foreach( $articles as $article ) {
        $cat = get_the_category( $article->ID )[0]->cat_name;
        array_push($cat_filter_list, $cat);
      }

      $cat_filter_list = array_count_values($cat_filter_list);

      function print_filter_category($cat_key) {
        $cat_name;
        $val_name;
        $output = '';
        global $music_count;
        global $essay_count;
        
        switch ($cat_key) {
          case "Visual Art":
            $cat_name = "fc_art";
            $val_name = "Art";
            break;
          case "Reviews":
            $cat_name = "fc_review";
            $val_name = "Reviews";
            break;
          case "Short Fiction":
            $cat_name = "fc_fiction";
            $val_name = "Fiction";
            break;
          case "Poetry":
            $cat_name = "fc_poetry";
            $val_name = "Poetry";
            break;
          case "Music":
          case "Fogged Clarity Sessions":
          case "Featured Album":
            $cat_name = "fc_music";
            $val_name = "Music";
            $music_count++;
            break;
          case "Interviews":
            $cat_name = "fc_interview";
            $val_name = "Interviews";
            break;
          case "Essays &amp; Nonfiction":
          case "Nonfiction":
          case "Polemics":
            $cat_name = "fc_nonfiction";
            $val_name = "Nonfiction";
            $essay_count++;
            break;
          default: // defaults to fc green
            $cat_name = "fc_poetry";
            $val_name = "Poetry";
        }

        if( $cat_name === "fc_music" ) {
          // special case since multiple categories are included under 'music' and we only want one filter for 'music'
          if( $music_count === 1 ) {
            $output = "<label><input type='radio' name='filter-group' value='$val_name'>";
            $output .= "<span class='filter-group-item $cat_name'>$val_name</span></label>";
          }
        } else if ( $cat_name === "fc_nonfiction" ) {
          if( $essay_count === 1 ) {
            $output = "<label><input type='radio' name='filter-group' value='$val_name'>";
            $output .= "<span class='filter-group-item $cat_name'>$val_name</span></label>";
          }
        } else {
          $output = "<label><input type='radio' name='filter-group' value='$val_name'>";
          $output .= "<span class='filter-group-item $cat_name'>$val_name</span></label>";
        }
        return $output;
      }

      foreach ($cat_filter_list as $cat_key => $cat_val) {
        //wp_print_r( $cat_key );
        if ($cat_val > 0) {
          $main_results .= print_filter_category($cat_key);
        }
      }

      $main_results .= '</div>';
      echo $main_results;
      
      // otherwise just show the title
    } else {
        // $main_results = '<h1 class="entry-title" itemprop="name">' . the_title() . '</h1>';
        $main_results .= '</header>';
        echo $main_results;
    }
    
    $results .= '<div class="issuem_articles_shortcode">';
  
    if ( $articles ) : 
    
      $old_post = $post;
      
      foreach( $articles as $article ) {

        $post_img;
        $gallery_img;
        $first_img;
        $fc_cat;
        $fc_cat_class;
        $is_podcast = false;
        $post_link = get_permalink( $article->ID );

        // check to see if the post has powerpress shortcode
        if (has_shortcode( $article->post_content, 'powerpress' )) {
          $is_podcast = true;
        }

        // TODO :: create a class list variable to store all the classes, then apply those classes all at once

        if ( has_post_thumbnail( $article->ID ) ) {
          // get the featured image
          $post_img = wp_get_attachment_url( get_post_thumbnail_id( $article->ID) );
          $wrap_results = '<div class="issuem_article has_image lrg podcast article-' . $article->ID . '" style="background-image:url(\'' . $post_img . '\'); background-size: cover;">';
        } else if ( has_shortcode( $article->post_content, 'gallery' ) ) {
          // get the first image out of the gallery
          $gallery = get_post_gallery_images( $article );
          $gallery_img = $gallery[0];
          if ( $gallery_img ) {
              // has image
              $wrap_results = '<div class="issuem_article has_image article-' . $article->ID . '" style="background-image:url(\'' . $gallery_img . '\'); background-size: cover;">';
          }
        } else {
          // get the first embedded image in the content
          $first_img = '';
          ob_start();
          ob_end_clean();
          $match = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $article->post_content, $matches);

          // grab the first image if it exists
          if (!empty($match)) {
            $first_img = $matches[1][0];
          }
          // otherwise, define a random default image
          if(empty($first_img)) {
            $theme_root = get_theme_root();
            $imgdir = '/fogged-clarity/dist/images/default_post_imgs/';
            $file_path = $theme_root . $imgdir;
            $imgs = glob($file_path . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
            $first_img = $imgs[array_rand($imgs)];
            // replace file server path with uri so we can use path in our CSS below
            $first_img = str_replace($theme_root, get_theme_root_uri(), $first_img);
          }

          if ( $first_img ) {
              // has image
              $wrap_results = '<div class="issuem_article has_image article-' . $article->ID . '" style="background-image:url(\'' . $first_img . '\'); background-size: 200px 200px;">';
          }
        }

        $fc_cat = get_the_category( $article->ID )[0]->cat_name;
        // wp_print_r($fc_cat);
        switch ( $fc_cat ) {
          case "Visual Art":
            $fc_cat_class = "fc_art";
            break;
          case "Reviews":
            $fc_cat_class = "fc_review";
            break;
          case "Short Fiction":
            $fc_cat_class = "fc_fiction";
            break;
          case "Poetry":
            $fc_cat_class = "fc_poetry";
            break;
          case "Music":
          case "Fogged Clarity Sessions":
          case "Featured Album":
            $fc_cat_class = "fc_music";
            break;
          case "Interviews":
            $fc_cat_class = "fc_interview";
            break;
          case "Essays &amp; Nonfiction":
          case "Nonfiction":
          case "Polemics":
            $fc_cat_class = "fc_nonfiction";
            break;
          default:
            $fc_cat_class = "fc_uncat";
        }

        $post = $article;
        setup_postdata( $article );

        $results .= $wrap_results;
        $results .= '<a class="outer_link" href="' . $post_link . '"></a>';
        if($is_podcast) $results .= '<div class="ribbon-wrapper"><div class="ribbon"><i class="fa fa-headphones"></i> Podcast</div></div>';
        $results .= '<div class="issuem_content_container ' . $fc_cat_class . '">';
        $results .= "\n" . issuem_replacements_args( $article_format, $post ) . "\n";
        $results .= '</div></div>';
      
      }
      
      if ( get_option( 'issuem_api_error_received' ) )
        $results .= '<div class="api_error"><p><a href="http://issuem.com/" target="_blank">' . __( 'Issue Management by ', 'issuem' ) . 'IssueM</a></div>';
    
      $post = $old_post;
  
    else :
  
      $results .= apply_filters( 'issuem_no_articles_found_shortcode_message', '<h1 class="issuem-entry-title no-articles-found">' . __( 'No articles Found', 'issuem' ) . '</h1>' );
  
    endif;

    $results .= '</div>';
    wp_reset_postdata();
    return $results;
  }
}



/*******************************************************************************************/



if ( !function_exists( 'do_custom_issuem_featured_rotator' ) ) {
  
  /**
   * Outputs Issue Featured Rotator Images HTML from shortcode call
   *
   * @since 1.0.0
   *
   * @param array $atts Arguments passed through shortcode
   * @return string HTML output of Issue Featured Rotator Images
   */
  function do_custom_issuem_featured_rotator( $atts ) {
    // var_dump("DO CUSTOM ROTATOR ");
    global $post;
    global $cfs;
    $results = '';
    
    $issuem_settings = get_issuem_settings();
    
    $defaults = array(
      'posts_per_page'    => -1,
      'offset'            => 0,
      'orderby'           => 'menu_order',
      'order'             => 'DESC',
      'issue'             => get_active_issuem_issue(),
      'show_title'        => true,
      'show_teaser'       => true,
      'show_byline'       => false,
      'article_category'  => 'all',
    );
    
    // Merge defaults with passed atts
    // Extract (make each array element its own PHP var
    extract( shortcode_atts( $defaults, $atts ) );
    
    $args = array(
      'posts_per_page'  => $posts_per_page,
      'offset'          => $offset,
      'post_type'       => 'article',
      'orderby'         => $orderby,
      'order'           => $order,
      'meta_key'        => '_featured_rotator',
      'issuem_issue'    => $issue,
    );
    
    if ( !empty( $issuem_settings['use_wp_taxonomies'] ) ) 
      $cat_type = 'category';
    else
      $cat_type = 'issuem_issue_categories';
    
    if ( !empty( $article_category ) && 'all' !== $article_category ) {
        
      $category = array(
        'taxonomy'  => $cat_type,
        'field'   => 'slug',
        'terms'   => split( ',', $article_category ),
      );  
      
      $args['tax_query'] = array(
        'relation'  => 'AND',
        $issuem_issue,
        $category
      );
      
    }
    
    $featured_articles = get_posts( $args );
    
    if ( $featured_articles ) :
      
      $results .= '<div id="issuem-featured-article-slideshowholder">'; 
      $results .= '<div class="issuem-flexslider">';
      $results .= '<ul class="slides">';
    
      /* start the loop */
      foreach( $featured_articles as $article ) {
        
        if ( has_post_thumbnail( $article->ID ) ) {
          
          $image = wp_get_attachment_image_src( get_post_thumbnail_id( $article->ID ), 'issuem-featured-rotator-image' );
          
          if ( !empty( $show_title ) ) 
            $title = get_the_title( $article->ID );
          else
            $title = '';
          
          if ( !empty( $show_teaser ) ) 
            $teaser = get_post_meta( $article->ID, '_teaser_text', true );
          else
            $teaser = '';
          
          if ( !empty( $show_byline ) ) {

            $author_name = get_issuem_author_name( $article );
            
            $byline = sprintf( __( 'By %s', 'issuem' ), apply_filters( 'issuem_author_name', $author_name, $article->ID ) );
          
          } else {
            
            $byline = '';
            
          }

          $cat_obj = get_the_category( $article->ID )[0];
          $cat = $cat_obj->cat_name;
          $cat_slug = $cat_obj->slug;
          // wp_print_r( $cat_slug );
          $cta = 'more';

          // get alignment from cfs var
          $align_output = '';
          $text_align_arr = $cfs->get('feature_text_align', $article->ID);
          $text_align = array_shift( $text_align_arr );
          if(!empty($text_align)) {
            $align_output = $text_align;
          } else {
            $align_output = 'center'; // default to center
          }

          // get text tone from cfs var
          $tone_output = '';
          $text_tone_arr = $cfs->get('feature_text_tone', $article->ID);
          $text_tone = array_shift( $text_tone_arr );
          if(!empty($text_tone)) {
            $tone_output = $text_tone;
          } else {
            $tone_output = 'light'; // default to light
          }
          
          $caption  = '<a href="' . get_permalink( $article->ID ) . '">';
          $caption .= '<span class="featured_slider_cat ' . $cat_slug . '">' . $cat . '</span>';
          $caption .= '<span class="featured_slider_title">' . $title . '</span>';
          $caption .= '<span class="featured_slider_teaser">' . $teaser . '</span>';
          $caption .= '<span class="featured_slider_byline">' . $byline . '</span>';
          $caption .= '<span class="featured_slider_cta ' . $cat_slug . '-cta">' . $cta . '</span></a>';

          $results .= '<li>';
          $results .= '<a href="' . get_permalink( $article->ID ) . '"><img src="' . $image[0] .'" alt="' .strip_tags( $caption ) . '" /></a>';
          // $results .= '<div class="flex-caption"><div class="flex-caption-content">' . $caption . '</div></div>';
          $results .= '<div class="flex-caption flex-' . $align_output . ' flex-' . $tone_output . '">' . $caption . '</div>';
          $results .= '</li>';
          
        }
        
      }
      
      $results .= '</ul>';  //slides
      $results .= '</div>'; //flexslider
      $results .= '</div>'; //issuem-featured-article-slideshowholder
          
      $results .= "<script type='text/javascript'>
            jQuery( window ).load( function(){
              jQuery( '.issuem-flexslider' ).issuem_flexslider({
              animation: 'fade',
              start: function(slider){
                jQuery('body').removeClass('loading');
              },
              controlNav: true,
              directionNav: true
              });
            });
            </script>";
    endif;
    return $results;
  }
}



/*******************************************************************************************/



if ( !function_exists( 'do_custom_issuem_archives' ) ) {
  
  /**
   * Outputs Issue Archives HTML from shortcode call
   *
   * @since 1.0.0
   *
   * @param array $atts Arguments passed through shortcode
   * @return string HTML output of Issue Archives
   */
  function do_custom_issuem_archives( $atts ) {
    
    $issuem_settings = get_issuem_settings();
    
    $defaults = array(
              'orderby'       => 'issue_order',
              'order'         => 'DESC',
              'limit'         => 0,
              'pdf_title'     => $issuem_settings['pdf_title'],
              'default_image' => $issuem_settings['default_issue_image'],
              'args'          => array( 'hide_empty' => 0 ),
            );
    extract( shortcode_atts( $defaults, $atts ) );
    
    if ( is_string( $args ) ) {
      $args = str_replace( '&amp;', '&', $args );
      $args = str_replace( '&#038;', '&', $args );
    }
    
    $args = apply_filters( 'do_issuem_archives_get_terms_args', $args );
    $issuem_issues = get_terms( 'issuem_issue', $args );
    $archives = array();
    $archives_no_issue_order = array();
    
    foreach ( $issuem_issues as $issue ) {
    
      $issue_meta = get_option( 'issuem_issue_' . $issue->term_id . '_meta' );
      
      // If issue is not a Draft, add it to the archive array;
      if ( !empty( $issue_meta['issue_status'] ) && ( 'Draft' !== $issue_meta['issue_status'] || current_user_can( apply_filters( 'see_issuem_draft_issues', 'manage_issues' ) ) ) ) {
      
        switch( $orderby ) {
          
          case "issue_order":
            if ( !empty( $issue_meta['issue_order'] ) )
              $archives[ $issue_meta['issue_order'] ] = array( $issue, $issue_meta );
            else 
              $archives_no_issue_order[] = array( $issue, $issue_meta );
            break;
            
          case "name":
            $archives[ $issue_meta['name'] ] = array( $issue, $issue_meta );
            break;
          
          case "term_id":
            $archives[ $issue->term_id ] = array( $issue, $issue_meta );
            break;
          
        }
      
      }
      
    }
    
    if ( 'issue_order' == $orderby && !empty( $archives_no_issue_order ) )
      $archives = array_merge( $archives_no_issue_order, $archives );
    
    if ( "DESC" == $order )
      krsort( $archives );
    else
      ksort( $archives );
      
    $archive_count = count( $archives ) - 1; //we want zero based
    
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    if ( !empty( $limit ) ) {
      $offset = ( $paged - 1 ) * $limit;
      $archives = array_slice( $archives, $offset, $limit );
    }
      
    $results = '<div class="issuem_archives_shortcode">';
    
    foreach ( $archives as $archive => $issue_array ) {
    
      $issue_meta = get_option( 'issuem_issue_' . $issue_array[0]->term_id . '_meta' );
      $cover_img_url = '';
      $issue_wrap = '';
        
      $class = '';
      if ( 'Draft' === $issue_meta['issue_status'] )
        $class = 'issuem_issue_draft';
      
      /*
        GET IMAGE
      */
      if ( !empty( $issue_array[1]['cover_image'] ) ) {
        $cover_img_url = wp_get_attachment_url( $issue_array[1]['cover_image'], 'issuem-cover-image' );
        $results .= '<div id="issue-' . $issue_array[0]->term_id . '" style="background-image:url(\'' . $cover_img_url . '\');" class="issuem_archive has_image "' . $class . '">';
      } else {
        $results .= '<div id="issue-' . $issue_array[0]->term_id . '" class="issuem_archive ' . $class . '">';
      }
      
      if ( 0 == $issuem_settings['page_for_articles'] )
        // $article_page = get_bloginfo( 'wpurl' ) . '/' . apply_filters( 'issuem_page_for_articles', 'article/' );
        $article_page = site_url() . '/' . apply_filters( 'issuem_page_for_articles', 'article/' );
      else
        $article_page = get_page_link( $issuem_settings['page_for_articles'] );
    
      $issue_url = get_term_link( $issue_array[0], 'issuem_issue' );
        if ( !empty( $issuem_settings['use_issue_tax_links'] ) || is_wp_error( $issue_url ) ) {
            $issue_url = add_query_arg( 'issue', $issue_array[0]->slug, $article_page );
        }
        
      if ( !empty( $issue_array[1]['pdf_version'] ) || !empty( $issue_meta['external_pdf_link'] ) ) {
        
        $pdf_url = empty( $issue_meta['external_pdf_link'] ) ? apply_filters( 'issuem_pdf_attachment_url', wp_get_attachment_url( $issue_array[1]['pdf_version'] ), $issue_array[1]['pdf_version'] ) : $issue_meta['external_pdf_link'];
        
        $pdf_line = '<a href="' . $pdf_url . '" target="' . $issuem_settings['pdf_open_target'] . '">';
        
        if ( 'PDF Archive' == $issue_array[1]['issue_status'] ) {
          
          $issue_url = $pdf_url;
          $pdf_line .= empty( $pdf_only_title ) ? $issuem_settings['pdf_only_title'] : $pdf_only_title;
          
        } else {
          
          $pdf_line .= empty( $pdf_title ) ? $issuem_settings['pdf_title'] : $pdf_title;
        
        }
        
        $pdf_line .= '</a>';
        
      } else {
      
        $pdf_line = apply_filters( 'issuem_pdf_version', '&nbsp;', $pdf_title, $issue_array[0] );
        
      }
            
      if ( !empty( $issue_meta['external_link'] ) )
        $issue_url = apply_filters( 'archive_issue_url_external_link', $issue_meta['external_link'], $issue_url );
      
        $results .= '<a class="outer_link" href="' . $issue_url . '"></a>';
        $results .= '<div class="issuem_content_container">';
        $results .= '<a class="issuem_article_link" href="' . $issue_url . '">';
        $results .= '<h4 class="issuem_issue_title">' . $issue_array[0]->name . '</h4>';
        $results .= '</a></div>';
        $results .= '</div>';
    }
    
    if ( !empty( $limit ) ) {
    
      $url = remove_query_arg( array( 'page', 'paged' ) );
    
      $results .= '<div class="next_previous_archive_pagination">';
    
      if ( 0 === $offset && $limit < $archive_count ) {
        //Previous link only
        $results .= '<div class="alignleft"><a href="' . add_query_arg( 'paged', $paged + 1, $url ) . '">' . __( 'Previous Archives', 'issuem' ) . '</a></div>';
        
      } else if ( $offset >= $archive_count ) {
        //Next link only
        $results .= '<div class="alignright"><a href="' . add_query_arg( 'paged', $paged - 1, $url ) . '">' . __( 'Next Archives', 'issuem' ) . '</a></div>';
      } else {
        //Next and Previous Links
        $results .= '<div class="alignleft"><a href="' . add_query_arg( 'paged', $paged + 1, $url ) . '">' . __( 'Previous Archives', 'issuem' ) . '</a></div>';
        $results .= '<div class="alignright"><a href="' . add_query_arg( 'paged', $paged - 1, $url ) . '">' . __( 'Next Archives', 'issuem' ) . '</a></div>';
      }
      
      
      $results .= '</div>';
    }
    
    if ( get_option( 'issuem_api_error_received' ) )
      $results .= '<div class="api_error"><p><a href="http://issuem.com/" target="_blank">' . __( 'Issue Management by ', 'issuem' ) . 'IssueM</a></div>';
      
    $results .= '</div>';
    
    return $results;
    
  }
}