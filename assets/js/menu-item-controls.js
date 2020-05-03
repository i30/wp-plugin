!function($) {
    "use strict";

    function ElementorMenuItemEditor(item) { // item => s
        let $el = {}, // c, cached DOM elements
            config, // a, menu settings
            isMega = !1, // l
            device = "desktop", // o
            activeItem = null, // d, current edit menu item list
            menu = null, // menu, #menu-scope
            content = null, // m, editor content section
            preview = null; // r, editor entire preview

        function toggleTitle(e) {
            const y = $(e.currentTarget).is(":checked"),
                a = $(".elementor-item-anchor", activeItem),
                l = $(".menu-item-label", activeItem);

            y ? (!0, l.length && l.hide()) : (!1, l.length ? l.show() : a.append('<span class="cmm4e-item-label">' + item.itemTitle + "</span>"))
        }

        function toggleBadge(e) {
            const y = $(e.currentTarget).is(":checked"),
                a = $("> .elementor-item-anchor", activeItem),
                b = $("> .menu-item-badge", activeItem);

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

        function i(e) {
            d.is(":hidden");
            var n = d.find("> .cmm4e-item-toggle"),
                t = k(e.target).is(":checked"),
                i = d.find("> .menu-item-arrow");
            if (s.toggleMega(t), t) {
                if (l = !0, c.sections.megaPanel.show(), c.sections.flyoutPanel.hide(), d.addClass("cmm4e-mega menu-item-has-children cmm4e-item-has-content"), d.find("> .cmm4e-sub-container").hide(), n.length || d.append('<span class="cmm4e-item-toggle cs-font clever-icon-plus"></span>'), i.length) i.show();
                else {
                    var o = "vertical" === a.desktop.orientation ? a.arrows.right : a.arrows.down;
                    d.append('<span role="presentation" class="menu-item-arrow ' + o + '"></span>')
                }
                r.find("#cmm4e-menu-content").show()
            } else {
                var m = d.find("> .cmm4e-sub-container");
                l = !1, c.sections.megaPanel.hide(), c.sections.flyoutPanel.show(), d.removeClass("cmm4e-mega cmm4e-item-has-content"), m.length ? m.show() : (d.removeClass("menu-item-has-children"), d.find("> .menu-item-arrow").hide()), r.find("#cmm4e-menu-content").hide()
            }
        }

        function h(e) {
            var n = k(e.target).val();
            s.setIcon(n);
            var t = d.find("> .cmm4e-nav-link > .menu-item-icon");
            t.length ? t.find("i").attr("class", n) : k('<span class="menu-item-icon"><i class="' + n + '"></i></span>').insertBefore(d.find("> .cmm4e-nav-link > .cmm4e-item-label"))
        }

        function u(e) {
            k(e.target).is(":checked") ? (!0, d.addClass("cmm4e-hide-on-mobile"), "mobile" !== o && "tablet" !== o || (d.hide(), d.hasClass("cmm4e-mega") && r.find("#cmm4e-menu-content").hide())) : (!1, d.removeClass("cmm4e-hide-on-mobile"), d.show(), d.hasClass("cmm4e-mega") && r.find("#cmm4e-menu-content").show())
        }

        function g(e) {
            if (k(e.target).is(":checked")) !0, d.hasClass("cmm4e-hide-sub-on-mobile") || d.addClass("cmm4e-hide-sub-on-mobile"), d.find("> .cmm4e-item-toggle").hide(), "mobile" !== o && "tablet" !== o || d.hasClass("cmm4e-mega") && r.find("#cmm4e-menu-content").hide();
            else {
                !1, d.removeClass("cmm4e-hide-sub-on-mobile");
                var n = d.find("> .cmm4e-item-toggle");
                n.length ? n.show() : (d.append('<span class="cmm4e-item-toggle cs-font clever-icon-plus"></span>'), n = d.find("> .cmm4e-item-toggle")), d.hasClass("cmm4e-mega") && r.find("#cmm4e-menu-content").show()
            }
        }

        function p(e) {
            k(e.target).is(":checked") ? (!0, d.addClass("cmm4e-hide-on-desktop"), "desktop" === o && (d.hide(), d.hasClass("cmm4e-mega") && r.find("#cmm4e-menu-content").hide())) : (!1, d.removeClass("cmm4e-hide-on-desktop"), d.show(), d.hasClass("cmm4e-mega") && r.find("#cmm4e-menu-content").show())
        }

        function f(e) {
            var n = d.find("> .cmm4e-content-container"),
                t = elementor.channels.deviceMode.request("currentMode");
            "mobile" === (o = t) || "tablet" === t ? (d.hasClass("cmm4e-hide-on-mobile") ? d.hide() : d.show(), d.hasClass("cmm4e-hide-sub-on-mobile") && d.find("> .cmm4e-item-toggle").hide(), l && (n.length ? n.hide() : d.append('<div class="cmm4e-content-container" style="display: none"><div class="cmm4e-content-wrapper">' + m.html() + "</div></div>"), m.hide())) : "desktop" === t ? (d.hasClass("cmm4e-hide-on-desktop") ? d.hide() : d.show(), d.hasClass("cmm4e-mega") ? d.find(".cmm4e-content-container").show() : d.hasClass("menu-item-has-children") && d.find(".cmm4e-sub-container").show(), l && (n.hide(), m.show())) : d.show()
        }

        function fetchUI() {
            const c = $("#elementor-panel-page-settings-controls");

            $el = {
                sections: {
                    general: $(".elementor-control-document_settings", c),
                    megaPanel: $(".elementor-control-mega_panel_settings", c)
                },
                controls: {
                    icon: $(".elementor-control-icon", c),
                    isMega: $(".elementor-control-is_mega", c),
                    hideTitle: $(".elementor-control-hide_title", c),
                    showBadge: $(".elementor-control-show_badge", c),
                    hideOnMobile: $(".elementor-control-hide_on_mobile", c),
                    hideOnDesktop: $(".elementor-control-hide_on_desktop", c),
                    hideSubOnMobile: $(".elementor-control-hide_sub_on_mobile", c)
                }
            };

            if (item.isFlyout || item.isChild) {
                $el.controls.isMega.hide();
                content.hide();
            }

            $(".elementor-control-post_status", c).hide();
            $("#elementor-panel-page-settings .elementor-tab-control-style").hide();
            $("#elementor-panel-header-title").html(scMmm4epI18n.menuItemSettings);
            $(".elementor-control-field-description", $el.controls.hideTitle).hide();

             // (d.hasClass("menu-item-has-children") ? d.find(".cmm4e-sub-container").hide() : d.addClass("menu-item-has-children cmm4e-item-has-content"));
            //
            // $("input", c.controls.hideTitle).length && $("input", c.controls.hideTitle).is(":checked");

            // $("input", c.controls.hideOnMobile).length && $("input", c.controls.hideOnMobile).is(":checked"), $("input", c.controls.hideOnDesktop).length && $("input", c.controls.hideOnDesktop).is(":checked"), $("input", c.controls.showBadge).length && $("input", c.controls.showBadge).is(":checked"),

            isMega = $("input", $el.controls.isMega).is(":checked");

            isMega ? $el.sections.megaPanel.show() : $el.sections.megaPanel.hide();
        }

        function bindEvents() {
            $("input", $el.controls.showBadge).on("change", toggleBadge);
            $("input", $el.controls.hideTitle).on("change", toggleTitle);
            // $("input", $el.controls.enableMega).on("change", toggleMega);
            // $("select", $el.controls.iconSelect).on("change", selectIcon);
            // $("input", $el.controls.hideOnMobile).on("change", toggleMobile);
            // $("input", $el.controls.hideOnDesktop).on("change", toggleDesktop);
            // $("input", $el.controls.hideSubOnMobile).on("change", toggleSub);
        }

        elementor.on("preview:loaded", () => {
            const pv = elementor.$previewContents;
            device = elementor.channels.deviceMode.request("currentMode");
            content = $("#content-scope", pv);
            activeItem = $(".menu-item-" + item.itemId, pv);
            preview = $("#preview-scope", pv);
            menu = $("#menu-scope", pv);
            config = menu.data("settings");
            activeItem.addClass("current-menu-item");
            $(".elementor-item-anchor", activeItem).addClass("elementor-item-active");
            // "mobile" !== o && "tablet" !== o || (d.hasClass("cmm4e-hide-on-mobile") && d.hide(),
            // d.hasClass("cmm4e-hide-sub-on-mobile") && d.find(".cmm4e-sub-panel").hide()),
            // "desktop" === o && d.hasClass("cmm4e-hide-on-desktop") && d.hide(), r.hasClass("cmm4e-mega-disabled") && r.find("#cmm4e-menu-content").hide(),
            // s.isChild && d.parents(".cmm4e-sub-container").css({
            //     visibility: "visible",
            //     opacity: 1
            // });
            // var t = parseFloat(d.offset().top - d.height()),
            //     i = parseFloat(t - d.height());
            // d.hasClass("cmm4e-mega") && (m.hasClass("cmm4e-content-vertical") ? r.find(".cmm4e-menu-content-container").css({
            //     marginTop: t + "px"
            // }) : r.find(".cmm4e-menu-content-container").css({
            //     marginTop: "20px"
            // })),
            // elementor.channels.deviceMode.on("change", f), elementor.$previewContents.find(".cmm4e-nav-link").on({
            //     mouseenter: function(e) {
            //         var n = k(this).parent();
            //         n.hasClass("cmm4e-current-menu-item") || m.css({
            //             zIndex: 1
            //         }), n.hasClass("cmm4e-mega") && n.hasClass("cmm4e-current-edit-item") && (m.hasClass("cmm4e-content-vertical") ? r.find(".cmm4e-menu-content-container").css({
            //             marginTop: i + "px"
            //         }) : r.find(".cmm4e-menu-content-container").css({
            //             marginTop: "0"
            //         }))
            //     },
            //     mouseleave: function(e) {
            //         var n = k(this).parent();
            //         n.hasClass("cmm4e-current-menu-item") || m.css({
            //             zIndex: 9
            //         }), n.hasClass("cmm4e-mega") && n.hasClass("cmm4e-current-edit-item") && (m.hasClass("cmm4e-content-vertical") ? r.find(".cmm4e-menu-content-container").css({
            //             marginTop: t + "px"
            //         }) : r.find(".cmm4e-menu-content-container").css({
            //             marginTop: "20px"
            //         }))
            //     }
            // });
        });

        elementor.on("document:loaded", () => {
            $("#elementor-mode-switcher-preview-input").on("change", e => {
                const closeEditorButton = $(top.document).find(".mm4ep-close-item-editor");
                if ($(e.currentTarget).is(":checked")) {
                    closeEditorButton.fadeOut();
                } else {
                    closeEditorButton.fadeIn()
                }
            });

            elementor.panel.currentView.on("set:page:page_settings", page => {
                page.on("render:collection", collection => {
                    if (collection._isRendered) {
                        fetchUI();
                        bindEvents();
                    }
                });
            });

            setTimeout(() => $e.route("panel/page-settings/settings"), 1000);
        });

        elementor.settings.page.model.on("change", e => {
            const v = e.changed;
            _.isUndefined(v.badge_label) || activeItem.find(" > .elementor-item-anchor .menu-item-badge").text(v.badge_label)
        });
    }

    $(() => new ElementorMenuItemEditor(top.currentMenuItem));
}(jQuery);
