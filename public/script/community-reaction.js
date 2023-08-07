document.addEventListener("DOMContentLoaded", (event) => {
    const emojis = JSON.parse(
        document.querySelector(".py-12").getAttribute("data-emojis")
    );

    document.querySelectorAll(".emoji-picker-button").forEach((button) => {
        button.addEventListener("click", function () {
            toggleEmojiPicker(this);
        });
    });

    window.toggleEmojiPicker = function (button) {
        const picker = button.nextElementSibling;
        picker.style.display =
            picker.style.display === "none" ? "block" : "none";
    };

    // function toggleMoreEmojis(button) {
    window.toggleMoreEmojis = function (button) {
        const moreEmojis = button.nextElementSibling;
        moreEmojis.style.display =
            moreEmojis.style.display === "none" ? "block" : "none";
    };

    document.querySelectorAll(".emoji-tab-button").forEach((button) => {
        button.addEventListener("click", function () {
            const tabName = this.getAttribute("data-tab");
            const picker = this.parentElement.parentElement.parentElement;
            switchEmojiTab(tabName, picker);
        });
    });

    window.switchEmojiTab = function (tabName, picker) {
        const tabs = picker.querySelectorAll(
            ".emoji-tab-container .emoji-tabs button"
        );
        tabs.forEach((tab) => tab.classList.remove("active"));
        const selectedTab = picker.querySelector(
            `.emoji-tab-container .emoji-tabs button[data-tab="${tabName}"]`
        );
        selectedTab.classList.add("active");

        const emojiList = picker.querySelector(
            ".emoji-tab-container .emoji-list"
        );
        emojiList.innerHTML = "";

        const emojiCategory = emojis[tabName];

        emojiCategory.forEach((emoji) => {
            const button = document.createElement("button");
            button.type = "button";
            button.name = "emoji";
            button.value = emoji;
            button.innerHTML = emoji;
            button.onclick = function () {
                const topicId = this.parentElement.parentElement.parentElement
                    .querySelector("form")
                    .id.split("-")[2];
                document.getElementById(`reaction-emoji-${topicId}`).value =
                    this.value;
                document.getElementById(`reaction-form-${topicId}`).submit();
            };
            emojiList.appendChild(button);
        });
    };

    window.onload = function () {
        const pickers = document.querySelectorAll(".emoji-picker");
        pickers.forEach((picker) => {
            switchEmojiTab("smileys", picker);
        });
    };
});
