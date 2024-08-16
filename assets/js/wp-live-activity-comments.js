jQuery(document).ready(function($) {
    $(document).on('heartbeat-send', function(e, data) {
        data.wpla_check_live_comments = true;
    });

    $(document).on('heartbeat-tick', function(e, data) {
        if (data.wpla_online_users) {
            console.log("tick >> comments:", data.wpla_live_comments);
            
            // Update the frontend with the online users list
            // var userList = '<table class="widefat striped">';
            // $.each(data.wpla_live_comments, function(index, user) {
            //     userList += ``;
            // });
            // userList += '</table>';
            // $('#wpla-live-comments').html(userList);
        }
    });
});