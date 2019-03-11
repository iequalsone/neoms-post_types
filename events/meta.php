<?php
function add_event_fields_meta_box()
{
    add_meta_box(
        'event_fields_meta_box', // $id
        'Additional Fields', // $title
        'show_event_fields_meta_box', // $callback
        'events', // $screen
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'add_event_fields_meta_box');

function show_event_fields_meta_box()
{
    global $post;
    $meta = get_post_meta($post->ID, 'event_fields', true);?>

  <input
    type="hidden"
    name="event_fields_nonce"
    value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">

    <?=generate_input_field(
        'event_fields',
        'sub_title',
        'Sub-title',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'start_date',
        'Start Date',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'end_date',
        'End Date',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'time',
        'Time',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'location',
        'Location',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'ticket_cost',
        'Ticket Cost',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'website',
        'Website',
        'regular-text',
        $meta);?>

    <?=generate_input_field(
        'event_fields',
        'floating_tag',
        'Floating Tag',
        'regular-text',
        $meta);?>

    <?=generate_image_field(
        'event_fields',
        'event_image',
        'Event Image',
        'meta-image regular-text',
        $meta);?>

    <!-- <p>
      <label for="event_fields[image]">Image Upload</label><br>
      <input
        type="text"
        name="event_fields[image]"
        id="event_fields[image]"
        class="meta-image regular-text"
        value="<?=(isset($meta['image']) ? $meta['image'] : '');?>">
      <input
        type="button"
        class="button image-upload"
        value="Browse">
    </p>
    <div class="image-preview">
      <img
        src="<?=(isset($meta['image']) ? $meta['image'] : '');?>"
        style="max-width: 250px;"
    ></div> -->

    <!-- <p>
      <label for="event_fields[textarea]">Textarea</label>
      <br>
      <textarea
        name="event_fields[textarea]"
        id="event_fields[textarea]"
        rows="5"
        cols="30"
        style="width:500px;"><?=(isset($meta['textarea']) ? $meta['textarea'] : '');?></textarea>
    </p> -->

    <!-- <p>
      <label for="event_fields[checkbox]">Checkbox
        <input
          type="checkbox"
          name="event_fields[checkbox]"
          value="checkbox"
          <?=((isset($meta['checkbox']) && $meta['checkbox'] === 'checkbox') ? 'checked' : '')?>
        >
      </label>
    </p> -->

    <!-- <p>
      <label for="event_fields[select]">Select Menu</label>
      <br>
      <select name="event_fields[select]" id="event_fields[select]">
          <option
            value="option-one"
            <?=(isset($meta['select']) ? selected($meta['select'], 'option-one') : '');?>>Option One</option>
          <option
            value="option-two"
            <?=(isset($meta['select']) ? selected($meta['select'], 'option-two') : '');?>>Option Two</option>
      </select>
    </p> -->
  <?php
wp_enqueue_script('meta_image', plugin_dir_url(__FILE__) . '/src/js/meta_image.js');
}

function generate_input_field($field_arr, $field_name, $field_label, $class, $meta)
{
    $output =
        '<p>
      <label for="' . $field_arr . '[' . $field_name . ']">' . $field_label . '</label>
      <br>
      <input
        type="text"
        name="' . $field_arr . '[' . $field_name . ']"
        id="' . $field_arr . '[' . $field_name . ']"
        class="' . $class . '"
        value="' . (isset($meta[$field_name]) ? $meta[$field_name] : '') . '">
    </p>';
    return $output;
}

function generate_image_field($field_arr, $field_name, $field_label, $class, $meta)
{
    $output =
        '<div>
          <p>
            <label for="' . $field_arr . '[' . $field_name . ']">Image Upload</label><br>
            <input
              type="text"
              name="' . $field_arr . '[' . $field_name . ']"
              id="' . $field_arr . '[' . $field_name . ']"
              class="' . $class . '"
              value="' . (isset($meta[$field_name]) ? $meta[$field_name] : '') . '">
            <input
              type="button"
              class="button image-upload"
              value="Browse">
          </p>

          <div class="image-preview">
            <img
              src="' . (isset($meta[$field_name]) ? $meta[$field_name] : '') . '"
              style="max-width: 250px;"
          ></div>
        </div>
        ';
    return $output;
}

function save_event_fields_meta($post_id)
{
    // verify nonce
    if (isset($_POST['event_fields_nonce']) && !wp_verify_nonce($_POST['event_fields_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if (isset($_POST['post_type']) && 'page' === $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    $old = get_post_meta($post_id, 'event_fields', true);
    $new = (isset($_POST['event_fields']) ? $_POST['event_fields'] : '');

    if ($new && $new !== $old) {
        update_post_meta($post_id, 'event_fields', $new);
    } elseif ('' === $new && $old) {
        delete_post_meta($post_id, 'event_fields', $old);
    }
}
add_action('save_post', 'save_event_fields_meta');