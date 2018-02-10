$(document).ready(function () {

    $('.collapse--header').not('.is--active').next('.collapse--content').hide();

    $('.collapse--header').click(function () {
        var trig = $(this);

        trig.addClass('is--active');
        trig.next('.collapse--content').addClass('paulActiveContent');

        if (trig.hasClass('is--active') & !trig.hasClass('paulActiveContent')) {
            trig.next('.collapse--content').slideToggle(500);
        }

        if (trig.hasClass('is--active') & trig.next('.collapse--content').hasClass('paulActiveContent')) {
            trig.removeClass('is--active');
            trig.next('.collapse--content').removeClass('paulActiveContent');
        }

    });

});