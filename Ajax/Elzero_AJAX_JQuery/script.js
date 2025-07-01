// First Demo
// $(function () {
//     $('button').click(function () {
//         $('#show').load($(this).data('page') + ' .content', function (resTxt, st, xhr) {
//             if (st == "error") {
//                 var msg = "Sorry but there was an error: ";
//                 $('#show').html(msg + xhr.status + " " + xhr.statusText);
//             }else if(st == "success"){
//                 $('#show').html(resTxt);
//             }
//         });
//         return false;
//     });
// });

// Second Demo
$(function () {
    $('button').click(function () {
        // GET

        // $.get('auth.php?name=Muhammad&last_login=Today', function (resTxt, st, xhr) {
        //     if (st == "error") {
        //         var msg = "Sorry but there was an error: ";
        //         $('#show').html(msg + xhr.status + " " + xhr.statusText);   
        //     }else if(st == "success"){
        //         $('#show').html(resTxt);
        //     }
        // });
        // return false;

        // POST
        // $.post('auth.php', {
        //     name: 'Muhammad',
        //     last_login: 'Today'
        // }, function (resTxt, st, xhr) {
        //     if (st == "error") {
        //         var msg = "Sorry but there was an error: ";
        //         $('#show').html(msg + xhr.status + " " + xhr.statusText);
        //     } else if (st == "success") {
        //         $('#show').html(resTxt);
        //     }
        // });
        // return false;

        // AJAX
        $.ajax({
            url: 'auth.php',
            type: 'POST',
            data: {
                name: 'Muhammad',
                last_login: 'Today'
            },
            cache: false, // Default is True
            success: function (resTxt, st, xhr) {
                $('#show').html(resTxt);
            },
            error: function (xhr, st, err) {
                var msg = "Sorry but there was an error: ";
                $('#show').html(msg + xhr.status + " " + xhr.statusText);
            },
            complete: function () {
                $('#show').append('<br>Done');
            }
        });
        return false;
    });
});