$(document).ready(function () {
    $(".copy-id-btn").on("click", function (e) {
        e.preventDefault();

        var topicId = $(this).data("topicId");

        var $tempInput = $("<input>");
        $("body").append($tempInput);
        $tempInput.val(topicId).select();
        document.execCommand("copy");
        $tempInput.remove();
    });
});
