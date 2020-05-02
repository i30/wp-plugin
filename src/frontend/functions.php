<?php
/**
 * Frontend functions
 */

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
        // 'full_width' =>
        'text_align' => 'aside',
        'toggle' => 'burger',
        'toggle_align' => 'center',
    ], $settings);
}
