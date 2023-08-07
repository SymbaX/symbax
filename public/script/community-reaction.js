$(document).ready(function () {
    // ".py-12" クラスを持つ要素の "data-emojis" 属性から JSON を取得して解析します
    const emojis = JSON.parse($(".py-12").attr("data-emojis"));

    // ".emoji-picker-button" および ".more-emojis-button" クラスを持つ各要素に対して、クリックイベントリスナーを追加します
    $(document).on(
        "click",
        ".emoji-picker-button, .more-emojis-button",
        function () {
            toggleEmojiPicker(this);
        }
    );

    // 引数として与えられた button 要素の隣にある要素の表示/非表示を切り替えます
    window.toggleEmojiPicker = function (button) {
        const $picker = $(button).next();
        $picker.toggle();
    };

    // この関数は、"more" ボタンがクリックされたときに、その隣にある要素の表示/非表示を切り替えます
    window.toggleMoreEmojis = function (button) {
        const $moreEmojis = $(button).next();
        $moreEmojis.toggle();
    };

    // ".emoji-tab-button" クラスを持つ各要素に対して、クリックイベントリスナーを追加します
    $(".emoji-tab-button").click(function () {
        const tabName = $(this).attr("data-tab");
        const $picker = $(this).parent().parent().parent();
        switchEmojiTab(tabName, $picker);
    });

    // 指定されたタブをアクティブにし、そのタブの絵文字リストを表示します
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

        // 取得した絵文字の各要素に対して以下の操作を行います
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

    // ページの読み込みが完了したときに以下の操作を行います
    $(window).on("load", function () {
        const $pickers = $(".emoji-picker");
        $pickers.each(function () {
            switchEmojiTab("face_and_persons", $(this));
        });
    });
});
