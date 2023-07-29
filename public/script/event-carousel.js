$(document).ready(function () {
    var scrollAmount = 0;

    $("#carousel-button-prev").click(function () {
        if (scrollAmount > 0) {
            scrollAmount -= 200;
        }
        $("#highlighted-event-wrap").animate(
            {
                scrollLeft: scrollAmount,
            },
            500
        );
    });

    $("#carousel-button-next").click(function () {
        if (
            scrollAmount <
            $("#highlighted-event-wrap")[0].scrollWidth -
                $("#highlighted-event-wrap").width()
        ) {
            scrollAmount += 200;
        }
        $("#highlighted-event-wrap").animate(
            {
                scrollLeft: scrollAmount,
            },
            500
        );
    });
});
