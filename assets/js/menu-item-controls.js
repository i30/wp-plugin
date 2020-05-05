!function($) {
    "use strict";

    function ElementorMenuItemEditor(item) {
        let itemEl, isMega, device = "desktop", megaContent;

        function toggleTitle(y) {
            const a = $("> .menu-item-link", itemEl),
                l = $(".menu-item-label", a);

            y ? (!0, l.length && l.hide()) : (!1, l.length ? l.show() : a.append('<span class="menu-item-label">' + item.itemTitle + "</span>"))
        }

        function toggleMega(mega) {
            const indicator = $("> .menu-item-link .sub-arrow", itemEl);

            if (mega) {
                megaContent.show();
                if (!indicator.length) {
                    $("> .menu-item-link", itemEl).append('<span role="presentation" class="sub-arrow"><i class="fa"></i></span>');
                } else {
                    indicator.show();
                }
            } else {
                megaContent.hide();
                if (indicator.length && !item.isFlyout) {
                    indicator.hide();
                }
            }
        }

        function hideOnDevice(d, y) {
            const hide = y && d === device;

            if (hide) {
                if (isMega) {
                    megaContent.hide();
                }
                itemEl.hide();
            } else {
                if (isMega) {
                    megaContent.show();
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
                b = $("> .menu-item-badge", itemEl);

            if (y) {
                if (b.length) {
                    b.css({display: "inline-block"});
                } else {
                    const bb = $('<span class="menu-item-badge" style="line-height:1;color:#fff;background-color:#D30C5C">' + scMmm4epI18n.new + '</span>');
                    elementorCommonConfig.isRTL ? bb.insertBefore(a) : bb.insertAfter(a);
                }
            } else {
                b.length ? b.hide() : !1;
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

            if (undefined !== a.icon) {
                setIcon(a.icon);
            }

            if (undefined !== a.show_badge) {
                toggleBadge(a.show_badge);
            }

            if (undefined !== a.badge_label) {
                $("> .menu-item-badge", itemEl).text(a.badge_label);
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
            $(".elementor-control-field-description", c).hide();
            $("#elementor-panel-page-settings .elementor-tab-control-style").hide();
            $("#elementor-panel-header-title").html(scMmm4epI18n.menuItemSettings);
        }

        elementor.on("preview:loaded", () => {
            itemEl = $(".menu-item-" + item.itemId, elementor.$previewContents);
            megaContent = $("#content-scope", elementor.$previewContents);

            if (item.isChild) {
                itemEl.parents(".sub-menu").show();
            }

            if (item.isFlyout || item.isChild) {
                megaContent.hide();
            }

            itemEl.addClass("current-menu-item");

            $("> .menu-item-link", itemEl).addClass("elementor-item-active");
        });

        elementor.on("document:loaded", () => {
            isMega = elementor.settings.page.model.get("is_mega");
            device = elementor.channels.deviceMode.request("currentMode");

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
}(jQuery);
