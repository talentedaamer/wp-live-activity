jQuery(document).ready(function($) {
    // Set up Heartbeat API settings
    var heartbeatInterval = wpla_params.wpla_interval;
    var current_user_id = wpla_params.wpla_current_user_id;

    // Hook into Heartbeat API
    $(document).on('heartbeat-send', function(e, data) {
        // Send the action to check for notifications
        data.action = 'wpla_check_notifications';

        // Send the current user's ID to track activity
        data.active_user = current_user_id
    });

    // Handle data received from the server
    $(document).on('heartbeat-tick', function(e, data) {
        if (data.notifications) { // Access the notifications directly from the data object
            var notifications = data.notifications;
            // Example: Display notifications in the console
            console.log("heartbeat-tick >> notifications", notifications);
        }

        if ( data.active_user ) {
            console.log("heartbeat-tick >> active_user:", data.active_user);
        }
    });

    // Start the Heartbeat API with configured interval
    // setInterval(function() {
    //     $.post(wpla_params.wpla_ajax_url, { action: 'wpla_check_notifications' }, function(response) {
    //         if (response.success) {
    //             console.log('Notifications:', response.data);
    //         }
    //     });
    // }, 10 * 1000);
});