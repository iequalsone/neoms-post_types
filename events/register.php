<?php
add_action('init', 'register_naoems_events_post_type');
function register_naoems_events_post_type()
{
    register_post_type('neoms-events',
        [
            'labels' => [
                'name' => __('Events', 'neoms_theme'),
                'menu_name' => __('Event Manager', 'neoms_theme'),
                'singular_name' => __('Event', 'neoms_theme'),
                'all_items' => __('All Events', 'neoms_theme'),
                'add_new_item' => __('Add New Event', 'neoms_theme'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'supports' => ['title', 'editor', 'author', 'thumbnail', 'comments', 'post-formats', 'revisions'],
            'hierarchical' => false,
            'has_archive' => 'events',
            'taxonomies' => ['event-category'],
            'rewrite' => ['slug' => 'events', 'hierarchical' => true, 'with_front' => false],
        ]
    );
    register_taxonomy('event-category', ['neoms-events'],
        [
            'labels' => [
                'name' => __('Event Categories', 'neoms_theme'),
                'menu_name' => __('Event Categories', 'neoms_theme'),
                'singular_name' => __('Event Category', 'neoms_theme'),
                'all_items' => __('All Categories', 'neoms_theme'),
            ],
            'public' => true,
            'hierarchical' => true,
            'show_ui' => true,
            'rewrite' => ['slug' => 'events', 'hierarchical' => true, 'with_front' => false],
        ]
    );
}

add_action('generate_rewrite_rules', 'register_event_rewrite_rules');
function register_event_rewrite_rules($wp_rewrite)
{
    $new_rules = array(
        'events/([^/]+)/?$' => 'index.php?event-category=' . $wp_rewrite->preg_index(1), // 'events/any-character/'
        'events/([^/]+)/([^/]+)/?$' => 'index.php?post_type=neoms-events&event-category=' . $wp_rewrite->preg_index(1) . '&neoms-events=' . $wp_rewrite->preg_index(2),
        // 'events/any-character/post-slug/'
        'products/([^/]+)/([^/]+)/page/(\d{1,})/?$' => 'index.php?post_type=neoms-events&event-category=' . $wp_rewrite->preg_index(1) . '&paged=' . $wp_rewrite->preg_index(3),
        // match paginated results for a sub-category archive
        'events/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?post_type=neoms-events&event-category=' . $wp_rewrite->preg_index(2) . '&neoms-events=' . $wp_rewrite->preg_index(3),
        // 'events/any-character/sub-category/post-slug/'
        'events/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?post_type=neoms-events&event-category=' . $wp_rewrite->preg_index(3) . '&neoms-events=' . $wp_rewrite->preg_index(4),
        // 'events/any-character/sub-category/sub-sub-category/post-slug/'
    );
    $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
