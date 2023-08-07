$(document).ready(function () {
    const emojis = JSON.parse($(".py-12").attr("data-emojis"));

    $(".emoji-picker-button").click(function () {
        toggleEmojiPicker(this);
    });

    window.toggleEmojiPicker = function (button) {
        const $picker = $(button).next();
        $picker.toggle();
    };

    window.toggleMoreEmojis = function (button) {
        const $moreEmojis = $(button).next();
        $moreEmojis.toggle();
    };

    $(".emoji-tab-button").click(function () {
        const tabName = $(this).attr("data-tab");
        const $picker = $(this).parent().parent().parent();
        switchEmojiTab(tabName, $picker);
    });

    window.switchEmojiTab = function (tabName, $picker) {
        const $tabs = $picker.find(".emoji-tab-container .emoji-tabs button");
        $tabs.removeClass("active");
        const $selectedTab = $picker.find(
            `.emoji-tab-container .emoji-tabs button[data-tab="${tabName}"]`
        );
        $selectedTab.addClass("active");

        const $emojiList = $picker.find(".emoji-tab-container .emoji-list");
        $emojiList.empty();

        const emojiCategory = emojis[tabName];

        emojiCategory.forEach((emoji) => {
            const $button = $("<button></button>");
            $button.attr("type", "button");
            $button.attr("name", "emoji");
            $button.val(emoji);
            $button.html(emoji);
            $button.on("click", function () {
                const topicId = $(this)
                    .parent()
                    .parent()
                    .parent()
                    .find("form")
                    .attr("id")
                    .split("-")[2];
                $(`#reaction-emoji-${topicId}`).val($(this).val());
                $(`#reaction-form-${topicId}`).submit();
            });
            $emojiList.append($button);
        });
    };

    $(window).on("load", function () {
        const $pickers = $(".emoji-picker");
        $pickers.each(function () {
            switchEmojiTab("smileys", $(this));
        });
    });
});
