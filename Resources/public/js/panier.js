
function updateResume(urlResume, token) {
    $.ajax({
        url: urlResume,
        type: "GET",
        dataType: "html",
        data: {
            "token": token
        },
        success: function (data) {
           // console.log(data);
          //  updateList(urlList);
            $('div#panier-resume').html(data);
        },
        error: function (data) {
            $('div#ajax-error').show();
            $('div#ajax-error').html(data);
        }
    });
    return false;
}

function updateList(url) {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "html",
        data: {
            "token": token
        },
        success: function (data) {
            $('div#panier-resume').html(data);

        },
        error: function (data) {
            $('div#ajax-error').show();
            $('div#ajax-error').html(data);
        }
    });
    return false;
}