<?php
/**
 * Plugin Name: Mega Menu for Elementor Pro
 * Description: An Elementor Pro addon that helps you add mega menus, menu icons, menu badges and user restriction to navigation menus.
 * Author: SarahCoding
 * Author URI: https://sarahcoding.com
 * Version: 1.0.0
 * Text Domain: textdomain
 * Requires PHP: 5.6
 * Requires at least: 5.2
 * Tested up to: 5.4
 */

/**
 * Pre-activation check
 */
function sc_elementor_mega_menu_pre_activation()
{
    if (version_compare(PHP_VERSION, '5.6', '<')) {
        throw new Exception(__('This plugin requires PHP version 5.6 at least!', 'textdomain'));
    }

    if (version_compare($GLOBALS['wp_version'], '5.2', '<')) {
        throw new Exception(__('This plugin requires WordPress version 5.2 at least!', 'textdomain'));
    }
}

/**
 * Do activation
 *
 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/
 */
function sc_elementor_mega_menu_activate($network)
{
    try {
        sc_elementor_mega_menu_pre_activation();
    } catch (Exception $e) {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            header('Content-Type: application/json; charset=' . get_option('blog_charset'));
            status_header(500);
            exit(json_encode([
                'success' => false,
                'name'    => __('Plugin Activation Error', 'textdomain'),
                'message' => $e->getMessage()
            ]));
        } else {
            exit($e->getMessage());
        }
    }
}
register_activation_hook(__FILE__, 'sc_elementor_mega_menu_activate');

/**
 * Do installation
 *
 * @see https://developer.wordpress.org/reference/hooks/plugins_loaded/
 */
function sc_elementor_mega_menu_install()
{
    define('ELEMENTOR_PRO_MEGAMENU_DIR', __DIR__ . '/');
    define('ELEMENTOR_PRO_MEGAMENU_URI', plugins_url('/', __FILE__));
    define('ELEMENTOR_PRO_MEGAMENU_VER', '1.0.0');

    load_plugin_textdomain('textdomain', false, basename(__DIR__) . '/languages');

    require __DIR__ . '/src/common/functions.php';
    require __DIR__ . '/src/common/hooks.php';

    if (is_admin()) {
        require __DIR__ . '/src/backend/functions.php';
        require __DIR__ . '/src/backend/hooks.php';
    } else {
        require __DIR__ . '/src/frontend/hooks.php';
        require __DIR__ . '/src/frontend/functions.php';
        require __DIR__ . '/src/frontend/class-mega-menu-walker.php';
    }
}
add_action('plugins_loaded', 'sc_elementor_mega_menu_install', 10, 0);
