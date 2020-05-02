<?php
/**
 * Mixin functions
 */

/**
 * @return array
 */
function sc_mm4ep_get_items_settings($menu_id)
{
    $items = wp_get_nav_menu_items($menu_id, [
        'no_found_rows' => true,
        'suppress_filters' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false
    ]);

    $menu_items = [];

    if ($items) {
        foreach ($items as $item) {
            $elementor_item_id = get_post_meta($item->ID, 'mm4ep_elementor_menu_item_id', true);
            $menu_items[$item->ID] = array_merge([
                'icon' => '',
                'is_mega' => 0,
                'hide_title' => 0,
                'viewers' => ['anyone'],
                'disable_link' => 0,
                'hide_on_mobile' => 0,
                'hide_on_desktop' => 0,
                'hide_sub_on_mobile' => 0,
                'show_badge' => 0,
                'badge_label' => esc_html__('New', 'textdomain'),
                'badge_label_color' => '#fff',
                'badge_bg_color' => '#2ed164',
                'badge_bd_radius' => [],
                'mega_panel_width' => ['unit' => '%', 'size' => 100]
            ], (array)get_post_meta($elementor_item_id, '_elementor_page_settings', true));
        }
    }

    return $menu_items;
}
