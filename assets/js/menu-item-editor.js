export default function MenuItemEditor(item) {
    let self = this, $ = jQuery;


    self.popUp = $($("#mm4ep-item-editor-popup").html());
    self.menuId = $("input#menu").val();
    self.itemId = $("input.menu-item-data-db-id", item).val();
    self.itemTitle = $(".menu-item-title", item).text();
    self.settings = {};

    if (!_.isEmpty(scMmm4epItems[self.itemId])) {
        self.settings = scMmm4epItems[self.itemId];
    }

    if (self.settings.icon) {
        $(".item-title", item).prepend('<span class="mm4ep-item-icon"><i class="' + self.settings.icon + '"></i></span>')
    }

    self.setIcon = function(e) {
        const icon = item.find(".mm4ep-item-icon");

        icon.length ? icon.find("i").attr("class", e) : item.find(".item-title").prepend('<span class="mm4ep-item-icon"><i class="' + e + '"></i></span>')
    }

    self.openPopup = function() {
        if (item.hasClass("menu-item-depth-0")) {
            self.isChild = false
        } else {
            self.isChild = true;
        }

        if ($('input.menu-item-data-parent-id[value="' + self.itemId + '"]').length) {
            self.isFlyout = true;
        }

        if ($("#mm4ep-menu-id-" + self.itemId).length < 1) {
            self.popUp.append('<div class="eicon-editor-close mm4ep-close-item-editor"></div><iframe id="mm4ep-item-frame-' + self.itemId + '" src="' + scMmm4epConfig.editUrl + "&mm4ep_menu_id=" + self.menuId + "&mm4ep_menu_item_id=" + self.itemId + '"></iframe>').attr("id", "mm4ep-menu-id-" + self.itemId).css({
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            });
            $("body").append(self.popUp);
        }

        $("#mm4ep-menu-id-" + self.itemId).show();

        window.currentMenuItem = self;
    }

    $(".mm4ep-item-edit-btn", item).on("click", e => self.openPopup());
}
