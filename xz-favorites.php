<?php
/*
 * Plugin Name: Мой первый xz плагин
 * Description: some plugin for favorite posts
 */

require __DIR__ . '/functions.php';
require __DIR__ . '/XZ_Favorites_Widget.php';

add_filter('the_content', 'xz_favorites_content');
add_action('wp_enqueue_scripts', 'xz_favorites_scripts');
add_action('wp_ajax_xz_add', 'wp_ajax_xz_add');
add_action('wp_ajax_xz_del', 'wp_ajax_xz_del');
add_action('wp_dashboard_setup', 'xz_favorites_dashboard_widget');
add_action('admin_enqueue_scripts', 'xz_favorites_admin_scripts');
add_action('wp_ajax_xz_del_all', 'wp_ajax_xz_del_all');

add_action('widgets_init', 'xz_favorites_widget');

function xz_favorites_widget() {
    register_widget('XZ_Favorites_Widget');
}