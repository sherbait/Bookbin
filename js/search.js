$(document).ready(function () {

    $("#form_search").submit(function () {

        var search = $("#form_search_term").val();

        if (search == '')
        {
            alert("Enter a value");
        }
        else
        {
            var url = '';
            var img = '';
            var title = '';
            var author = '';

            $.get()
        }

    });
    return false;
});