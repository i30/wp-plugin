<?php
/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
 
/**
 * Enqueue assets
 */
function sc_mm4ep_enqueue_frontend_assets()
{
    wp_enqueue_style('mm4ep-frontend', ELEMENTOR_PRO_MEGAMENU_URI . 'assets/css/frontend.min.css', ['elementor-pro'], ELEMENTOR_PRO_MEGAMENU_VER);

    // wp_enqueue_script('mm4ep-frontend', ELEMENTOR_PRO_MEGAMENU_URI . 'assets/js/frontend.min.js', ['elementor-pro-frontend'], ELEMENTOR_PRO_MEGAMENU_VER, true);
}
add_action('wp_enqueue_scripts', 'sc_mm4ep_enqueue_frontend_assets', 10, 0);

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


/**
 * Since Elementor Pro renders a menu twice,
 * ensure we add schema markups once per page.
 */
function sc_mm4ep_ensure_one_site_nav_element($menu, $args)
{
    if (isset($args->schema_markup_added)) {
        $GLOBALS['navmenu_schema_markup_added'] = 1;
    }

    return $menu;
}
add_filter('wp_nav_menu', 'sc_mm4ep_ensure_one_site_nav_element', PHP_INT_MAX, 2);
