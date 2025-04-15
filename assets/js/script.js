jQuery(document).ready(function($) {
    // Initialize video container
    function initVideo() {
        $('.wcfv-video-container').addClass('active');
        $('.container-wrap').addClass('place-on-top');
        // Get appropriate video source based on screen size
        const video = $('.wcfv-product-video')[0];
        const sources = video.querySelectorAll('source');
        const placeholder = $('.wcfv-video-placeholder');

        // Find the correct source for current screen size
        let selectedSource = null;
        sources.forEach(source => {
            if (window.matchMedia(source.media).matches) {
                selectedSource = source;
            }
        });

        if (selectedSource && selectedSource.dataset.src) {
            // Set the video source
            video.src = selectedSource.dataset.src;

            // When video can play, hide placeholder and show video
            video.oncanplay = function() {
                placeholder.hide();
                video.style.display = 'block';
                video.play().catch(e => console.log('Autoplay prevented:', e));
            };

            video.load();
        } else {
            // No suitable video found, keep showing placeholder
            console.log('No suitable video source found for this device');
        }
    }

    const viewVideoBtn = $('.wcfv-view-video-btn');
    // Show video when button is clicked
    viewVideoBtn.on('click', function() {
        initVideo();
    });

    // Close video
    $('.wcfv-video-close').on('click', function() {
        const video = $('.wcfv-product-video')[0];
        if (video) {
            video.pause();
        }
        $('.container-wrap').removeClass('place-on-top');
        $('.wcfv-video-container').removeClass('active');
    });

    $('.wcfv-video-container').on('click', function() {
        const video = $('.wcfv-product-video')[0];
        if (video) {
            video.pause();
        }
        $('.container-wrap').removeClass('place-on-top');
        $('.wcfv-video-container').removeClass('active');
    });

    // Initialize after page load
    setTimeout(initVideo, 500);

    // Handle window resize
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const video = $('.wcfv-product-video')[0];
            if (video) {
                video.pause();
                $('.wcfv-video-placeholder').show();
                video.style.display = 'none';
                initVideo();
            }
        }, 250);
    });
});