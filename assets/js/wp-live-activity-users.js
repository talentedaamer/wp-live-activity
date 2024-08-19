jQuery(document).ready(function($) {
    // Set up Heartbeat API settings
    console.log("wpla_users_params", wpla_users_params);
    var current_user_id = wpla_users_params.wpla_current_user_id;

    var generated_table = wpla_generate_users_table(wpla_users_params.wpla_users)
    $('#wpla-online-users').html(generated_table);

    // Hook into Heartbeat API
    $(document).on('heartbeat-send', function(e, data) {
        // Send the current user's ID to track activity
        data.active_user = current_user_id
        data.wpla_check_online_users = true;
    });

    // Handle data received from the server
    $(document).on('heartbeat-tick', function(e, data) {
        console.log("users tick", data);
        var generated_table = wpla_generate_users_table(data.wpla_online_users)
        $('#wpla-online-users').html(generated_table);
    });

    function wpla_generate_users_table(data) {
        var userList = '';
        if (data) {
            userList += '<table class="widefat striped">';
            $.each(data, function(index, user) {
                var statusHTML = '';
                if (user.time_ago.class === 'just-now') {
                    statusHTML = `<span class="wpla-status wpla-green"></span>`;
                }
                userList += `
                <tr>
                    <td>
                        <div class="wpla-user-profile">
                            <div class="wpla-user-avatar">
                                ${statusHTML}
                                ${user.avatar}
                            </div>
                            <div class="wpla-user-info">
                                <a href="#"><strong class="wpla-user-name">${user.display_name}</strong></a>
                                <span class="wpla-user-email">${user.user_email}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="wpla-user-status">
                            <strong class="${ user.time_ago.class }">Online: ${ user.time_ago.value }</strong>
                            <div><strong>${ user.last_active_datetime }</strong></div>
                        </div>
                    </td>
                    <td>
                        <div class="wpla-user-actions">
                            <a href="${user.edit_link}">
                                <span class="dashicons dashicons-visibility"><span class="screen-reader-text">View User</span></span>
                            </a>
                        </div>
                    </td>
                </tr>`;
            });
            userList += '</table>';
        }
        
        return userList
    }
});