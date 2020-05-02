<?php
/**
 * Frontend hooks
 */

/**
 * Include elementor-menu-item template
 */
function sc_mm4ep_include_template($tmpl)
{
    if (is_singular('elementor_menu_item')) {
        $tmpl = ELEMENTOR_PRO_MEGAMENU_DIR . 'src/templates/elementor-menu-item.php';
    }

    return $tmpl;
}
add_action('template_include', 'sc_mm4ep_include_template', PHP_INT_MAX);

function sc_handle_link_classes($atts, $item, $args, $depth)
{
    $classes = $depth ? 'elementor-sub-item' : 'elementor-item';
    $is_anchor = false !== strpos($atts['href'], '#');

    if (!$is_anchor && in_array('current-menu-item', $item->classes)) {
        $classes .= ' elementor-item-active';
    }

    if ($is_anchor) {
        $classes .= ' elementor-item-anchor';
    }

    if (empty($atts['class'])) {
        $atts['class'] = $classes;
    } else {
        $atts['class'] .= ' ' . $classes;
    }

    return $atts;
}

function sc_handle_sub_menu_classes($classes)
{
	$classes[] = 'elementor-nav-menu--dropdown';

	return $classes;
}

// add_action('wp_head', function()
// {
//     $meta = json_decode(get_metadata('post', 8, '_elementor_data', true));
//
//     function get_nav_menu_settings(array $elements)
//     {
//         static $nav_menus = [];
//
//         foreach ($elements as $el) {
//             if (!empty($el->elements)) {
//                 get_nav_menu_settings($el->elements);
//             }
//             if ($el->elType === 'widget' && $el->widgetType === 'nav-menu') {
//                 if (!empty($el->settings->menu)) {
//                     $nav_menus[$el->settings->menu] = $el->settings;
//                 }
//             }
//         }
//
//         return $nav_menus;
//     }
//
//     echo "<pre>";
//     var_dump(get_nav_menu_settings(get_option('abcxyz_8')));
//     exit;
// }, 10, 0);
