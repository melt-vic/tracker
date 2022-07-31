window.jQuery = $;
window.$ = $;

let intervalId;

$(document).ready(function () {
    let app = {
        stopTask: function () {
            $('#stop-btn').on('click', function () {
                let url = 'stop-task/' + $(this).data('id');

                $.ajax({
                    method: "GET",
                    url: url,
                }).done(function () {
                    clearInterval(intervalId);
                    $('.alert-success.alert-dismissible').slideUp(500);
                    $('#start-btn').toggle();
                }).fail(function (data) {
                    alert('Something went wrong. :-( Please, try again later.')
                    console.log(JSON.stringify(data));
                });
            });
        },
        chronometer: function () {
            function updateDisplay(from) {
                let distance = new Date() - from;
                const second = 1000;
                const minute = second * 60;
                const hour = minute * 60;
                const day = hour * 24;

                $('#seconds').text(Math.floor((distance % minute) / second));
                $('#minutes').text(Math.floor((distance % hour) / minute));
                $('#hours').text(Math.floor((distance % day) / hour));
            }

            if ($('#chronometer').length) {
                let totalTime = (typeof $('#chronometer').data('totalTime') === 'undefined') ? 0 : $('#chronometer').data('totalTime');
                let from = new Date(Date.now() - totalTime * 1000);     // Date.now() returns milliseconds
                console.log(Date.now());
                intervalId = setInterval(function () {
                    updateDisplay(from)
                }, 1000);
            }
        }
    }

    app.stopTask();
    app.chronometer();
});