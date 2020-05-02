<?php
$elementor = Elementor\Plugin::$instance;
$menu_id = get_post_meta($post->ID, 'mm4ep_menu_id', true);
$item_id = get_post_meta($post->ID, 'mm4ep_menu_item_id', true);
$document = $elementor->documents->get_doc_for_frontend($post->ID);
$menu_obj = get_term($menu_id, 'nav_menu');
$stylesheet = get_option('stylesheet');
$item_settings = get_post_meta($post->ID, '_elementor_page_settings', true);
$menu_settings = get_option($stylesheet . '_mod_sc_mm4ep_' . $menu_obj->slug, []);
$menu_settings = sc_mm4ep_parse_nav_menu_settings($menu_settings);
$elementor->frontend->add_body_class('elementor-template-full-width');

$args = [
	'echo' => true,
	'menu' => $menu_id,
	'menu_class' => 'elementor-nav-menu',
	'menu_id' => 'menu-1-' . $post->ID,
	'fallback_cb' => false,
	'container' => '',
];

$data_settings = json_encode([
    'layout' => $menu_settings['layout'],
    'toggle' => $menu_settings['toggle']
]);

if ('vertical' === $menu_settings['layout']) {
	$args['menu_class'] .= ' sm-vertical';
}

// Add custom filter to handle Nav Menu HTML output.
add_filter('nav_menu_item_id', '__return_empty_string');
add_filter('nav_menu_submenu_css_class', 'sc_handle_sub_menu_classes');
add_filter('nav_menu_link_attributes', 'sc_handle_link_classes', 10, 4);

$menu_toggle_html_class = 'elementor-menu-toggle';
$menu_container_html_class = 'elementor-nav-menu--main elementor-nav-menu__container';
$menu_wrapper_html_class = 'elementor-element elementor-element-' . $menu_settings['el_id'] . ' elementor-nav-menu--indicator-' . $menu_settings['indicator'];

if ($elementor->editor->is_edit_mode()) {
    $menu_toggle_html_class .= ' elementor-clickable';
}

if ('dropdown' !== $menu_settings['layout']) {
    $menu_wrapper_html_class .= ' elementor-nav-menu__align-' . $menu_settings['align_items']
    . ' elementor-nav-menu--dropdown-' . $menu_settings['dropdown']
    . ' elementor-nav-menu--toggle elementor-nav-menu--' . $menu_settings['toggle'];
}

if ('none' !== $menu_settings['dropdown']) {
    $menu_wrapper_html_class .= ' elementor-nav-menu__text-align-' . $menu_settings['text_align'];
}

?>
<!DOCTYPE html>
<html lang="en_US">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('elementor-' . $menu_settings['post_id']); ?>>
<div id="menu-scope" class="<?php echo esc_attr($menu_wrapper_html_class) ?> elementor-widget elementor-widget-nav-menu" data-id="<?php echo esc_attr($menu_settings['el_id']) ?>" data-settings='<?php echo esc_js($data_settings) ?>' data-element_type="widget" data-widget_type="nav-menu.default">
<div class="elementor-widget-container">
<?php
	if ('dropdown' !== $menu_settings['layout']) :
	$menu_container_html_class .= ' elementor-nav-menu--layout-' . $menu_settings['layout'];
	if ($menu_settings['pointer']) {
		$menu_container_html_class .= ' e--pointer-' . $menu_settings['pointer'];
		foreach ($menu_settings as $key => $value) {
			if (0 === strpos($key, 'animation') && $value) {
				$menu_container_html_class .= ' e--animation-' . $value;
				break;
            }
        }
    }
    ?>
    <nav class="<?php echo esc_attr($menu_container_html_class) ?>">
        <?php wp_nav_menu($args); ?>
    </nav>
    <?php
	endif;
	?>
	<div role="button" tabindex="0" aria-label="<?php esc_attr_e('Menu Toggle', 'textdomain') ?>" aria-expanded="false" class="<?php echo esc_attr($menu_toggle_html_class) ?>">
		<i class="eicon-menu-bar" aria-hidden="true"></i>
		<span class="elementor-screen-only"><?php esc_html_e('Menu', 'textdomain'); ?></span>
	</div>
	<nav class="elementor-nav-menu--dropdown elementor-nav-menu__container" role="navigation" aria-hidden="true">
        <?php $args['menu_id'] = 'menu-2-' . $post->ID; wp_nav_menu($args); ?>
    </nav>
</div>
</div>
<?php

$elementor->modules_manager->get_modules('page-templates')->print_content();

wp_footer();

?>
<script type="text/javascript">
    jQuery(() => elementorFrontend.elementsHandler.runReadyTrigger(jQuery("#menu-scope")[0]))
</script>
</body>
</html>
