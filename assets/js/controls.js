! function(k) {
    "use strict";
jQuery("#elementor-mode-switcher-preview-input").on("change", (e) => jQuery(e.currentTarget).is(":checked") ? console.log("preview") : console.log("edit"));
    function e(s) {
        var c = {},
            a = (s = s, null),
            l = !1,
            o = "desktop",
            d = null,
            e = null,
            m = null,
            r = null;

        function n(e) {
            var n = k(e.target).is(":checked"),
                t = d.find(".cmm4e-nav-link"),
                i = d.find(".cmm4e-item-label");
            n ? (!0, i.length && i.hide()) : (!1, i.length ? i.show() : t.append('<span class="cmm4e-item-label">' + top.clever_mega_menu_item_title + "</span>"))
        }

        function t(e) {
            var n = k(e.target).is(":checked"),
                t = d.find("> .cmm4e-nav-link"),
                i = t.find("> .menu-item-badge");
            n ? (!0, i.length ? i.css({
                display: "inline-block"
            }) : t.append('<span class="menu-item-badge" style="line-height:1;color:#fff;background-color:#2ed164">New</span>')) : (!1, i.length && i.hide())
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

        function b() {
            var e = k("#elementor-panel-page-settings-controls");
            k("#elementor-panel-page-settings .elementor-tab-control-style").hide(), k("#elementor-panel-header-title").html(top.cmm4eL10n.edit + ' "' + top.clever_mega_menu_item_title + '"'), c = {
                sections: {
                    general: k(".elementor-control-document_settings", e),
                    flyoutPanel: k(".elementor-control-flyout_panel_settings", e),
                    megaPanel: k(".elementor-control-mega_panel_settings", e)
                },
                controls: {
                    hideTitle: k(".elementor-control-hide_title", e),
                    enableMega: k(".elementor-control-enable_mega", e),
                    iconSelect: k(".elementor-control-cmm4e_icon", e),
                    hideOnMobile: k(".elementor-control-hide_on_mobile", e),
                    hideSubOnMobile: k(".elementor-control-hide_sub_on_mobile", e),
                    hideOnDesktop: k(".elementor-control-hide_on_desktop", e),
                    showBadge: k(".elementor-control-show_badge", e)
                }
            }, k(".elementor-control-post_status", e).hide(), k(".elementor-control-field-description", c.hideTitle).hide(), k("input", c.controls.enableMega).length && (l = k("input", c.controls.enableMega).is(":checked")) && (d.hasClass("menu-item-has-children") ? d.find(".cmm4e-sub-container").hide() : d.addClass("menu-item-has-children cmm4e-item-has-content")), k("input", c.controls.hideTitle).length && k("input", c.controls.hideTitle).is(":checked"), k("input", c.controls.hideOnMobile).length && k("input", c.controls.hideOnMobile).is(":checked"), k("input", c.controls.hideOnDesktop).length && k("input", c.controls.hideOnDesktop).is(":checked"), k("input", c.controls.showBadge).length && k("input", c.controls.showBadge).is(":checked"), l ? (c.sections.megaPanel.show(), c.sections.flyoutPanel.hide()) : (c.sections.megaPanel.hide(), c.sections.flyoutPanel.show())
        }

        function v() {
            k("input", c.controls.hideTitle).on("change", n), k("input", c.controls.enableMega).on("change", i), k("select", c.controls.iconSelect).on("change", h), k("input", c.controls.hideOnMobile).on("change", u), k("input", c.controls.hideSubOnMobile).on("change", g), k("input", c.controls.hideOnDesktop).on("change", p), k("input", c.controls.showBadge).on("change", t)
        }

        function w(e) {
            e._isRendered && (b(), v())
        }

        function C(e) {
            b(), v(), e.on("render:collection", w)
        }
        elementor.on("preview:loaded", function() {
            o = elementor.channels.deviceMode.request("currentMode"), m = elementor.$previewContents.find("#cmm4e-menu-content"), d = elementor.$previewContents.find("#cmm4e-menu-item-" + s.menuId), r = elementor.$previewContents.find("#cmm4e-menu-container"), e = r.find("> .cmm4e-navigation-menu > .cmm4e-container > .cmm4e"), a = e.data("config"), d.addClass("cmm4e-current-menu-item cmm4e-current-edit-item"), "mobile" !== o && "tablet" !== o || (d.hasClass("cmm4e-hide-on-mobile") && d.hide(), d.hasClass("cmm4e-hide-sub-on-mobile") && d.find(".cmm4e-sub-panel").hide()), "desktop" === o && d.hasClass("cmm4e-hide-on-desktop") && d.hide(), r.hasClass("cmm4e-mega-disabled") && r.find("#cmm4e-menu-content").hide(), s.isChild && d.parents(".cmm4e-sub-container").css({
                visibility: "visible",
                opacity: 1
            });
            var t = parseFloat(d.offset().top - d.height()),
                i = parseFloat(t - d.height());
            d.hasClass("cmm4e-mega") && (m.hasClass("cmm4e-content-vertical") ? r.find(".cmm4e-menu-content-container").css({
                marginTop: t + "px"
            }) : r.find(".cmm4e-menu-content-container").css({
                marginTop: "20px"
            })), elementor.panel.currentView.on("set:page:page_settings", C), elementor.channels.deviceMode.on("change", f), elementor.$previewContents.find(".cmm4e-nav-link").on({
                mouseenter: function(e) {
                    var n = k(this).parent();
                    n.hasClass("cmm4e-current-menu-item") || m.css({
                        zIndex: 1
                    }), n.hasClass("cmm4e-mega") && n.hasClass("cmm4e-current-edit-item") && (m.hasClass("cmm4e-content-vertical") ? r.find(".cmm4e-menu-content-container").css({
                        marginTop: i + "px"
                    }) : r.find(".cmm4e-menu-content-container").css({
                        marginTop: "0"
                    }))
                },
                mouseleave: function(e) {
                    var n = k(this).parent();
                    n.hasClass("cmm4e-current-menu-item") || m.css({
                        zIndex: 9
                    }), n.hasClass("cmm4e-mega") && n.hasClass("cmm4e-current-edit-item") && (m.hasClass("cmm4e-content-vertical") ? r.find(".cmm4e-menu-content-container").css({
                        marginTop: t + "px"
                    }) : r.find(".cmm4e-menu-content-container").css({
                        marginTop: "20px"
                    }))
                }
            }), $e.route("panel/page-settings/settings")
        }), elementor.settings.page.model.on("change", function(e) {
            var n = e.changed;
            _.isUndefined(n.badge_label) || d.find(" > .cmm4e-nav-link .menu-item-badge").text(n.badge_label)
        })
    }
    k(function() {
        window.cmm4eElementor = window.cmm4eElementor || new e(top.currentEditItem)
    })
}(jQuery);
