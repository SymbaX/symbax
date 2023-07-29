$(document).ready(function () {
    var scrollAmount = 0;
    var cardWidth = $(".highlighted-event-card").outerWidth(true);

    $(window).resize(function () {
        var previousCardWidth = cardWidth;
        cardWidth = $(".highlighted-event-card").outerWidth(true);
        var currentCardIndex = Math.round(scrollAmount / previousCardWidth);
        scrollAmount = currentCardIndex * cardWidth;
        $("#highlighted-event-wrap").scrollLeft(scrollAmount);
    });

    $("#carousel-button-prev").click(function () {
        if (scrollAmount > 0) {
            scrollAmount -= cardWidth;
            $("#highlighted-event-wrap").animate(
                {
                    scrollLeft: scrollAmount,
                },
                500
            );
        }
    });

    $("#carousel-button-next").click(function () {
        var maxScroll =
            $("#highlighted-event-wrap")[0].scrollWidth -
            $("#highlighted-event-wrap").width();
        if (scrollAmount < maxScroll) {
            scrollAmount += cardWidth;
            if (scrollAmount > maxScroll) {
                scrollAmount = maxScroll;
            }
            $("#highlighted-event-wrap").animate(
                {
                    scrollLeft: scrollAmount,
                },
                500
            );
        }
    });
});
