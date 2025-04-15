<div class="wcfv-video-container">
    <div class="wcfv-video-wrapper">
        <img src="<?php echo esc_url($placeholder ?: WCFV_PLUGIN_URL . 'assets/images/video-placeholder.jpg'); ?>"
             alt="<?php esc_attr_e('Video loading...', 'woocommerce-fullscreen-video'); ?>"
             class="wcfv-video-placeholder">

        <video class="wcfv-product-video" playsinline muted loop>
            <source data-src="<?php echo esc_url($desktop_video); ?>" media="(min-width: 1025px)" type="video/mp4">
            <source data-src="<?php echo esc_url($tablet_video); ?>" media="(min-width: 768px) and (max-width: 1024px)" type="video/mp4">
            <source data-src="<?php echo esc_url($mobile_video); ?>" media="(max-width: 767px)" type="video/mp4">
        </video>

        <button class="wcfv-video-close">&times;</button>
    </div>
</div>

<button class="wcfv-view-video-btn floating-btn">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24px" height="24px">
        <path d="M0 0h24v24H0z" fill="none"/>
        <path d="M8 5v14l11-7z"/>
    </svg>
    <?php _e('Watch Featured Video', 'woocommerce-fullscreen-video'); ?>
</button>