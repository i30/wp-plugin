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
function sc_mm4ep_get_item_settings($item_id)
{
    $elementor_item_id = get_post_meta($item_id, 'mm4ep_elementor_menu_item_id', true);

    return array_merge([
        'icon' => [],
        'is_mega' => 0,
        'viewers' => ['anyone'],
        'show_badge' => 0,
        'hide_title' => 0,
        'badge_label' => esc_html__('New', 'textdomain'),
        'badge_bg_color' => '#D30C5C',
        'hide_on_mobile' => 0,
        'hide_on_tablet' => 0,
        'badge_bd_radius' => [],
        'hide_on_desktop' => 0,
        'mega_panel_fit' => 'container',
        'mega_fit_to_el' => '',
        'mega_panel_width' => ['unit' => '%', 'size' => 100],
        'badge_label_color' => '#FFFFFF',
        'mega_panel_stretch' => '',
    ], (array)get_post_meta($elementor_item_id, '_elementor_page_settings', true));
}

/**
 * @return array
 */
function sc_mm4ep_parse_nav_menu_settings(array $settings)
{
    return array_merge([
        'layout' => 'horizontal',
        'toggle' => 'burger',
        'pointer' => 'underline',
        'dropdown' => 'tablet',
        'indicator' => 'classic',
        'full_width' => '',
        'text_align' => 'aside',
        'align_items' => 'left',
        'toggle_align' => 'center',
        'animation_text' => 'grow',
        'animation_line' => 'fade',
        'animation_framed' => 'fade',
        'animation_background' => 'fade',
    ], $settings);
}
