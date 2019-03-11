<?php
function add_additional_fields_meta_box()
{
    add_meta_box(
        'additional_fields_meta_box', // $id
        'Additional Fields', // $title
        'show_additional_fields_meta_box', // $callback
        'neoms_events', // $screen
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'add_additional_fields_meta_box');
