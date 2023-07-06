require("./bootstrap");
// window.$ = window.jQuery = require("jquery");
import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

jQuery(document).ready(function ($) {
    setTimeout(() => {
        $("#status_message").slideUp("slow");
    }, 2000);

    // Task Filter Box
    $("#task_filter_btn").on("click", function () {
        var text = $(this).text();

        if (text == "Filter") {
            $(this).text("Close Filter");
        }
        if (text == "Close Filter") {
            $(this).text("Filter");
        }

        $("#task_filter").slideToggle("slow");
    });

});

CKEDITOR.replace("description");

function progress(timeleft, timetotal, $element) {
    var progressBarWidth = (timeleft * $element.width()) / timetotal;
    $element
        .find("div")
        .animate({ width: progressBarWidth }, 500)
        .html(Math.floor(timeleft / 60) + ":" + (timeleft % 60));
    if (timeleft > 0) {
        setTimeout(function () {
            progress(timeleft - 1, timetotal, $element);
        }, 1000);
    }
}
