<?php
if (!defined('ABSPATH')) {
    exit;
}

class WC_Fullscreen_Video_Handler {
    public function __construct() {
        // Backend hooks
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_video_fields']);
        add_action('woocommerce_process_product_meta', [$this, 'save_video_fields']);

        // Frontend hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('woocommerce_before_single_product', [$this, 'display_video_container'], 5);
    }

    // Add video fields to product edit page
    public function add_video_fields() {
        global $post;

        echo '<div class="options_group">';

        // Desktop Video
        woocommerce_wp_text_input([
            'id' => '_wcfv_desktop_video',
            'label' => __('Desktop Video URL', 'woocommerce-fullscreen-video'),
            'desc_tip' => true,
            'description' => __('Enter video URL or select from media library for desktop devices', 'woocommerce-fullscreen-video'),
            'placeholder' => 'https://example.com/video.mp4',
            'type' => 'text'
        ]);

        // Tablet Video
        woocommerce_wp_text_input([
            'id' => '_wcfv_tablet_video',
            'label' => __('Tablet Video URL', 'woocommerce-fullscreen-video'),
            'desc_tip' => true,
            'description' => __('Enter video URL or select from media library for tablet devices', 'woocommerce-fullscreen-video'),
            'placeholder' => 'https://example.com/video-tablet.mp4',
            'type' => 'text'
        ]);

        // Mobile Video
        woocommerce_wp_text_input([
            'id' => '_wcfv_mobile_video',
            'label' => __('Mobile Video URL', 'woocommerce-fullscreen-video'),
            'desc_tip' => true,
            'description' => __('Enter video URL or select from media library for mobile devices', 'woocommerce-fullscreen-video'),
            'placeholder' => 'https://example.com/video-mobile.mp4',
            'type' => 'text'
        ]);

        // Placeholder Image
        woocommerce_wp_text_input([
            'id' => '_wcfv_video_placeholder',
            'label' => __('Video Placeholder Image', 'woocommerce-fullscreen-video'),
            'desc_tip' => true,
            'description' => __('Image displayed while video is loading', 'woocommerce-fullscreen-video'),
            'placeholder' => 'https://example.com/placeholder.jpg',
            'type' => 'text'
        ]);

        echo '</div>';

        // Add media buttons
        $this->add_media_buttons();
    }

    // Add media library buttons
    private function add_media_buttons() {
        echo '<div class="options_group" style="padding: 10px;">';
        echo '<p><strong>' . __('Select from Media Library:', 'woocommerce-fullscreen-video') . '</strong></p>';

        echo '<button type="button" class="button wcfv-media-button" data-target="#_wcfv_desktop_video">' . __('Select Desktop Video', 'woocommerce-fullscreen-video') . '</button>';
        echo '<button type="button" class="button wcfv-media-button" data-target="#_wcfv_tablet_video">' . __('Select Tablet Video', 'woocommerce-fullscreen-video') . '</button>';
        echo '<button type="button" class="button wcfv-media-button" data-target="#_wcfv_mobile_video">' . __('Select Mobile Video', 'woocommerce-fullscreen-video') . '</button>';
        echo '<button type="button" class="button wcfv-media-button" data-target="#_wcfv_video_placeholder">' . __('Select Placeholder Image', 'woocommerce-fullscreen-video') . '</button>';

        echo '</div>';

        // Media uploader script
        $this->media_uploader_script();
    }

    // Media uploader script
    private function media_uploader_script() {
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('.wcfv-media-button').click(function(e) {
                    e.preventDefault();
                    var target = $(this).data('target');
                    var mediaUploader;

                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }

                    mediaUploader = wp.media({
                        title: 'Select Media',
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false
                    });

                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $(target).val(attachment.url);
                    });

                    mediaUploader.open();
                });
            });
        </script>
        <?php
    }

    // Save video fields
    public function save_video_fields($post_id) {
        $fields = [
            '_wcfv_desktop_video',
            '_wcfv_tablet_video',
            '_wcfv_mobile_video',
            '_wcfv_video_placeholder'
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    // Enqueue frontend assets
    public function enqueue_assets() {
        if (is_product()) {
            wp_enqueue_style(
                'wcfv-style',
                WCFV_PLUGIN_URL . 'assets/css/style.css',
                [],
                WCFV_VERSION
            );

            wp_enqueue_script(
                'wcfv-script',
                WCFV_PLUGIN_URL . 'assets/js/script.js',
                ['jquery'],
                WCFV_VERSION,
                true
            );
        }
    }

    // Display video container
    public function display_video_container() {
        global $post;

        $desktop_video = get_post_meta($post->ID, '_wcfv_desktop_video', true);
        $tablet_video = get_post_meta($post->ID, '_wcfv_tablet_video', true);
        $mobile_video = get_post_meta($post->ID, '_wcfv_mobile_video', true);
        $placeholder = get_post_meta($post->ID, '_wcfv_video_placeholder', true);

        if ($desktop_video || $tablet_video || $mobile_video) {
            include WCFV_PLUGIN_PATH . 'templates/video-display.php';
        }
    }
}