<?php

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
    

/***************** Scroll to load more *****************/

/* Layout for infinite scroll */

function be_post_summary(){
    get_template_part( 'template-parts/post/content', get_post_format() );
}

/**
 * AJAX Load More
 * 
 */
function be_ajax_load_more() {
    $args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
    $args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'post';
    $args['paged'] = esc_attr( $_POST['page'] );
    $args['post_status'] = 'publish';
    ob_start();
    $loop = new WP_Query( $args );
    if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
        be_post_summary();
    endwhile; endif; wp_reset_postdata();
    $data = ob_get_clean();
    wp_send_json_success( $data );
    wp_die();
}
add_action( 'wp_ajax_be_ajax_load_more', 'be_ajax_load_more' );
add_action( 'wp_ajax_nopriv_be_ajax_load_more', 'be_ajax_load_more' );

/**
 * Javascript for Load More
 *
 */
function be_load_more_js() {
    global $wp_query;
    $args = array(
        'url'   => admin_url( 'admin-ajax.php' ),
        'query' => $wp_query->query,
    );
            
    wp_enqueue_script( 'be-load-more', get_stylesheet_directory_uri() . '/assets/js/load-more.js', array( 'jquery' ), '1.0', true );
    wp_localize_script( 'be-load-more', 'beloadmore', $args );
    
}
add_action( 'wp_enqueue_scripts', 'be_load_more_js' );