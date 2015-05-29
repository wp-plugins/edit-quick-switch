<?php
/**
 * Plugin Name: Edit Quick Switch
 * Plugin URI:  http://galengidman.com/plugins/edit-quick-switch/
 * Description: Easily switch between edit screens on posts, pages, and custom post types.
 * Author:      Galen Gidman
 * Author URI:  http://galengidman.com/
 * Version:     1.0.0
 */

/**
 * Enqueue styles and scripts for the admin features.
 */
function eqs_assets() {

  $screen = get_current_screen();

  if ( $screen->base === 'post' ) {

    wp_enqueue_style(  'select2', plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css' );
    wp_enqueue_style(  'eqs',     plugin_dir_url( __FILE__ ) . 'assets/css/eqs.css' );

    wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'eqs',     plugin_dir_url( __FILE__ ) . 'assets/js/eqs.js',         array( 'jquery', 'select2' ) );

  }

}
add_action( 'admin_enqueue_scripts', 'eqs_assets' );

/**
 * Registers the meta box for the switcher dropdown.
 */
function eqs_add_switch_meta_box() {

  $post_types = get_post_types( array( 'public' => true ) );
  unset( $post_types['attachment'] );
  array_values( $post_types );

  foreach ( $post_types as $post_type ) {

    add_meta_box(
      'eqs-switch-box',
      __( 'Quick Switch', 'eqs' ),
      'eqs_switch_meta_box_callback',
      $post_type,
      'side',
      'high'
    );

  }

}
add_action( 'add_meta_boxes', 'eqs_add_switch_meta_box' );

/**
 * Callback for the switch dropdown content.
 */
function eqs_switch_meta_box_callback() {

  $args  = array(
    'posts_per_page' => 500,
    'post_type'      => get_post_type(),
    'no_rows_found'  => true
  );

  $posts = new WP_Query( $args );

  if ( $posts->have_posts() ) :

  ?>

  <select id="eqs-switcher">
    <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
      <option
        value="<?php echo esc_url( get_edit_post_link( get_the_ID() ) ); ?>"
        <?php if ( isset( $_GET['post'] ) && get_the_ID() == $_GET['post'] ) echo 'selected'; ?>
      >
        <?php the_title_attribute(); ?>
      </option>
    <?php endwhile; ?>
  </select>

  <?php

  endif;

}
