<?php
/*
    Plugin Name: Custom post type
    Description: Custom post type block for gutenberg editor
    Version 1.0
    Author: vladmasliuk@gmail.com
*/

/*
*	Create custom post type
*/
function locals_cpt() {
    $labels = array(
        'name'                => _x( 'Locals', 'Post Type General Name' ),
        'singular_name'       => _x( 'Locals', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Locals' ),
        'parent_item_colon'   => __( 'Parent Local' ),
        'all_items'           => __( 'All Locals' ),
        'view_item'           => __( 'View Local' ),
        'add_new_item'        => __( 'Add New Local' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Local' ),
        'update_item'         => __( 'Update Local' ),
        'search_items'        => __( 'Search Local' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
     
    $args = array(
        'label'               => __( 'locals' ),
        'description'         => __( '' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail'),
        'taxonomies'          => array( 'genres' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => 4,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
    );
    register_post_type( 'locals', $args );
 
}
add_action( 'init', 'locals_cpt', 0 );

/*
*	Add styles
*/
add_action('wp_enqueue_scripts', 'cpt_styles');
function cpt_styles() {
    wp_enqueue_style( 'cpt-style', plugin_dir_url(__FILE__) . '/css/style.css' );
}

/*
*	Front template
*/
function locals_querry($content){
    if(is_single() && 'post' == get_post_type()){
        ob_start();?>
            <div class="locals-container">
                <h1 class="locals-title">Locals</h1>
                <?php 
                    $args_locals = array(
                        'post_type' => 'locals',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'desc'
                    );
                    $the_query_locals = new WP_Query( $args_locals );
                    if ( $the_query_locals->have_posts() ) :
                        global $post;
                ?>
                    <div class="locals-wrap">
                        <?php while ( $the_query_locals->have_posts() ) : $the_query_locals->the_post(); ?>
                            <div class="loc-item">
                                <?php if (has_post_thumbnail( $post->ID ) ): ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <div class="loc-item-img">
                                            <?php the_post_thumbnail( '', [ 'alt' => esc_html ( get_the_title() ) ] );  ?>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                    <div class="loc-item-text">
                                        <a href="<?php the_permalink(); ?>">
                                            <h3><?php the_title(); ?></h3>
                                        </a>
                                    </div>
                            </div>
                        <?php endwhile; 
                        wp_reset_postdata(); ?>
                    </div>
                    <div class="all-locals-link">
                        <a href="/locals">View all locals</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php
        return $content . ob_get_clean();
    }
    return $content;
}

add_filter('the_content', 'locals_querry');