<?php
/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
?>
<script type="text/template" id="mm4ep-item-editor-popup">
    <div class="mm4ep-item-editor-popup"></div>
</script>
<script type="text/template" id="mm4ep-settings">
    <div class="mm4ep-settings">
        <h3><?php esc_html_e('Elementor Mega Menu Settings', 'textdomain'); ?></h3>
        <fieldset class="menu-settings-group">
            <legend class="menu-settings-group-name howto"><?php esc_html_e('Elementor', 'textdomain'); ?></legend>
            <div class="menu-settings-input checkbox-input">
                <input id="mm4ep-is-elementor" type="checkbox" value="1" <?php checked($settings['is_elementor'], 1) ?> name="sc_mm4ep_settings[is_elementor]">
                <label for="mm4ep-is-elementor"><?php esc_html_e('Make menu items editable with Elementor or not.', 'textdomain') ?></label>
            </div>
        </fieldset>
        <fieldset class="menu-settings-group">
            <legend class="menu-settings-group-name howto"><?php esc_html_e('Schema markup', 'textdomain'); ?></legend>
            <div class="menu-settings-input checkbox-input">
                <input id="mm4ep-schema-markup" type="checkbox" value="1" <?php checked($settings['schema_markup'], 1) ?> name="sc_mm4ep_settings[schema_markup]">
                <label for="mm4ep-schema-markup"><?php echo sprintf(esc_html__('Whether to add %sschema markups%s or not.', 'textdomain'), '<a href="https://schema.org/SiteNavigationElement">', '</a>') ?></label>
            </div>
        </fieldset>
    </div>
</script>
<script type="text/javascript">
    const scMmm4epConfig = <?php echo json_encode([
            'editUrl' => admin_url('?cmm4e-edit-menu-item=true')
        ]) ?>,
        scMmm4epI18n = {
          "edit": "<?php esc_html_e('Edit', 'textdomain') ?>"
        }
</script>
<script defer src="<?php echo ELEMENTOR_PRO_MEGAMENU_URI . 'assets/js/menu-editor.min.js' ?>"></script>
<?php // EOF
