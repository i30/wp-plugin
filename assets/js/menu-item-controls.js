/**
 * Copyright (c) SarahCoding <contact.sarahcoding@gmail.com>
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */

(function($) {
    "use strict";

    function ElementorMenuItemEditor(item) {
        let itemEl, isMega, device = "desktop", megaPanel, menuContainer;

        function toggleTitle(y) {
            const a = $("> .menu-item-link", itemEl),
                l = $(".menu-item-label", a);

            y ? (!0, l.length && l.hide()) : (!1, l.length ? l.show() : a.append('<span class="menu-item-label">' + item.itemTitle + "</span>"))
        }

        function toggleMega(mega) {
            const indicator = $("> .menu-item-link > .sub-arrow", itemEl);

            if (mega) {
                megaPanel.show();
                indicator.show();
            } else {
                megaPanel.hide();
                if (indicator.length && !item.isFlyout) {
                    indicator.hide();
                }
            }
        }

        function hideOnDevice(d, y) {
            const hide = y && d === device;

            if (hide) {
                if (isMega) {
                    megaPanel.hide();
                }
                itemEl.hide();
            } else {
                if (isMega) {
                    megaPanel.show();
                }
                itemEl.show();
            }
        }

        function setIcon(icon) {
            const itemLink = $("> .menu-item-link", itemEl),
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
            const a = $("> .menu-item-link", itemEl),
                b = $("> .menu-item-badge", a);

            if (y) {
                if (b.length) {
                    b.css({display: "inline-block"});
                } else {
                    const r = $("> .sub-arrow", a),
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
                            const $fitEl = $(elementor.settings.page.model.get("mega_fit_to_el"), menuContainer);
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
            if (width.unit === 'px') {
                megaPanel.css({width:width.size, 'margin-left': 'auto'});
            } else {
                const $fitEl = $(elementor.settings.page.model.get("mega_fit_to_el"), menuContainer);
                if ($fitEl.length === 1) {
                    megaPanel.css({width:width.size * $fitEl.outerWidth() / 100, 'margin-left': 'auto'});
                } else {
                    console.log('Fit-to element not found in the editor. Fall back to menu widget container');
                    megaPanel.css({width:width.size * menuContainer.outerWidth() / 100, 'margin-left': 'auto'});
                }
            }
        }

        function onSettingsChange(data) {
            const a = data.changed;

            if (undefined !== a.hide_title) {
                toggleTitle(a.hide_title);
            }

            if (undefined !== a.is_mega) {
                isMega = a.is_mega;
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
            }

            if (a.badge_label) {
                $("> .menu-item-link > .menu-item-badge", itemEl).text(a.badge_label);
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
        }

        function onSwitchPreview(e) {
            const closeEditorButton = $(top.document).find(".mm4ep-close-item-editor");

            if ($(e.currentTarget).is(":checked")) {
                closeEditorButton.fadeOut();
            } else {
                closeEditorButton.fadeIn()
            }
        }

        function renderControls() {
            const c = $("#elementor-panel-page-settings-controls"),
                isMegaControl = $(".elementor-control-is_mega", c);

            if (item.isFlyout || item.isChild) {
                isMegaControl.hide();
            }

            $(".elementor-control-post_status", c).hide();
            $("#elementor-panel-header-title").html(scMmm4epI18n.menuItemSettings);
            $("#elementor-panel-page-settings .elementor-panel-navigation").hide();
            $(".elementor-control-hide_title .elementor-control-field-description", c).hide();
        }

        elementor.on("preview:loaded", () => {
            isMega = elementor.settings.page.model.get("is_mega");
            device = elementor.channels.deviceMode.request("currentMode");
            itemEl = $(".menu-item-" + item.itemId, elementor.$previewContents);
            megaPanel = $("#content-scope", elementor.$previewContents);
            menuContainer = $("#menu-scope > .elementor-widget-container", elementor.$previewContents);

            if (item.isChild) {
                itemEl.parents(".sub-menu").show();
            }

            if (item.isFlyout || item.isChild || !isMega) {
                megaPanel.hide();
            }

            itemEl.addClass("current-menu-item");

            if (isMega && !$("> .menu-item-link > .sub-arrow", itemEl).length) {
                $("> .menu-item-link", itemEl).append('<span role="presentation" class="sub-arrow"><i class="fa"></i></span>');
            }

            $("> .menu-item-link", itemEl).addClass("elementor-item-active");

            fitMegaPanel(elementor.settings.page.model.get("mega_panel_fit"));
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
