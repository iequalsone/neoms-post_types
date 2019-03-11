<?php
function add_additional_fields_meta_box()
{
    add_meta_box(
        'additional_fields_meta_box', // $id
        'Additional Fields', // $title
        'show_additional_fields_meta_box', // $callback
        'events', // $screen
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'add_additional_fields_meta_box');

function show_additional_fields_meta_box()
{
    global $post;
    $meta = get_post_meta($post->ID, 'additional_fields', true);?>

  <input
    type="hidden"
    name="additional_fields_nonce"
    value="<?php echo wp_create_nonce(basename(__FILE__)); ?>">

    <p>
      <label for="additional_fields[text]">Input Text</label>
      <br>
      <input
        type="text"
        name="additional_fields[text]"
        id="additional_fields[text]"
        class="regular-text"
        value="<?=(isset($meta['text']) ? $meta['text'] : '');?>">
    </p>

    <p>
      <label for="additional_fields[textarea]">Textarea</label>
      <br>
      <textarea
        name="additional_fields[textarea]"
        id="additional_fields[textarea]"
        rows="5"
        cols="30"
        style="width:500px;"><?=(isset($meta['textarea']) ? $meta['textarea'] : '');?></textarea>
    </p>

    <p>
      <label for="additional_fields[checkbox]">Checkbox
        <input
          type="checkbox"
          name="additional_fields[checkbox]"
          value="checkbox"
          <?=((isset($meta['checkbox']) && $meta['checkbox'] === 'checkbox') ? 'checked' : '')?>
        >
      </label>
    </p>

    <p>
      <label for="additional_fields[select]">Select Menu</label>
      <br>
      <select name="additional_fields[select]" id="additional_fields[select]">
          <option
            value="option-one"
            <?=(isset($meta['select']) ? selected($meta['select'], 'option-one') : '');?>>Option One</option>
          <option
            value="option-two"
            <?=(isset($meta['select']) ? selected($meta['select'], 'option-two') : '');?>>Option Two</option>
      </select>
    </p>

    <p>
      <label for="additional_fields[image]">Image Upload</label><br>
      <input
        type="text"
        name="additional_fields[image]"
        id="additional_fields[image]"
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
    ></div>
    <script>
      jQuery(document).ready(function ($) {
        // Instantiates the variable that holds the media library frame.
        var meta_image_frame;
        // Runs when the image button is clicked.
        $('.image-upload').click(function (e) {
          // Get preview pane
          var meta_image_preview = $(this).parent().parent().children('.image-preview');
          // Prevents the default action from occuring.
          e.preventDefault();
          var meta_image = $(this).parent().children('.meta-image');
          // If the frame already exists, re-open it.
          if (meta_image_frame) {
            meta_image_frame.open();
            return;
          }
          // Sets up the media library frame
          meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: {
              text: meta_image.button
            }
          });
          // Runs when an image is selected.
          meta_image_frame.on('select', function () {
            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
            // Sends the attachment URL to our custom image input field.
            meta_image.val(media_attachment.url);
            meta_image_preview.children('img').attr('src', media_attachment.url);
          });
          // Opens the media library frame.
          meta_image_frame.open();
        });
      });
    </script>
  <?php }

function save_additional_fields_meta($post_id)
{
    // verify nonce
    if (isset($_POST['additional_fields_nonce']) && !wp_verify_nonce($_POST['additional_fields_nonce'], basename(__FILE__))) {
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

    $old = get_post_meta($post_id, 'additional_fields', true);
    $new = (isset($_POST['additional_fields']) ? $_POST['additional_fields'] : '');

    if ($new && $new !== $old) {
        update_post_meta($post_id, 'additional_fields', $new);
    } elseif ('' === $new && $old) {
        delete_post_meta($post_id, 'additional_fields', $old);
    }
}
add_action('save_post', 'save_additional_fields_meta');