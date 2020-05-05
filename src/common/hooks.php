<?php
/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

/**
 * Apply mega menu walker
 */
function sc_mm4ep_walker_nav_menu_args(array $args)
{
    if (empty($args['menu'])) {
        return $args;
    }

    if (is_numeric($args['menu'])) {
        $menu_id = intval($args['menu']);
        $menu_obj = get_term($menu_id, 'nav_menu');
        $menu_slug = $menu_obj->slug;
    } else {
        $menu_obj = get_term_by('slug', $args['menu'], 'nav_menu');
        $menu_id = $menu_obj->term_id;
        $menu_slug = $args['menu'];
    }

    $menu_meta = get_term_meta($menu_id, 'sc_mm4ep_settings', true);

    if (empty($menu_meta['is_elementor'])) {
        return $args;
    }

    $stylesheet = get_option('stylesheet');
    $menu_settings = get_option($stylesheet . '_mod_sc_mm4ep_' . $menu_obj->slug, []);

    $menu_settings['is_elementor'] = 1;
    $menu_settings['schema_markup'] = empty($menu_meta['schema_markup']) ? 0 : 1;

    $menu_settings = sc_mm4ep_parse_nav_menu_settings($menu_settings);

    if ($menu_settings['schema_markup'] && !isset($GLOBALS['navmenu_schema_markup_added'])) {
        $args['items_wrap'] = '<ul id="%1$s" class="%2$s" itemscope itemtype="https://schema.org/SiteNavigationElement">%3$s</ul>';
        $args['schema_markup_added'] = 1;
    }

    $args['walker'] = new SC\MM4EP\MegaMenuWalker($menu_settings);

    return $args;
}
add_filter('wp_nav_menu_args', 'sc_mm4ep_walker_nav_menu_args', PHP_INT_MAX);

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
