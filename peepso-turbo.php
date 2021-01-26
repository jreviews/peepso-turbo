<?php
/* Plugin Name: PeepSo Turbo
Plugin URI: https://www.jreviews.com
Description: Make PeepSo fly for search engines and guests by disabling unnecessary js/css
Version: 1.1.2
Author: ClickFWD
Author URI: https://www.jreviews.com/
*/

defined('ABSPATH') or die;

/**
 * For guests or when uring the ?turbo=1 URL query string parameter
 */
function should_enable_peepso_turbo()
{
    return !is_user_logged_in() || ($_REQUEST['peepso_turbo'] ?? null);
}

add_action('wp_enqueue_scripts', 'peepso_turbo_dequeue_javascript', 100000);
add_action('wp_enqueue_scripts', 'peepso_turbo_dequeue_css', 100000);

function peepso_turbo_is_stream_page() {
    $post = get_post();

    if ($post && strpos($post->post_content, '[peepso_activity]') !== false) {
        return true;
    }

    return false;
}

function peepso_turbo_is_remove_everything_page() {
    $post = get_post();

    if (! $post) {
        return false;
    }
  
    // Remove all CSS/JS for pages that include specific shortcodes    

    if (strpos($post->post_content, '[some_shortcode]') !== false) {
        return true;
    }

    // Remove all CSS/JS for specific pages by slug

    if (in_array($post->post_name,['privacy-policy'])) {
        return true;
    }
    
    return false;
}

function peepso_turbo_dequeue_javascript()
{
    if (! should_enable_peepso_turbo()) {
        return;
    }

    if (peepso_turbo_is_stream_page()) {
        $scripts = peepso_turbo_get_exclude_scripts_stream();
    } elseif (peepso_turbo_is_remove_everything_page()) {
        $scripts = peepso_turbo_get_exclude_scripts_everything();
    } else {
        $scripts = peepso_turbo_get_exclude_scripts_non_stream();      
    }

     # Gecko theme-specific
    // 'gecko-macy-js',
    // 'gecko-sticky-js',
    // 'gecko-js',
    
    foreach ($scripts as $handle) {
        wp_deregister_script($handle);
    }
}
    
function peepso_turbo_dequeue_css()
{
    # Remove stuff that's only needed for guests
    if (is_user_logged_in()) {
        // Social login CSS
        wp_deregister_style('twst-edd-social-login');
    }
    
    if (! should_enable_peepso_turbo()) {
        return;
    }

    wp_deregister_style('peepso-markdown');
    wp_deregister_style('peepso-blogposts-dynamic');
    wp_deregister_style('peepso-backend');
    wp_deregister_style('peepso-moods');
    wp_deregister_style('peepso-giphy');
    wp_deregister_style('peepso-fileupload');
    
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('peepso-fileupload');

    // Unload PeepSo Icons because they are also loaded by Gecko
    wp_dequeue_style('peepso-icons-new');
    // wp_dequeue_style('gecko-icons-css');

    if (peepso_turbo_is_stream_page() || peepso_turbo_is_remove_everything_page()) {
        wp_dequeue_style('peepso-datepicker');
    }

    # Used in privacy page for the image dimensions
    // wp_dequeue_style('wp-block-library');
    
    # reaction icons - comments
    // wp_deregister_style('peepsoreactions-dynamic');
}

function peepso_turbo_get_exclude_scripts_stream() {
    return [
        # Needed for core PeepSo functionality
        // 'underscore',
        // 'peepso',
        // 'peepso-bundle',
        // 'peepso-window',
        'wp-embed',
        'backbone',
        'wp-polyfill',
        'wp-api',
        'peepso-chat',
        'peepso-member',
        'peepso-notification',
        'peepso-time',
        'peepso-hashtags',
        'peepso-blogposts',
        'peepso-markdown',
        'jquery-widgetopts',
        'peepsotags',
        'peepso-moods',
        'peepso-giphy',
        'peepsopolls',
        'peepso-friends',
        'friendso',
        'peepso-groups',
        'peepso-resize',
        'peepso-postbox',
        'peepso-posttabs',
        'peepso-groups-group',
        
        # Needed for comments lightbox and share functionality. They force peepso-bundle to load.
        // 'peepso-activity',
        // 'peepso-crop',
        // 'peepso-fileupload',
        // 'peepso-modal-comments',
        
        # Needed in stream pages, can remove in non-stream
        // 'peepsoreactions', # needed for Login Modal in stream
        'peepsolocation-js',
        // 'peepso-vip',
        // 'peepso-photos-grid',
        // 'peepso-photos',
        // 'peepsovideos',
    ];
}

function peepso_turbo_get_exclude_scripts_non_stream() {
    return [
        # Needed for core PeepSo functionality
        // 'underscore',
        // 'peepso',
        // 'peepso-bundle',
        // 'peepso-window',
        'wp-embed',
        'backbone',
        // 'wp-polyfill', If removed, prevents many jQuery UI plugins from loading breaking JReviews
        'wp-api',
        'peepso-chat',
        'peepso-member',
        'peepso-notification',
        'peepso-time',
        'peepso-hashtags',
        'peepso-blogposts',
        'peepso-markdown',
        'jquery-widgetopts',
        'peepsotags',
        'peepso-moods',
        'peepso-giphy',
        'peepsopolls',
        'peepso-friends',
        'friendso',
        'peepso-groups',
        'peepso-resize',
        'peepso-postbox',
        'peepso-posttabs',
        'peepso-groups-group',
        
        # Needed for stream, comments lightbox and share functionality. They force peepso-bundle to load.
        // 'peepso-activity',
        // 'peepso-crop',
        // 'peepso-fileupload',
        // 'peepso-modal-comments',
        
        # Needed in stream pages, can remove in non-stream
        // 'peepsoreactions', # needed for Login Modal in stream
        // 'peepsolocation-js',
        'peepso-vip',
        'peepso-photos-grid',
        'peepso-photos',
        'peepsovideos',
    ];     
}

function peepso_turbo_get_exclude_scripts_everything() {
    return [
        # Needed for core PeepSo functionality
        'underscore',
        'peepso',
        'peepso-bundle',
        'peepso-window',
        'wp-embed',
        'backbone',
        'wp-polyfill',
        'wp-api',
        'peepso-chat',
        'peepso-member',
        'peepso-notification',
        'peepso-time',
        'peepso-hashtags',
        'peepso-blogposts',
        'peepso-markdown',
        'jquery-widgetopts',
        'peepsotags',
        'peepso-moods',
        'peepso-giphy',
        'peepsopolls',
        'peepso-friends',
        'friendso',
        'peepso-groups',
        'peepso-resize',
        'peepso-postbox',
        'peepso-posttabs',
        'peepso-groups-group',
        
        # Needed for stream, comments lightbox and share functionality. They force peepso-bundle to load.
        'peepso-activity',
        'peepso-crop',
        'peepso-fileupload',
        'peepso-modal-comments',
        
        # Needed in stream pages, can remove in non-stream
        'peepsoreactions', # needed for Login Modal in stream
        'peepsolocation-js',
        'peepso-vip',
        'peepso-photos-grid',
        'peepso-photos',
        'peepsovideos',
    ];     
}
