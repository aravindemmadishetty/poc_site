(function($){
    $(document).ready(function(){
        $('#attendee_email_address').on('blur', function(){
            $.post(
                my_ajax_obj.ajax_url,
                {
                    action: 'check_user_email',
                    email: $('#attendee_email_address').val()
                },
                function response(result){
                    if(result){
                        $('#attendee_email_address').attr( 'style', 'backgroundColor:red !important;');
                    }
                }
            )
        })
    })
}(jQuery));