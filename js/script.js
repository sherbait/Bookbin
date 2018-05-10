$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).ready(function(){
    $('.timer').each(function() {
        let target = new Date($(this).data('end')), update, $this = $(this);
        (update = function () {
            let now = new Date();
            $this.text((new Date(target - now)).toUTCString().split(' ')[4]);
            if (Math.floor((target - now)/1000) == 0) return; // timer stops
            setTimeout(update, 1000);
        })();
    });
});

function getUserInfo(id) {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("user_mail_info").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET","get_mail_info.php?q="+id,true);
    xmlhttp.send();
}