<?php
/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

use Elementor\Scheme_Color;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

/**
 * Register elemetor controls for menu item
 */
function sc_mm4ep_register_elementor_menu_item_controls($doc)
{
    $post = $doc->get_post();

    if ('elementor_menu_item' !== $post->post_type) {
        return;
    }

    $viewers = ['anyone' => esc_html__('Anyone', 'textdomain')];
    $roles = wp_roles()->roles;

    foreach ($roles as $role => $cap) {
        $viewers[$role] = $cap['name'];
    }

    $doc->remove_control('post_title');

    remove_all_actions('elementor/documents/register_controls');

	$doc->start_injection([
		'of' => 'post_status'
	]);

	$doc->add_control(
		'hide_on_mobile',
		[
			'label' => esc_html__('Hide On Mobile', 'textdomain'),
			'type' => Controls_Manager::SWITCHER
		]
	);

	$doc->add_control(
		'hide_on_tablet',
		[
			'label' => esc_html__('Hide On Tablet', 'textdomain'),
			'type' => Controls_Manager::SWITCHER
		]
	);

	$doc->add_control(
		'hide_on_desktop',
		[
			'label' => esc_html__('Hide On Desktop', 'textdomain'),
			'type' => Controls_Manager::SWITCHER
		]
	);

	$doc->add_control(
		'is_mega',
		[
			'label' => esc_html__('Enable Mega Menu', 'textdomain'),
			'type' => Controls_Manager::SWITCHER
		]
	);

	// $doc->add_control(
	// 	'is_popup',
	// 	[
	// 		'label' => esc_html__('Is Popup', 'textdomain'),
    //         'description' => __('Display mega content as a popup.', 'textdomain'),
	// 		'type' => Controls_Manager::SWITCHER,
    //         'condition' => [
    //             'is_mega!' => ''
    //         ]
	// 	]
	// );

	$doc->add_control(
		'mega_panel_fit',
		[
			'label' => esc_html__('Mega Panel Fits To', 'textdomain'),
			'type' => Controls_Manager::SELECT,
            'label_block' => true,
            'default' => 'container',
            'options' => [
                'menu' => __('Root UL element', 'textdomain'),
                'container' => __('Nav menu container', 'textdomain'),
                'viewport' => __('Device screen, viewport', 'textdomain'),
                'auto' => __('Auto, as big as content', 'textdomain'),
                'custom' => __('Custom', 'textdomain')
            ],
            'condition' => [
                'is_mega!' => ''
            ]
		]
	);

	$doc->add_control(
		'mega_fit_to_el',
		[
			'label' => esc_html__('Fit-To Element', 'textdomain'),
            'description' => __('Enter a unique CSS selector of the element you want to sync the width with mega panel. Default is the nav menu container.', 'textdomain'),
			'type' => Controls_Manager::TEXT,
            'placeholder' => 'E.g. #element-123',
            'condition' => [
                'is_mega!' => '',
                'mega_panel_fit' => 'custom'
            ]
		]
	);

	$doc->add_control(
		'mega_panel_width',
		[
			'label' => esc_html__('Mega Panel Width', 'textdomain'),
            'description' => __('In horizontal menus, % width is relative to outer width of the fit-to element. In vertical menus, % width is inapplicable, mega panel will be as wide as content or a fixed px value.', 'textdomain'),
			'type' => Controls_Manager::SLIDER,
			'size_units' => ['%', 'px'],
			'range' => [
				'%' => [
					'min' => 30,
					'max' => 100
				],
                'px' => [
					'min' => 300,
					'max' => 1200
				]
			],
			'default' => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors' => [
				'#content-scope' => 'width:{{SIZE}}{{UNIT}};',
			],
            'condition' => [
                'is_mega!' => '',
                'mega_panel_fit' => 'custom'
            ]
		]
	);

	$doc->add_control(
		'icon',
		[
			'label' => esc_html__('Icon', 'textdomain'),
			'label_block' => true,
			'type' => Controls_Manager::ICONS
		]
	);

	$doc->add_control(
		'show_badge',
		[
			'label' => esc_html__('Show Badge', 'textdomain'),
			'type' => Controls_Manager::SWITCHER
		]
	);

	$doc->add_control(
		'badge_label',
		[
			'label' => esc_html__('Badge Label', 'textdomain'),
			'type' => Controls_Manager::TEXT,
            'default' => esc_html__('New', 'textdomain'),
            'condition' => [
                'show_badge' => 'yes'
            ]
		]
	);

	$doc->add_control(
		'badge_label_color',
		[
			'label' => esc_html__('Badge Label Color', 'textdomain'),
			'type' => Controls_Manager::COLOR,
            'default' => '#FFFFFF',
    		'selectors' => [
    			'.current-menu-item > .menu-item-link > .menu-item-badge' => 'color:{{VALUE}}',
    		],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'badge_bg_color',
		[
			'label' => esc_html__('Badge Background Color', 'textdomain'),
			'type' => Controls_Manager::COLOR,
            'default' => '#D30C5C',
    		'selectors' => [
    			'.current-menu-item > .menu-item-link > .menu-item-badge' => 'background-color:{{VALUE}};',
    		],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'badge_text_size',
		[
			'label' => esc_html__('Badge Font Size', 'textdomain'),
			'type' => Controls_Manager::SLIDER,
			'size_units' => ['px', 'em', 'rem'],
			'range' => [
				'px' => [
					'min' => 8,
					'max' => 100
				],
				'em' => [
					'min' => 1,
					'max' => 10
				],
				'rem' => [
					'min' => 1,
					'max' => 10
				]
			],
			'default' => [
				'unit' => 'px',
				'size' => 12,
			],
			'selectors' => [
				'.current-menu-item > .menu-item-link > .menu-item-badge' => 'font-size:{{SIZE}}{{UNIT}} !important',
			],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'badge_offset_top',
		[
			'label' => esc_html__('Badge Offset Top', 'textdomain'),
			'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
			'range' => [
				'px' => [
					'min' => -200,
					'max' => 200,
					'step' => 1,
				],
				'%' => [
					'min' => -100,
					'max' => 100,
				]
			],
			'default' => [
				'size' => '-3',
                'unit' => 'px'
			],
			'selectors' => [
				'.current-menu-item > .menu-item-link > .menu-item-badge' => 'top:{{SIZE}}{{UNIT}} !important;',
			],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'badge_offset_right',
		[
			'label' => esc_html__('Badge Offset Right', 'textdomain'),
			'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
			'range' => [
				'px' => [
					'min' => -200,
					'max' => 200,
					'step' => 1,
				],
				'%' => [
					'min' => -100,
					'max' => 100,
				]
			],
			'default' => [
				'size' => '0',
                'unit' => 'px'
			],
			'selectors' => [
				'.current-menu-item > .menu-item-link > .menu-item-badge' => 'right:{{SIZE}}{{UNIT}} !important;',
			],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'badge_padding',
		[
			'label' => esc_html__('Badge Padding', 'textdomain'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors' => [
				'.current-menu-item > .menu-item-link > .menu-item-badge' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'badge_border_radius',
		[
			'label' => esc_html__('Badge Border Radius', 'textdomain'),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => ['px'],
			'selectors' => [
				'.current-menu-item > .menu-item-link > .menu-item-badge' => 'border-radius:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
            'condition' => [
                'show_badge' => 'yes',
                'badge_label!' => ''
            ]
		]
	);

	$doc->add_control(
		'viewers',
		[
			'label' => esc_html__('Who Can See This?', 'textdomain'),
            'label_block' => true,
			'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $viewers,
            'default' => 'anyone'
		]
	);

    $doc->end_injection();
}
add_action('elementor/element/wp-post/document_settings/after_section_end', 'sc_mm4ep_register_elementor_menu_item_controls');

/**
 * Ajax render item icon
 */
function sc_mm4ep_ajax_render_menu_item_icon()
{
    if (!current_user_can('edit_posts')) {
        wp_die(-1);
    }

    wp_send_json(sc_mm4ep_render_menu_item_icon($_POST['icon']));
}
add_action('wp_ajax_mm4ep_render_menu_item_icon', 'sc_mm4ep_ajax_render_menu_item_icon');

/**
 * Enqueue editor scripts
 */
function sc_mm4ep_enqueue_editor_scripts()
{
    global $post;

    if ('elementor_menu_item' !== $post->post_type)
        return;

    wp_enqueue_script('menu-item-controls', ELEMENTOR_PRO_MEGAMENU_URI . 'assets/js/menu-item-controls.min.js', ['elementor-editor'], ELEMENTOR_PRO_MEGAMENU_VER, true);

    wp_localize_script('menu-item-controls', 'scMmm4epI18n', [
        'new' => esc_html__('New', 'textdomain'),
        'menuItemSettings' => esc_html__('Menu Item Settings', 'textdomain')
    ]);
}
add_action('elementor/editor/after_enqueue_scripts', 'sc_mm4ep_enqueue_editor_scripts', 10, 0);

/**
 * Enqueue edit stylesheet
 */
function sc_mm4ep_enqueue_edit_css($hook_suffix)
{
    if ($hook_suffix === 'nav-menus.php') {
        wp_enqueue_style('menu-editor', ELEMENTOR_PRO_MEGAMENU_URI . 'assets/css/menu-editor.min.css', ['elementor-icons', 'elementor-admin'], ELEMENTOR_PRO_MEGAMENU_VER);
    }
}
add_action('admin_enqueue_scripts', 'sc_mm4ep_enqueue_edit_css');

/**
 * Print edit scripts
 */
function sc_mm4ep_print_edit_scripts()
{
    global $nav_menu_selected_id;

    $settings = (array)get_term_meta($nav_menu_selected_id, 'sc_mm4ep_settings', true);
    $settings = array_merge([
        'is_elementor' => 0,
        'schema_markup' => 0
    ], $settings);

    require ELEMENTOR_PRO_MEGAMENU_DIR . 'src/templates/menu-settings.php';
}
add_action('admin_footer-nav-menus.php', 'sc_mm4ep_print_edit_scripts', 10, 0);

/**
 * Save menu settings
 */
function sc_mm4ep_save_menu_settings($menu_id)
{
    if (isset($_REQUEST['wp_customize']) && $_REQUEST['wp_customize'] === 'on') {
        return;
    }

    $settings['is_elementor'] = isset($_POST['sc_mm4ep_settings']['is_elementor']) ? intval($_POST['sc_mm4ep_settings']['is_elementor']) : 0;
    $settings['schema_markup'] = isset($_POST['sc_mm4ep_settings']['schema_markup']) ? intval($_POST['sc_mm4ep_settings']['schema_markup']) : 0;

    update_term_meta($menu_id, 'sc_mm4ep_settings', $settings);
}
add_action('wp_update_nav_menu', 'sc_mm4ep_save_menu_settings');

/**
 * Save elementor data
 */
function sc_mm4ep_save_menu_item_indentity()
{
    if (!current_user_can('edit_posts')) return;

    if (empty($_REQUEST['cmm4e-edit-menu-item']) || empty($_REQUEST['mm4ep_menu_item_id']) || empty($_REQUEST['mm4ep_menu_id'])) {
        return;
    }

    $menu_id = intval($_REQUEST['mm4ep_menu_id']);
    $menu_item_id = intval($_REQUEST['mm4ep_menu_item_id']);
    $elementor_item_id = get_post_meta($menu_item_id, 'mm4ep_elementor_menu_item_id', true);

    if (!get_post($elementor_item_id)) {
        $elementor_item_id = wp_insert_post([
            'post_title' => 'Elementor Menu Item #' . $menu_item_id,
            'post_name' => 'elementor-menu-item-' . $menu_item_id,
            'post_status' => 'publish',
            'post_type' => 'elementor_menu_item',
        ]);
        if (!is_int($elementor_item_id)) {
            throw new Exception(esc_html__('Unable to edit the menu item with Elementor.', 'textdomain'));
        } else {
            update_post_meta($elementor_item_id, 'mm4ep_menu_id', $menu_id);
            update_post_meta($elementor_item_id, 'mm4ep_menu_item_id', $menu_item_id);
            update_post_meta($menu_item_id, 'mm4ep_elementor_menu_item_id', $elementor_item_id);
        }
    }

    wp_redirect(add_query_arg(
        [
            'post' => $elementor_item_id,
            'action' => 'elementor',
            'mm4ep_menu_id' => $menu_id,
            'mm4ep_menu_item_id' => $menu_item_id
        ],
        admin_url('post.php')
    ));

    exit;
}
add_action('init', 'sc_mm4ep_save_menu_item_indentity', 11, 0);

/**
 * Delete Elementor data when deleting menu item
 */
function sc_mm4ep_on_deleting_menu_item()
{
	if (!current_user_can('edit_theme_options')) {
		wp_die(-1);
	}

    $menu_item_id = intval($_POST['menu-item-id']);

    if ($elementor_item_id = get_post_meta($menu_item_id, 'mm4ep_elementor_menu_item_id', true)) {
        $deleted = wp_delete_post($elementor_item_id, true);
        if (!$deleted) {
            wp_send_json(['success' => false]);
        } else {
            wp_send_json(['success' => true]);
        }
    }
}
add_action('wp_ajax_delete-menu-item', 'sc_mm4ep_on_deleting_menu_item', 10, 0);

/**
 * Make shallow copies of nav menu data from editor data.
 */
function sc_mm4ep_extract_navmenu_data($post_id, $data)
{
    $ss = get_option('stylesheet');
    $post = get_post($post_id);

    if ('elementor_menu_item' === $post->post_type) {
        return;
    }

    foreach ($data as $el) {
        if (!empty($el['elements'])) {
            sc_mm4ep_extract_navmenu_data($post_id, $el['elements']);
            continue;
        }
        if ($el['elType'] === 'widget' && $el['widgetType'] === 'nav-menu') {
            if (!empty($el['settings']['menu'])) {
                $el['settings']['el_id'] = $el['id'];
                $el['settings']['post_id'] = $post_id;
                update_option($ss . '_mod_sc_mm4ep_' . $el['settings']['menu'], $el['settings']);
            }
        }
    }
}
add_action('elementor/editor/after_save', 'sc_mm4ep_extract_navmenu_data', 10, 2);
