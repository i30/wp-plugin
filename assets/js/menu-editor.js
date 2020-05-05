import MenuItemEditor from './menu-item-editor';

(function(api, $) {
    "use strict";

    function MenuEditor(menuId) {
        let $el = {};

        api.addItemToMenu = (item, processMethod, callback) => {
            let nonce = $('#menu-settings-column-nonce').val(), params;

            processMethod = processMethod || function() {};
            callback = callback || function() {};

            params = {
                'action': 'add-menu-item',
                'menu': menuId,
                'menu-settings-column-nonce': nonce,
                'menu-item': item
            };

            $.post(ajaxurl, params, menuMarkup => {
                const ins = $('#menu-instructions'),
                    display = $el.elementorCheckbox.is(":checked") ? "inline" : "none";
                menuMarkup = $.trim(menuMarkup); // Trim leading whitespaces.
                processMethod(menuMarkup, params);
                const pendingItem = $(".menu-item.pending");
                $(".item-title", pendingItem).append('<span style="display:' + display + '" class="mm4ep-item-edit-btn"><i class="eicon-elementor-square"></i> ' + scMmm4epI18n.edit + '</span>');
                $el.itemEditButton = $(".mm4ep-item-edit-btn");
                new MenuItemEditor(pendingItem);
                pendingItem.hide().fadeIn('slow');
                $('.drag-instructions').show();
                if (!ins.hasClass('menu-instructions-inactive') && ins.siblings().length)
                    ins.addClass('menu-instructions-inactive');
                callback();
            });
        }

        api.removeMenuItem = el => {
            const children = el.childMenuItems();

            $(document).trigger('menu-removing-item', [el]);

            if (!el.hasClass("pending")) {
                $.post(ajaxurl, {
                    "action": "delete-menu-item",
                    "menu": menuId,
                    "menu-item-id": el.attr("id").replace("menu-item-", "")
                }, (r) => {
                    if (!r.success) {
                        console.log("Failed to delete menu item Elementor data.");
                    }
                });
            }

            el.addClass('deleting').animate({
                opacity: 0,
                height: 0
            }, 350, function() {
                var ins = $('#menu-instructions');
                el.remove();
                children.shiftDepthClass(-1).updateParentMenuItemDBId();
                if (0 === $('#menu-to-edit li').length) {
                    $('.drag-instructions').hide();
                    ins.removeClass('menu-instructions-inactive');
                }
                api.refreshAdvancedAccessibility();
            });
        }

        $("#update-nav-menu .menu-settings").append($("#mm4ep-settings").html());

        $("li", wpNavMenu.menuList).each(function(i) {
            const el = $(this);
            if (0 === $(".mm4ep-item-edit-btn", el).length) {
                $(".item-title", el).append('<span class="mm4ep-item-edit-btn"><i class="eicon-elementor-square"></i> ' + scMmm4epI18n.edit + '</span>');
                new MenuItemEditor(el);
            }
        });

        $el.itemEditButton = $(".mm4ep-item-edit-btn");
        $el.elementorCheckbox = $("#mm4ep-is-elementor");

        $el.itemEditButton.toggle($el.elementorCheckbox.is(":checked"));

        $(document).on(
            "click",
            ".mm4ep-close-item-editor",
            e => $(".mm4ep-item-editor-popup").hide()
        );

        $el.elementorCheckbox.on(
            "change",
            e => $el.itemEditButton.toggle($(e.currentTarget).is(":checked"))
        );
    }

    // DOM ready
    $(() => new MenuEditor($("input#menu").val()))
}(wpNavMenu, jQuery));
