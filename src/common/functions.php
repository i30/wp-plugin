<?php
/**
 * Common functions
 */
use Elementor\Icons_Manager;
use Elementor\Core\Files\Assets\Svg\Svg_Handler;

/**
 * @return string
 */
function sc_mm4ep_render_menu_item_icon(array $icon)
{
    if (!empty($icon['value'])) {
        if ('svg' === $icon['library']) {
            return Svg_Handler::get_inline_svg($icon['value']);
        } else {
            $types = Icons_Manager::get_icon_manager_tabs();
            if (isset($types[$icon['library']]['render_callback']) && is_callable($types[$icon['library']]['render_callback'])) {
                $atts = ['class' => 'menu-item-icon ' . $icon['value']];
                return call_user_func_array($types[$icon['library']]['render_callback'], [$icon, $atts, 'span']);
            }
            return '<span class="menu-item-icon ' . $icon['value'] . '"></span>';
        }
    }

    return '';
}

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
                'icon' => [],
                'is_mega' => 0,
                'hide_title' => 0,
                'viewers' => ['anyone'],
                'hide_on_mobile' => 0,
                'hide_on_tablet' => 0,
                'hide_on_desktop' => 0,
                'show_badge' => 0,
                'badge_label' => esc_html__('New', 'textdomain'),
                'badge_label_color' => '#FFFFFF',
                'badge_bg_color' => '#D30C5C',
                'badge_bd_radius' => [],
                'mega_panel_width' => ['unit' => '%', 'size' => 100]
            ], (array)get_post_meta($elementor_item_id, '_elementor_page_settings', true));
        }
    }

    return $menu_items;
}

/**
 * @return array
 */
function sc_mm4ep_parse_nav_menu_settings(array $settings)
{
    return array_merge([
        'layout' => 'horizontal',
        'align_items' => 'left',
        'pointer' => 'underline',
        'animation_line' => 'fade',
        'animation_framed' => 'fade',
        'animation_background' => 'fade',
        'animation_text' => 'grow',
        'indicator' => 'classic',
        'dropdown' => 'tablet',
        'full_width' => '',
        'text_align' => 'aside',
        'toggle' => 'burger',
        'toggle_align' => 'center'
    ], $settings);
}
