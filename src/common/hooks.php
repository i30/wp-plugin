<?php
/**
 * Common hooks
 */

/**
 * Register elementor_menu_item post type
 */
function sc_mm4ep_register_post_type()
{
    register_post_type('elementor_menu_item', [
        'labels' => [
            'name' => esc_html__('Elementor Menu Items', 'textdomain'),
            'singular_name' => esc_html__('Elementor Menu Item', 'textdomain'),
            'all_items' => esc_html__('All Elementor Menu Items', 'textdomain')
        ],
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'exclude_from_search' => true,
        'supports' => ['title', 'editor']
    ]);
}
add_action('init', 'sc_mm4ep_register_post_type', 10, 0);

/**
 * Add Elementor support
 */
function sc_mm4ep_add_elementor_support($supports)
{
    if (empty($supports)) $supports = [];

    if (!isset($supports['elementor_menu_item'])) {
        $supports[] = 'elementor_menu_item';
    }

    return $supports;
}
add_filter('option_elementor_cpt_support', 'sc_mm4ep_add_elementor_support');            add_filter('default_option_elementor_cpt_support', 'sc_mm4ep_add_elementor_support');
