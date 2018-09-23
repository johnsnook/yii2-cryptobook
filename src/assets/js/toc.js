/*
 * @author John Snook
 * @date Sep 17, 2018
 * @license https://snooky.biz/site/license
 * @copyright 2018 John Snook Consulting
 * Description of toc.js
 */

$("time.timeago").timeago();
//$timeago.settings.strings.inPast = "time has elapsed";
$.timeago.settings.strings = {
    inPast: null,
    prefixAgo: null,
    prefixFromNow: null,
    suffixAgo: "",
    suffixFromNow: "",
    seconds: "1m",
    minute: "1m",
    minutes: "%dm",
    hour: "1h",
    hours: "%dh",
    day: "1d",
    days: "%dd",
    month: "1mo",
    months: "%dmo",
    year: "1yr",
    years: "%dyr",
    wordSeparator: " ",
    numbers: []
};

$('div.ui-sortable-handle').removeClass('ui-sortable-handle');

$('#toc').sortable({
    handle: $('i.drag-handle'),
    update: function (event, ui) {
        toc = [];
        $(event.target).children().each(function (index, child) {
            $(child).find('.chapterIndex').text(index + 1);
            toc.push($(child).data('id'));
        });
        console.log(reindexUrl + '&toc=' + toc.join(','));
        $.getJSON(reindexUrl + '&toc=' + toc.join(','));
    }
});


$(document).ready(function () {
    // Add smooth scrolling to all links
    $("a").on('click', function (event) {

        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 100
            }, 800, function () {

                // Add hash (#) to URL when done scrolling (default click behavior)
                //window.location.hash = hash;
            });
        } // End if
    });
});
