$(document).ready(function () {
    $('.ui.dropdown').dropdown();

    $('.ui.modal')
        .modal('show')
    ;

    $('#addRecord').on('click', function () {
        document.getElementById('newEntry').style.visibility = "visible";
    });

    $('#cancel').on('click', function () {
        document.getElementById('newEntry').style.visibility = "hidden";
        this.visibility = "hidden";
    });

    $('#close-message').style.visibility = "hidden";




});

