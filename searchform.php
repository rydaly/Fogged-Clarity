<?php
/**
 * The template for displaying search forms in foggedclarity
 *
 * @package foggedclarity
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <div class="search-wrap">
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'SEARCH', 'placeholder', 'foggedclarity' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
  	<button type="submit" class="search-submit" value="">
      <i class="fa fa-search"></i>
    </button>
  </div>
</form>
