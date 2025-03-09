<?php
/**
 * Plugin Name: WordPress Post Scheduler
 * Plugin URI: https://github.com/hostragons
 * Description: Reschedules WordPress posts to publish at regular intervals (two per hour)
 * Version: 1.0.0
 * Author: Hostragons Global Limited
 * Author URI: https://www.hostragons.com
 * License: MIT
 * Text Domain: wp-post-scheduler
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Reschedule all posts to two per hour
function reschedule_all_posts_two_per_hour() {
    // Get scheduled posts
    $args = array(
        'post_status' => 'future',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC'
    );
    
    $scheduled_posts = get_posts($args);
    
    if (empty($scheduled_posts)) {
        return "No scheduled posts found.";
    }
    
    // Set start time (now + 1 hour)
    $timezone = new DateTimeZone('Europe/London'); // London timezone
    $start_time = new DateTime('now', $timezone);
    $start_time->modify('+1 hour');
    $start_time->setTime($start_time->format('H'), 0, 0); // Set to the beginning of the hour
    
    $total_posts = count($scheduled_posts);
    $updated_count = 0;
    
    // For each post
    foreach ($scheduled_posts as $index => $post) {
        // Schedule one post every 30 minutes (2 posts per hour)
        $minutes = ($index % 2) * 30; // 0 or 30 minutes
        $hours_to_add = floor($index / 2); // How many hours to add
        
        $post_time = clone $start_time;
        $post_time->modify("+{$hours_to_add} hours");
        $post_time->modify("+{$minutes} minutes");
        
        // Update the post
        $update_post = array(
            'ID' => $post->ID,
            'post_date' => $post_time->format('Y-m-d H:i:s'),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', $post_time->getTimestamp())
        );
        
        wp_update_post($update_post);
        $updated_count++;
    }
    
    return "Total of {$total_posts} posts have been rescheduled. First post starts at: " . 
           $start_time->format('Y-m-d H:i:s') . " (London time) with two posts per hour.";
}

// Add menu to admin panel
function add_reschedule_menu() {
    add_management_page(
        'Reschedule Posts',
        'Reschedule Posts',
        'manage_options',
        'reschedule-posts',
        'reschedule_posts_page'
    );
}
add_action('admin_menu', 'add_reschedule_menu');

// Admin page
function reschedule_posts_page() {
    $message = '';
    
    if (isset($_POST['reschedule_posts']) && check_admin_referer('reschedule_posts_action')) {
        $message = reschedule_all_posts_two_per_hour();
    }
    
    echo '<div class="wrap">';
    echo '<h1>Reschedule Posts</h1>';
    
    if (!empty($message)) {
        echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
    }
    
    echo '<form method="post">';
    wp_nonce_field('reschedule_posts_action');
    echo '<p>This operation will reschedule all your future posts to publish at a rate of two per hour.</p>';
    echo '<p>All times will be set according to London (GMT) timezone.</p>';
    echo '<p><strong>CAUTION:</strong> We recommend backing up your site before performing this operation.</p>';
    echo '<p><input type="submit" name="reschedule_posts" class="button button-primary" value="Reschedule Posts"></p>';
    echo '</form>';
    echo '</div>';
}
