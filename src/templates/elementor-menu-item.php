<?php
/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

$elementor = Elementor\Plugin::$instance;
$menu_id = get_post_meta($post->ID, 'mm4ep_menu_id', true);
$item_id = get_post_meta($post->ID, 'mm4ep_menu_item_id', true);
$data_id = 'cicada3';
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
    'toggle' => $menu_settings['toggle'],
    'indicator' => $menu_settings['indicator'],
    'full_width' => $menu_settings['full_width']
]);

if ('vertical' === $menu_settings['layout']) {
	$args['menu_class'] .= ' sm-vertical';
}

$body_class = isset($menu_settings['post_id']) ? 'elementor-' . $menu_settings['post_id'] : '';
$container_class = 'elementor-nav-menu--main elementor-nav-menu__container';
$wrapper_class = 'elementor-element elementor-nav-menu--indicator-' . $menu_settings['indicator'];

if (isset($menu_settings['el_id'])) {
    $data_id = $menu_settings['el_id'];
    $args['menu_id'] = 'menu-1-' . $menu_settings['el_id'];
    $wrapper_class .= ' elementor-element-' . $menu_settings['el_id'];
}

if ('dropdown' !== $menu_settings['layout']) {
    $wrapper_class .= ' elementor-nav-menu__align-' . $menu_settings['align_items']
    . ' elementor-nav-menu--dropdown-' . $menu_settings['dropdown']
    . ' elementor-nav-menu--toggle elementor-nav-menu--' . $menu_settings['toggle'];
}

if ('none' !== $menu_settings['dropdown']) {
    $wrapper_class .= ' elementor-nav-menu__text-align-' . $menu_settings['text_align'];
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php wp_head(); ?>
    <style media="screen">
        body {
            background-color: #495157;
            padding: 2em;
        }
        .elementor-menu-toggle {
            color: #FFFFFF;
        }
        #menu-scope, #content-scope {
            box-sizing: border-box;
            border: 2px dashed #d5dadf;
        }
        #content-scope {
            margin-top: 10px;
            margin-right: auto;
            margin-bottom: 0;
            margin-left: auto;
        }
    </style>
</head>
<body <?php body_class($body_class); ?>>
<div id="preview-scope">
    <div id="menu-scope" class="<?php echo esc_attr($wrapper_class) ?> elementor-widget elementor-widget-nav-menu" data-id="<?php echo esc_attr($data_id) ?>" data-settings='<?php echo esc_js($data_settings) ?>' data-element_type="widget" data-widget_type="nav-menu.default">
        <div class="elementor-widget-container">
        <?php
        	if ('dropdown' !== $menu_settings['layout']) :
        	$container_class .= ' elementor-nav-menu--layout-' . $menu_settings['layout'];
        	if ($menu_settings['pointer']) {
        		$container_class .= ' e--pointer-' . $menu_settings['pointer'];
        		foreach ($menu_settings as $key => $value) {
        			if (0 === strpos($key, 'animation') && $value) {
        				$container_class .= ' e--animation-' . $value;
                        break;
                    }
                }
            }
            ?>
            <nav class="<?php echo esc_attr($container_class) ?>">
                <?php wp_nav_menu($args); ?>
            </nav>
            <?php
        	endif;
        	?>
        	<div role="button" tabindex="0" aria-label="<?php esc_attr_e('Menu Toggle', 'textdomain') ?>" aria-expanded="false" class="elementor-menu-toggle  elementor-clickable">
        		<i class="eicon-menu-bar" aria-hidden="true"></i>
        		<span class="elementor-screen-only"><?php esc_html_e('Menu', 'textdomain'); ?></span>
        	</div>
        	<nav class="elementor-nav-menu--dropdown elementor-nav-menu__container" role="navigation" aria-hidden="true">
            <?php
                $args['menu_id'] = 'menu-2-' . $post->ID;
                if (isset($menu_settings['el_id'])) {
                    $args['menu_id'] = 'menu-2-' . $menu_settings['el_id'];
                }
                wp_nav_menu($args);
            ?>
            </nav>
        </div>
    </div>
    <div id="content-scope">
    <?php $elementor->modules_manager->get_modules('page-templates')->print_content(); ?>
    </div>
</div>
<?php wp_footer(); ?>
<script type="text/javascript">
    jQuery(() => elementorFrontend.elementsHandler.runReadyTrigger(jQuery("#menu-scope")[0]))
</script>
</body>
</html>
