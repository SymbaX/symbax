$(document).ready(function () {
    $(".dropdown-btn").click(function (e) {
        e.stopPropagation();
        $(".dropdown-menu").hide();
        $(this).next(".dropdown-menu").toggle();
    });

    $(document).click(function () {
        $(".dropdown-menu").hide();
    });
});
