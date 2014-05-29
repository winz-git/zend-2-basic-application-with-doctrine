/**
 * Created by Winston on 14/5/14.
 */


$(function(){

    $('button[id^="id-btn-edit"]').click(function(e){
        var _id = $('td:first', $(this).parents('tr')).text();
        //redirect
        //redirect
        window.location.href= '/track/edit/'+ _id;
    });

    $('button[id^="id-btn-delete"]').click(function(e){
        var _id = $('td:first', $(this).parents('tr')).text();

        //redirect
        window.location.href= '/track/delete/'+ _id;
    });

    $('button[id^="id-btn-cancel"]').click(function(e){
       window.location.href = '/track';
    });


    //
    $('#theForm').validate({

        onkeyup:false,
        rules: {
            inputTitle: {
                required: true
            },
            inputYear:{
                required: true,
                minlength: 4,
                maxlength: 4

            }
        },
        messages: {
            inputTitle: {
                required: " Required input"
            },
            inputYear: {
                required: " Year is required",
                minLength: " Must be 4-digit year"
            }
        }


    });



});