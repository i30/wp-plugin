/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

(function($) {
    "use strict";

    function ElementorMenuItemEditor(item) {
        let device = "desktop", megaPanel, menuContainer;

        function repositionIndicator() {
            let style = '',
                badge = $("> .menu-item-link > .menu-item-badge", item.$el),
                indicator = $("> .menu-item-link > .sub-arrow", item.$el);
            if (badge.length && badge.is(":visible")) {
                let offVal = 10 - badge.outerWidth();
                elementorCommonConfig.isRTL ? indicator.css({marginRight:offVal}) : indicator.css({marginLeft:offVal});
            } else {
                elementorCommonConfig.isRTL ? indicator.css({marginRight:0}) : indicator.css({marginLeft:0});
            }
        }

        function toggleTitle(y) {
            let a = $("> .menu-item-link", item.$el),
                l = $(".menu-item-label", a);

            y ? (!0, l.length && l.hide()) : (!1, l.length ? l.show() : a.append('<span class="menu-item-label">' + item.itemTitle + "</span>"))
        }

        function toggleMega(mega) {
            let indicator = $("> .menu-item-link > .sub-arrow", item.$el);

            if (mega) {
                if (!indicator.length) {
                    $("> .menu-item-link", item.$el).append('<span class="sub-arrow"><i class="fa"></i></span>');
                } else {
                    indicator.show();                    
                }
                megaPanel.show();
            } else {
                megaPanel.hide();
                if (indicator.length && !item.isFlyout) {
                    indicator.hide();
                }
            }
        }

        function hideOnDevice(d, y) {
            let hide = y && d === device;

            if (hide) {
                if (item.isMega) {
                    megaPanel.hide();
                }
                item.$el.hide();
            } else {
                if (item.isMega) {
                    megaPanel.show();
                }
                item.$el.show();
            }
        }

        function setIcon(icon) {
            let itemLink = $("> .menu-item-link", item.$el),
                itemIcon = $(".menu-item-icon", itemLink),
                data = {
                    'action': 'mm4ep_render_menu_item_icon',
                    'icon': icon
                };

            $.post(ajaxurl, data, r => {
                if (r != -1) {
                    if (itemIcon.length) {
                        itemIcon.remove();
                    }
                    itemLink.prepend(r);
                }
            });
        }

        function toggleBadge(y) {
            let a = $("> .menu-item-link", item.$el),
                b = $("> .menu-item-badge", a);

            if (y) {
                if (b.length) {
                    b.css({display: "inline-block"});
                } else {
                    let r = $("> .sub-arrow", a),
                        g = '<span class="menu-item-badge" style="color:#fff;background-color:#D30C5C">' + scMmm4epI18n.new + '</span>';
                    if (r.length) {
                        $(g).insertBefore(r);
                    } else {
                        a.append(g);
                    }
                }
            } else {
                b.length ? b.hide() : !1;
            }
        }

        function fitMegaPanel(fit) {
            switch (fit) {
                case 'menu':
                    megaPanel.css({width:$("> .elementor-nav-menu--main > .elementor-nav-menu", menuContainer).outerWidth(), 'margin-left': 'auto'});
                    break;
                case 'viewport':
                    megaPanel.css({width:'100vw', 'margin-left': '-2em'});
                    break;
                case 'auto':
                    megaPanel.css({width:'auto', 'margin-left': 'auto'});
                    break;
                case 'custom':
                    const fitW = elementor.settings.page.model.get("mega_panel_width");
                        if (fitW.unit === 'px') {
                            megaPanel.css({width:fitW.size, 'margin-left': 'auto'});
                        } else {
                            let $fitEl = $(elementor.settings.page.model.get("mega_fit_to_el"), menuContainer);
                            if ($fitEl.length === 1) {
                                megaPanel.css({width:fitW.size * $fitEl.outerWidth() / 100, 'margin-left': 'auto'});
                            } else {
                                console.log('Fit-to element not found in the editor. Fall back to menu widget container');
                                megaPanel.css({width:fitW.size * menuContainer.outerWidth() / 100, 'margin-left': 'auto'});
                            }
                        }
                    break;
                default:
                    megaPanel.css({width:menuContainer.outerWidth() + 4,'margin-left': 'auto', 'margin-right': 'auto'});
            }
        }

        function resizeMegaPanel(width) {
            if (device === 'mobile') {
                megaPanel.css({width:'100%', 'margin-left': 'auto'});
                return;
            }
            if (width.unit === 'px') {
                megaPanel.css({width:width.size, 'margin-left': 'auto'});
            } else {
                const $fitEl = $(elementor.settings.page.model.get("mega_fit_to_el"), menuContainer);
                if ($fitEl.length === 1) {
                    megaPanel.css({width:width.size * $fitEl.outerWidth() / 100, 'margin-left': 'auto'});
                } else {
                    // console.log('Fit-to element not found in the editor. Fall back to menu widget container');
                    megaPanel.css({width:width.size * menuContainer.outerWidth() / 100, 'margin-left': 'auto'});
                }
            }
        }

        function onSettingsChange(data) {
            let a = data.changed;

            if (undefined !== a.hide_title) {
                toggleTitle(a.hide_title);
            }

            if (undefined !== a.is_mega) {
                item.isMega = a.is_mega;
                toggleMega(a.is_mega);
            }

            if (undefined !== a.hide_on_mobile) {
                hideOnDevice("mobile", a.hide_on_mobile);
            }

            if (undefined !== a.hide_on_tablet) {
                hideOnDevice("tablet", a.hide_on_tablet);
            }

            if (undefined !== a.hide_on_desktop) {
                hideOnDevice("desktop", a.hide_on_desktop);
            }

            if (a.icon) {
                setIcon(a.icon);
            }

            if (undefined !== a.show_badge) {
                toggleBadge(a.show_badge);
                repositionIndicator();
            }

            if (a.badge_label) {
                $("> .menu-item-link > .menu-item-badge", item.$el).text(a.badge_label);
            }

            if (a.badge_padding) {
                setTimeout(() => repositionIndicator(), 50);
            }

            if (a.badge_text_size) {
                setTimeout(() => repositionIndicator(), 50);
            }

            if (a.mega_panel_fit) {
                fitMegaPanel(a.mega_panel_fit);
            }

            if (a.mega_panel_width) {
                resizeMegaPanel(a.mega_panel_width);
            }
        }

        function onDeviceChange() {
            device = elementor.channels.deviceMode.request("currentMode");

            switch (device) {
                case "mobile":
                    hideOnDevice("mobile", elementor.settings.page.model.get("hide_on_mobile"));
                    break;
                case "tablet":
                    hideOnDevice("tablet", elementor.settings.page.model.get("hide_on_tablet"));
                    break;
                default:
                    hideOnDevice("desktop", elementor.settings.page.model.get("hide_on_desktop"));
            }

            if (item.settings.layout != 'vertical') {
                setTimeout(() => resizeMegaPanel(elementor.settings.page.model.get("mega_panel_width")), 600);
            }
        }

        function onSwitchPreview(e) {
            let closeEditorButton = $(top.document).find(".mm4ep-close-item-editor");

            if ($(e.currentTarget).is(":checked")) {
                closeEditorButton.fadeOut();
            } else {
                closeEditorButton.fadeIn()
            }

            if (item.settings.layout != 'vertical') {
                let fit = elementor.settings.page.model.get("mega_panel_fit");
                if ("custom" != fit) {
                    setTimeout(() => fitMegaPanel(fit), 600);
                } else {
                    setTimeout(() => resizeMegaPanel(elementor.settings.page.model.get("mega_panel_width")), 600);
                }
            }
        }

        function renderControls() {
            let c = $("#elementor-panel-page-settings-controls");

            if (item.settings.layout == 'vertical') {
                $(".elementor-control-mega_panel_fit select", c).attr("disabled", true);
            }

            if (item.isFlyout || item.isChild) {
                $(".elementor-control-is_mega", c).hide();
            }

            $(".elementor-control-post_status", c).hide();
            $("#elementor-panel-header-title").html(scMmm4epI18n.menuItemSettings);
            $("#elementor-panel-page-settings .elementor-panel-navigation").hide();
            $(".elementor-control-hide_title .elementor-control-field-description", c).hide();
        }

        elementor.on("preview:loaded", () => {
            device = elementor.channels.deviceMode.request("currentMode");
            megaPanel = $("#content-scope", elementor.$previewContents);
            menuContainer = $("#menu-scope > .elementor-widget-container", elementor.$previewContents);
            item.$el = $(".menu-item-" + item.itemId, elementor.$previewContents);
            item.isMega = elementor.settings.page.model.get("is_mega");
            item.settings = $("#menu-scope", elementor.$previewContents).data("settings");

            if (item.isChild) {
                let css = {
                    left:'50%',
                    transform: 'translateX(-50%)',
                    width: 'auto'
                };
                if (mm4epConfig.isRTL) {
                    css = {
                        right:'50%',
                        transform: 'translateX(50%)',
                        width: 'auto'
                    }
                }
                item.$el.parents('.sub-menu-lv-0').css(css);
                let subSub = item.$el.parents('.sub-menu:not(.sub-menu-lv-0)');
                subSub.css({width: 'auto', top: mm4epConfig.flyoutSubOffsetTop});
                mm4epConfig.isRTL ? subSub.css('right', '100%') : subSub.css('left', '100%');
                item.$el.parents(".sub-menu").show();
            }

            if (item.isFlyout || item.isChild || !item.isMega) {
                megaPanel.hide();
            }

            item.$el.addClass("current-menu-item");

            if (item.isMega && !$("> .menu-item-link > .sub-arrow", item.$el).length) {
                $("> .menu-item-link", item.$el).append('<span class="sub-arrow"><i class="fa"></i></span>');
                repositionIndicator();
            }

            $("> .menu-item-link", item.$el).addClass("elementor-item-active");

            if ('vertical' != item.settings.layout) {
                fitMegaPanel(elementor.settings.page.model.get("mega_panel_fit"));
            }
        });

        elementor.on("document:loaded", () => {
            elementor.channels.deviceMode.on("change", onDeviceChange);

            elementor.settings.page.model.on("change", onSettingsChange);

            elementor.panel.currentView.on("set:page:page_settings", page => {
                page.on("render:collection", collection => {
                    collection._isRendered ? renderControls() : !1;
                });
            });

            $("#elementor-mode-switcher-preview-input").on("change", onSwitchPreview);

            setTimeout(() => $e.route("panel/page-settings/settings"), 1000);
        });
    }

    $(() => new ElementorMenuItemEditor(top.currentMenuItem));
}(jQuery));
