<?php namespace SC\MM4EP;
/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

use WP_Post;
use Walker_Nav_Menu;
use Elementor\Plugin as Elementor;

/**
 * MegaMenuWalker
 */
final class MegaMenuWalker extends Walker_Nav_Menu
{
    /**
     * Menu settings
     *
     * @var    array
     */
    private $menu_settings;

    /**
     * Constructor
     */
    public function __construct(array $menu_settings)
    {
        $this->is_rtl = is_rtl();
        $this->is_mobile = wp_is_mobile();
        $this->is_preview = Elementor::$instance->preview->is_preview_mode();
        $this->menu_settings = $menu_settings;
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see \Walker::start_lvl()
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        $output .= '<ul class="sub-menu elementor-nav-menu--dropdown">';
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see \Walker::end_lvl()
     */
    public function end_lvl(&$output, $depth = 0, $args = [])
    {
        $output .= '</ul>';
    }

    /**
     * Start the element output.
     *
     * @see \Walker::start_el()
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if (!$this->menu_settings['is_elementor']) {
            return;
        }

        $atts = [];
        $content = false;
        $settings = $this->getItemSettings($item->ID);

        if (!empty($settings['viewers']) && !$this->isItemViewable($settings['viewers'])) {
            return;
        }

        if ($settings['hide_on_mobile'] && $this->is_mobile && !$this->is_preview) {
            return;
        }

        if ($settings['hide_on_desktop'] && !$this->is_mobile && !$this->is_preview) {
            return;
        }

        $classes = (array)$item->classes;

        if ('yes' === $settings['hide_on_mobile']) {
            $classes[] = 'elementor-hidden-mobile';
        }

        if ('yes' === $settings['hide_on_tablet']) {
            $classes[] = 'elementor-hidden-tablet';
        }

        if ('yes' === $settings['hide_on_desktop']) {
            $classes[] = 'elementor-hidden-desktop';
        }

        $classes[] = 'menu-item-' . $item->ID;

        if ('yes' === $settings['is_mega'] && !$this->is_preview) {
            $content = $this->getItemContent($item->ID);
        }

        if ($content) {
            $atts['aria-haspopup'] = 'true';
        }

        $html_classes = join(' ', array_filter($classes));
        $html_classes = $content ? $html_classes . ' elementor-mega-menu-item' : $html_classes;

        $output .= '<li class="' . esc_attr($html_classes) . '">';

        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';

        $attributes = '';

        if (!isset($atts['class'])) {
            $atts['class'] = '';
        }

        $is_anchor = false !== strpos($atts['href'], '#');

        foreach ($atts as $attr => $value) {
            if ($attr === 'class') {
                $value = $value ? $value : 'menu-item-link';
                if ($depth) {
                    $value .= ' elementor-sub-item';
                } else {
                    $value .= ' elementor-item';
                }
                if ($is_anchor) {
                    $value .= ' elementor-item-anchor';
                }
                if (!$is_anchor && in_array('current-menu-item', $classes)) {
                    $value .= ' elementor-item-active';
                }
            }
            if (!empty($value)) {
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $schema_markup = $this->menu_settings['schema_markup'] && !isset($GLOBALS['navmenu_schema_markup_added']);

        // if (!empty($args->before) && is_string($args->before)) {
        //     $output .= $args->before;
        // }

        $output .= '<a'. $attributes;
        $output .= $schema_markup ? ' itemprop="url">' : '>';

        if (!empty($settings['icon'])) {
            $output .= sc_mm4ep_render_menu_item_icon($settings['icon']);
        }

        if (!$settings['hide_title'] || !empty($settings['icon'])) {
            $output .= '<span class="menu-item-label';
            $output .= $settings['hide_title'] ? ' elementor-screen-only"' : '"';
            $output .= $schema_markup ? ' itemprop="name">' : '>';
            $output .= $item->title;
            $output .= '</span>';
        }

        if ('yes' === $settings['show_badge']) {
            $output .= $this->getItemBadge($settings);
        }

        // if ($content && $this->is_preview) {
        //     $output .= '<span role="presentation" class="sub-arrow"><i class="fa"></i></span>';
        // }

        $output .= '</a>';

        if ($content) {
            $output .= '<ul class="sub-menu elementor-nav-menu--dropdown">';
            $output .= '<li class="menu-item mega-menu-item">';
            $mega_style = !$this->is_mobile ? 'width:' . $settings['mega_panel_width']['size'] . $settings['mega_panel_width']['unit'] . ';' : '';
            $output .= '<div class="elementor-mega-menu-content" style="' . esc_attr($mega_style) . '">';
            $output .= sprintf('<div class="elementor-mega-menu-content-inner">%s</div>', $content);
            $output .= '</div>';
            $output .= '</li>';
            $output .= '</ul>';
        }

        // if (!empty($args->after) && is_string($args->after)) {
        //     $output .= $args->after;
        // }
    }

    /**
     * Ends the element output, if needed.
     *
     * @see \Walker::end_el()
     */
    public function end_el(&$output, $item, $depth = 0, $args = [])
    {
        $output .= '</li>';
    }

    /**
     * Get item settings
     *
     * @param    int    $id    Item's ID.
     *
     * @return    array    $settings
     */
    private function getItemSettings($item_id)
    {
        $default = [
            'icon' => '',
            'is_mega' => '',
            'hide_title' => '',
            'viewers' => ['anyone'],
            'hide_on_mobile' => '',
            'hide_on_tablet' => '',
            'hide_on_desktop' => '',
            'show_badge' => '',
            'bagde_label' => esc_html__('New', 'textdomain'),
            'bagde_label_color' => '#FFFFFF',
            'bagde_bg_color' => '#D30C5C',
            'bagde_border_radius' => [],
            'mega_panel_width' => ['unit' => '%', 'size' => 100]
        ];

        $elementor_item_id = get_post_meta($item_id, 'mm4ep_elementor_menu_item_id', true);

        if ($elementor_item_id) {
            $menu_item_settings = get_post_meta($elementor_item_id, '_elementor_page_settings', true);
            return array_merge($default, (array)$menu_item_settings);
        }

        return $default;
    }

    /**
     * Get item settings
     *
     * @param    int    $id    Item's ID.
     *
     * @return    mixed    $content
     */
    private function getItemContent($item_id)
    {
        $elementor_item_id = get_post_meta($item_id, 'mm4ep_elementor_menu_item_id', true);

        if ($elementor_item_id) {
            return Elementor::$instance->frontend->get_builder_content_for_display($elementor_item_id);
        }

        return '';
    }

    /**
     * @return string
     */
    private function getItemBadge(array $settings)
    {
        $style = 'line-height:1';
        $output = '<span role="presentation" class="menu-item-badge"';

        $settings['badge_label'] = !empty($settings['badge_label']) ? $settings['badge_label'] : esc_html__('New', 'textdomain');

        if (!empty($settings['badge_label_color'])) {
            $style .= ';color:' . $settings['badge_label_color'];
        } else {
            $style .= ';color:#fff';
        }

        if (!empty($settings['badge_bg_color'])) {
            $style .= ';background-color:' . $settings['badge_bg_color'];
        } else {
            $style .= ';background-color:#D30C5C';
        }

        if (!empty($settings['badge_text_size'])) {
            $style .= ';font-size:' . $settings['badge_text_size']['size'] . $settings['badge_text_size']['unit'];
        }

        if (!empty($settings['badge_padding']['top'])) {
            $style .= sprintf(';padding:%spx %spx %spx %spx', $settings['badge_padding']['top'], $settings['badge_padding']['right'], $settings['badge_padding']['bottom'], $settings['badge_padding']['left']);
        }

        if (!empty($settings['badge_border_radius']['top'])) {
            $style .= sprintf(';border-radius:%spx %spx %spx %spx', $settings['badge_border_radius']['top'], $settings['badge_border_radius']['right'], $settings['badge_border_radius']['bottom'], $settings['badge_border_radius']['left']);
        }

        if (!empty($settings['badge_offset_top'])) {
            $style .= ';top:' . $settings['badge_offset_top']['size'] . $settings['badge_offset_top']['unit'];
        }

        if (!empty($settings['badge_offset_right'])) {
            $style .= ';right:' . $settings['badge_offset_right']['size'] . $settings['badge_offset_right']['unit'];
        }

        $output .= ' style="' . esc_attr($style) . '">' . esc_html($settings['badge_label']) . '</span>';

        return $output;
    }

    /**
     * Check if a menu item is viewable
     *
     * @param    array    $settings    Item's settings.
     *
     * @return    bool
     */
    private function isItemViewable($viewers)
    {
        $valid_roles = [];
        $roles = wp_roles()->roles;

        foreach ($roles as $role => $data) {
            if (in_array($role, $viewers)) {
                $valid_roles[] = $role;
            }
        }

        $user = wp_get_current_user();

        if ($user->exists()) {
            $minimal_role = $user->roles[0];
        } else {
            $minimal_role = 'anyone';
        }

        if (in_array($minimal_role, $valid_roles) || in_array('anyone', $viewers)) {
            return true;
        }

        return false;
    }
}