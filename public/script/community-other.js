$(document).ready(function () {
    let textLength = $("#content").val().length;
    $("#content-count").text(textLength + " / 300");

    $(".copy-id-btn").on("click", function (e) {
        e.preventDefault();

        var topicId = $(this).data("topicId");

        var $tempInput = $("<input>");
        $("body").append($tempInput);
        $tempInput.val(topicId).select();
        document.execCommand("copy");
        $tempInput.remove();
    });

    $("#content").on("keyup", function () {
        let textLength = $(this).val().length;
        $("#content-count").text(textLength + " / 300");

        if (textLength > 0 && textLength <= 300) {
            $(".send-button").prop("disabled", false);
        } else {
            $(".send-button").prop("disabled", true);
        }
    });
});
