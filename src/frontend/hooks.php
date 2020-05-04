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
