<?php
function create_custom_post_type_event() {
    $labels = array(
        'name' => _x('Events', 'Post Type General Name', 'textdomain'),
        'singular_name' => _x('Event', 'Post Type Singular Name', 'textdomain'),
        'menu_name' => __('Events', 'textdomain'),
        'name_admin_bar' => __('Event', 'textdomain'),
        'archives' => __('Event Archives', 'textdomain'),
        'attributes' => __('Event Attributes', 'textdomain'),
        'parent_item_colon' => __('Parent Event:', 'textdomain'),
        'all_items' => __('All Events', 'textdomain'),
        'add_new_item' => __('Add New Event', 'textdomain'),
        'add_new' => __('Add New', 'textdomain'),
        'new_item' => __('New Event', 'textdomain'),
        'edit_item' => __('Edit Event', 'textdomain'),
        'update_item' => __('Update Event', 'textdomain'),
        'view_item' => __('View Event', 'textdomain'),
        'view_items' => __('View Events', 'textdomain'),
        'search_items' => __('Search Event', 'textdomain'),
        'not_found' => __('Not found', 'textdomain'),
        'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
        'featured_image' => __('Featured Image', 'textdomain'),
        'set_featured_image' => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image' => __('Use as featured image', 'textdomain'),
        'insert_into_item' => __('Insert into event', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this event', 'textdomain'),
        'items_list' => __('Events list', 'textdomain'),
        'items_list_navigation' => __('Events list navigation', 'textdomain'),
        'filter_items_list' => __('Filter events list', 'textdomain'),
    );
    $args = array(
        'label' => __('Event', 'textdomain'),
        'description' => __('Event Description', 'textdomain'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'show_in_rest' => true,
    );
    register_post_type('event', $args);
}
add_action('init', 'create_custom_post_type_event', 0);

function followandrew_theme_support()
{
    //Adds dynamic title tag support
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'followandrew_theme_support');

function followandrew_menus()
{
    $locations = array(
        'primary' => 'Desktop Primary Left Sidebar',
        'footer' => 'Footer Menu Items'
    );

    register_nav_menus($locations);
}

add_action('init', 'followandrew_menus');

function followandrew_register_styles()
{
    $version = wp_get_theme()->get('Version');

    wp_enqueue_style('followandrew-style', get_template_directory_uri() . "/style.css", array('followandrew-bootstrap'), $version, 'all');
    wp_enqueue_style('followandrew-bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css", array(), '4.4.1', 'all');
    wp_enqueue_style('followandrew-fontawesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css", array(), '5.13.0', 'all');
}

add_action('wp_enqueue_scripts', 'followandrew_register_styles');

function followandrew_register_scripts()
{

    wp_enqueue_script('followandrew-jquery', "https://code.jquery.com/jquery-3.4.1.slim.min.js", array(), '3.4.1', true);
    wp_enqueue_script('followandrew-popper', "https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js", array(), '1.16.0', true);
    wp_enqueue_script('followandrew-bootstrap', "https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js", array(), '4.4.1', true);
    wp_enqueue_script('followandrew-mainjs', get_template_directory_uri() . "/assets/js/main.js", array(), '1.0', true);


}
add_action('wp_enqueue_scripts', 'followandrew_register_scripts');

function followandrew_widget_areas()
{
    register_sidebar(
        array(
            'name' => 'Sidebar Area',
            'id' => 'sidebar-1',
            'description' => 'Sidebar Widget Area',
            'before_title' => '',
            'after_title' => '',
            'before_widget' => '<ul class="social-list list-inline py-3 mx-auto">',
            'after_widget' => '</ul>'
        )
    );
    
    register_sidebar(
        array(
            'name' => 'Footer Area',
            'id' => 'footer-1',
            'description' => 'Footer Widget Area',
            'before_title' => '',
            'after_title' => '',
            'before_widget' => '',
            'after_widget' => ''
        )
    );
}

add_action('widgets_init', 'followandrew_widget_areas');
?>