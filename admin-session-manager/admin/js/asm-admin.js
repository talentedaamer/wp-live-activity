(function( $ ) {
	'use strict';

	/**
	 * get current user id
	 */
	var current_user_id = asm_params.current_user_id;

	/**
	 * initially load the users from localized data
	 */
	$(document).ready(function() {
        var initial_users_table = asm_display_users(asm_params.asm_active_users);
		if ( initial_users_table ) {
			$('#asm-users').html(initial_users_table);
		}

		var clear_session_btn = document.querySelectorAll('.asm-clear-session-btn');
		if ( clear_session_btn ) {
			clear_session_btn.forEach( function( button ) {
				button.addEventListener('click', function(event) {
					
					event.preventDefault();
					var confirmAction = confirm("Are you sure you want to terminate this session?");
					
					/**
					 * if not confirmed prevent floowing the link
					 */
					if (!confirmAction) {
						event.preventDefault();
						return;
					}

					window.location.href = this.href;
				});
			});

		}
    });
	
	/**
	 * Send users check to the data array of heartbeat
	 * it will be used in heartbeat_received function
	 */
	$(document).on('heartbeat-send', function(e, data) {
		data.asm_active_user_id = current_user_id
        data.asm_is_user_active = true;
	});

	/**
	 * heartbeat response function
	 */
	$(document).on('heartbeat-tick', function(e, data) {
		console.log(">> asm_active_users", data.asm_active_users)
		var display_users_table = asm_display_users(data.asm_active_users);
		if ( display_users_table ) {
			$('#asm-users').html(display_users_table);
		}
	});

	function asm_display_users(data) {
        var userList = '';
        if ( data && data.length > 0 ) {
            userList += '<table class="widefat striped">';
            $.each(data, function(index, user) {
				
                var status_html = '';
                if ( user.time_ago && user.time_ago.class === 'just-now' ) {
                    status_html = '<span class="asm-status asm-green"></span>';
                }
				
				var time_ago_html = '';
				if ( user.time_ago && user.time_ago.class ) {
					time_ago_html = '<div class="asm-online-status">';
						time_ago_html += '<strong class="'+ user.time_ago.class +'">Online: '+ user.time_ago.value +'</strong>';
					time_ago_html += '</div>';
				}

				var session_clear_html = '';
				if ( user.logged_in ) {
					session_clear_html += '<a class="asm-clear-session-btn asm-button button reset" href="'+ user.end_user_session_link +'">';
						session_clear_html += '<span class="dashicons dashicons-trash"></span>';
					session_clear_html += '</a>';
				}

				userList += '<tr>';
					userList += '<td>';
						userList += '<div class="asm-wrap">';
							
							userList += '<div class="asm-avatar">';
								userList += status_html;
								userList += user.author_avatar;
							userList += '</div>';

							userList += '<div class="asm-info">';
								userList += '<div class="asm-header">';
									userList += time_ago_html;
									userList += '<div class="asm-info-wrap">';
										userList += '<strong class="asm-name">'+ user.author +'</strong>';
										userList += '<span class="asm-seperator"> / </span>';
										userList += '<strong class="asm-date">'+ user.date_time +'</strong>';
									userList += '</div>';
								userList += '</div>';
								userList += '<span class="asm-user-email">'+ user.author_email +'</span>';
							userList += '</div>';

						userList += '</div>';
					userList += '</td>';

					userList += '<td style="width: 100px">';
						userList += '<div class="asm-actions">';
							userList += '<div class="asm-buttons">';
								// edit user profile link
								userList += '<a class="asm-button button" href="'+ user.edit_link +'">';
									userList += '<span class="dashicons dashicons-visibility"></span>';
								userList += '</a>';
								// clear session link
								userList += session_clear_html;
							userList += '</div>';
						userList += '</div>';
					userList += '</td>';

				userList += '</tr>';
				
            });
            userList += '</table>';
        }
        
        return userList
    }
})( jQuery );
