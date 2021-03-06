<?php
add_action('init', 'register_events_post_type');
function register_events_post_type()
{
    register_taxonomy('event-category', ['events'],
        [
            'labels' => [
                'name' => __('Event Categories'),
                'menu_name' => __('Event Categories'),
                'singular_name' => __('Event Category'),
                'all_items' => __('All Categories'),
            ],
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'rewrite' => [
                'slug' => 'event-category',
                'hierarchical' => true,
                'with_front' => false,
            ],
        ]
    );

    register_post_type('events',
        [
            'labels' => [
                'name' => __('Events'),
                'menu_name' => __('Event Manager'),
                'singular_name' => __('Event'),
                'all_items' => __('All Events'),
                'add_new_item' => __('Add New Event'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'comments', 'post-formats', 'revisions'],
            'hierarchical' => false,
            'has_archive' => true,
            'taxonomies' => ['event-category'],
            'rewrite' => [
                'slug' => 'event',
                'hierarchical' => true,
                'with_front' => false,
            ],
        ]
    );
}

add_action('generate_rewrite_rules', 'register_event_rewrite_rules');
function register_event_rewrite_rules($wp_rewrite)
{
    $new_rules = array(
      'event/([^/]+)/?$' => 'index.php?post_type=events&events=' . $wp_rewrite->preg_index(1),
      'event-category/([^/]+)/?$' => 'index.php?event-category=' . $wp_rewrite->preg_index(1),
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
