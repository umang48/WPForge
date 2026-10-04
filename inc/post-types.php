<?php
/**
 * Register Custom Post Types and Taxonomies
 *
 * @package WPForge
 */

function wpforge_register_post_types() {

    // 1. Register 'Projects' Custom Post Type
    $project_labels = array(
        'name'                  => _x( 'Projects', 'Post type general name', 'wpforge' ),
        'singular_name'         => _x( 'Project', 'Post type singular name', 'wpforge' ),
        'menu_name'             => _x( 'Projects', 'Admin Menu text', 'wpforge' ),
        'name_admin_bar'        => _x( 'Project', 'Add New on Toolbar', 'wpforge' ),
        'add_new'               => __( 'Add New', 'wpforge' ),
        'add_new_item'          => __( 'Add New Project', 'wpforge' ),
        'new_item'              => __( 'New Project', 'wpforge' ),
        'edit_item'             => __( 'Edit Project', 'wpforge' ),
        'view_item'             => __( 'View Project', 'wpforge' ),
        'all_items'             => __( 'All Projects', 'wpforge' ),
        'search_items'          => __( 'Search Projects', 'wpforge' ),
        'not_found'             => __( 'No projects found.', 'wpforge' ),
        'not_found_in_trash'    => __( 'No projects found in Trash.', 'wpforge' ),
    );

    $project_args = array(
        'labels'             => $project_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'projects' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-portfolio',
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
    );

    register_post_type( 'project', $project_args );

    // 2. Register 'Project Type' Taxonomy
    $tax_labels = array(
        'name'              => _x( 'Project Types', 'taxonomy general name', 'wpforge' ),
        'singular_name'     => _x( 'Project Type', 'taxonomy singular name', 'wpforge' ),
        'search_items'      => __( 'Search Project Types', 'wpforge' ),
        'all_items'         => __( 'All Project Types', 'wpforge' ),
        'parent_item'       => __( 'Parent Project Type', 'wpforge' ),
        'parent_item_colon' => __( 'Parent Project Type:', 'wpforge' ),
        'edit_item'         => __( 'Edit Project Type', 'wpforge' ),
        'update_item'       => __( 'Update Project Type', 'wpforge' ),
        'add_new_item'      => __( 'Add New Project Type', 'wpforge' ),
        'new_item_name'     => __( 'New Project Type Name', 'wpforge' ),
        'menu_name'         => __( 'Project Type', 'wpforge' ),
    );

    $tax_args = array(
        'hierarchical'      => true, 
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-type' ),
        'show_in_rest'      => true, 
    );

    register_taxonomy( 'project_type', array( 'project' ), $tax_args );

    // 3. Register 'Services' Custom Post Type
    $service_labels = array(
        'name'                  => _x( 'Services', 'Post type general name', 'wpforge' ),
        'singular_name'         => _x( 'Service', 'Post type singular name', 'wpforge' ),
        'menu_name'             => _x( 'Services', 'Admin Menu text', 'wpforge' ),
        'name_admin_bar'        => _x( 'Service', 'Add New on Toolbar', 'wpforge' ),
        'add_new'               => __( 'Add New', 'wpforge' ),
        'add_new_item'          => __( 'Add New Service', 'wpforge' ),
        'new_item'              => __( 'New Service', 'wpforge' ),
        'edit_item'             => __( 'Edit Service', 'wpforge' ),
        'view_item'             => __( 'View Service', 'wpforge' ),
        'all_items'             => __( 'All Services', 'wpforge' ),
        'search_items'          => __( 'Search Services', 'wpforge' ),
        'not_found'             => __( 'No services found.', 'wpforge' ),
        'not_found_in_trash'    => __( 'No services found in Trash.', 'wpforge' ),
    );

    $service_args = array(
        'labels'             => $service_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'services' ),
        'capability_type'    => 'post',
        'has_archive'        => false, // Typically services are just standard pages rather than an archive feed
        'hierarchical'       => false,
        'menu_position'      => 21,
        'menu_icon'          => 'dashicons-admin-tools',
        'show_in_rest'       => true,
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
    );

    register_post_type( 'service', $service_args );

    // 4. Register 'Team Members' Custom Post Type
    $team_labels = array(
        'name'                  => _x( 'Team', 'Post type general name', 'wpforge' ),
        'singular_name'         => _x( 'Team Member', 'Post type singular name', 'wpforge' ),
        'menu_name'             => _x( 'Team', 'Admin Menu text', 'wpforge' ),
        'name_admin_bar'        => _x( 'Team Member', 'Add New on Toolbar', 'wpforge' ),
        'add_new'               => __( 'Add New', 'wpforge' ),
        'add_new_item'          => __( 'Add New Team Member', 'wpforge' ),
        'new_item'              => __( 'New Team Member', 'wpforge' ),
        'edit_item'             => __( 'Edit Team Member', 'wpforge' ),
        'view_item'             => __( 'View Team Member', 'wpforge' ),
        'all_items'             => __( 'All Team Members', 'wpforge' ),
        'search_items'          => __( 'Search Team', 'wpforge' ),
        'not_found'             => __( 'No team members found.', 'wpforge' ),
        'not_found_in_trash'    => __( 'No team members found in Trash.', 'wpforge' ),
    );

    $team_args = array(
        'labels'             => $team_labels,
        'public'             => false, // Team members usually don't need dedicated single URLs, they appear on an 'About' or 'Team' page
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-groups',
        'show_in_rest'       => true, // Still need REST API so we can build custom blocks for the team grid
        'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
    );

    register_post_type( 'team_member', $team_args );


    // 1. Register Team Members CPT
    $team_labels = array(
        'name'                  => _x( 'Team Members', 'Post Type General Name', 'wpforge' ),
        'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'wpforge' ),
        'menu_name'             => __( 'Team', 'wpforge' ),
        'all_items'             => __( 'All Team Members', 'wpforge' ),
        'add_new_item'          => __( 'Add New Team Member', 'wpforge' ),
        'edit_item'             => __( 'Edit Team Member', 'wpforge' ),
    );
    
    $team_args = array(
        'label'                 => __( 'Team Member', 'wpforge' ),
        'labels'                => $team_labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 22,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true, // Enables Gutenberg editor
    );
    register_post_type( 'team', $team_args );

    // 2. Register Testimonials CPT
    $testimonial_labels = array(
        'name'                  => _x( 'Testimonials', 'Post Type General Name', 'wpforge' ),
        'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'wpforge' ),
        'menu_name'             => __( 'Testimonials', 'wpforge' ),
        'all_items'             => __( 'All Testimonials', 'wpforge' ),
        'add_new_item'          => __( 'Add New Testimonial', 'wpforge' ),
        'edit_item'             => __( 'Edit Testimonial', 'wpforge' ),
    );
    
    $testimonial_args = array(
        'label'                 => __( 'Testimonial', 'wpforge' ),
        'labels'                => $testimonial_labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        'hierarchical'          => false,
        'public'                => false, // Testimonials usually don't need their own single URL
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 23,
        'menu_icon'             => 'dashicons-testimonial',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false, // Keeps them out of sitemaps as standalone pages
        'show_in_rest'          => true,
    );
    register_post_type( 'testimonial', $testimonial_args );
}
add_action( 'init', 'wpforge_register_post_types' );