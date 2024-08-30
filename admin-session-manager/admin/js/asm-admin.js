(function( $ ) {
	'use strict';

	/**
	 * get current user id
	 */
	var current_user_id = asm_params.current_user_id;
	var is_current_user_admin = asm_params.is_current_user_admin;

	/**
	 * initially load the users from localized data
	 */
	$(document).ready(function() {
		var user_widget_wrapper = document.getElementById('asm-users');
		var sync_loader_display = document.getElementById('asm-sync');
		
		if ( user_widget_wrapper ) {
			var initial_users_table_html = generate_users_table_html(asm_params.asm_active_users);
			if ( initial_users_table_html ) {
				user_widget_wrapper.innerHTML = initial_users_table_html;
				clear_user_session_event_listner();
			}
		}

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
		if ( is_current_user_admin === "1" ) {
			$(document).on('heartbeat-tick', function(e, data) {
				if ( user_widget_wrapper ) {
					sync_loader_display.style.display = 'none';
					var users_table_html = generate_users_table_html(data.asm_active_users);
					if ( users_table_html ) {
						user_widget_wrapper.innerHTML = users_table_html;
						clear_user_session_event_listner();
					}
				}
			});
		}

		function clear_user_session_event_listner() {
			var clear_session_btn = document.querySelectorAll('.asm-clear-session-btn');
			if ( clear_session_btn ) {
				clear_session_btn.forEach(function(button) {
					button.asmConfirm({
						title: 'Clear user session',
						message: 'Are you sure you want to terminate this user session?',
						onConfirm: function() {
							window.location.href = this.href;
						},
						onCancel: function() {
							// do nothing
						}
					});
				});
			}
		}

		function generate_users_table_html(data) {
			var userList = '';
			if ( data && data.length > 0 ) {
				userList += '<table class="widefat striped">';
				$.each(data, function(index, user) {
					var email_icon = '<span class="dashicons dashicons-email"></span>';
					var user_icon = '<span class="dashicons dashicons-admin-users"></span>';
					var calendar_icon = '<span class="dashicons dashicons-calendar"></span>';
					var clear_session_icon = '<span class="dashicons dashicons-dismiss"></span>';
	
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
							session_clear_html += clear_session_icon;
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
											userList += '<strong class="asm-name">'+ user_icon + ' ' + user.author +'</strong>';
											// userList += '<span class="asm-seperator"> | </span>';
											userList += '<strong class="asm-date">'+ calendar_icon + ' ' + user.date_time +'</strong>';
										userList += '</div>';
									userList += '</div>';
									userList += '<span class="asm-user-email">'+ email_icon +' ' + user.author_email +'</span>';
								userList += '</div>';
	
							userList += '</div>';
						userList += '</td>';
	
						userList += '<td style="width: 50px">';
							userList += '<div class="asm-actions">';
								userList += '<div class="asm-buttons">';
									// edit user profile link
									userList += '<a class="asm-button button button-primary" href="'+ user.edit_link +'">';
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

    });

})( jQuery );

/**
 * Custom confirmation dialog for the plugin
 * accepts a dom element and options for th dialog
 * prevents opening default confirmation dialog
 */

/**
 * Extend the Element prototype to add the asm_confirm method
 * @param {*} options 
 */
Element.prototype.asmConfirm = function(options) {
    // Default options
    var settings = Object.assign({
		title: 'Dialog',
        message: 'Are you sure?',
        confirmText: 'Yes',
        cancelText: 'No',
        onConfirm: function() {},
        onCancel: function() {},
    }, options);

    /**
     * Event listener for the element
     */
    this.addEventListener('click', function(e) {
        e.preventDefault();

		// Create the backdrop
        var backdrop = document.createElement('div');
        backdrop.className = 'asm-confirm-backdrop';
        document.body.appendChild(backdrop);

        // Create the confirmation dialog
        var confirmBox = document.createElement('div');
        confirmBox.className = 'asm-confirm-dialog';

		var messageBox = document.createElement('div');
        messageBox.className = 'asm-message-wrap';

		var actionBox = document.createElement('div');
        actionBox.className = 'asm-actions-wrap';

        var message = document.createElement('p');
        message.textContent = settings.message;

		var title = document.createElement('strong');
		title.className = 'asm-title';
        title.textContent = settings.title;

		var confirmAction = document.createElement('button');
        confirmAction.className = 'asm-button button button-primary';
        confirmAction.textContent = settings.confirmText;

        var cancelAction = document.createElement('button');
        cancelAction.className = 'asm-button button reset';
        cancelAction.textContent = settings.cancelText;

		messageBox.appendChild(title);
		messageBox.appendChild(message);
		actionBox.appendChild(confirmAction);
		actionBox.appendChild(cancelAction);

        confirmBox.appendChild(messageBox);
        confirmBox.appendChild(actionBox);
        document.body.appendChild(confirmBox);

		// Style the backdrop
        backdrop.style.position = 'fixed';
        backdrop.style.top = '0';
        backdrop.style.left = '0';
        backdrop.style.width = '100%';
        backdrop.style.height = '100%';
        backdrop.style.backgroundColor = 'rgba(0, 0, 0, 0.3)';
        backdrop.style.zIndex = 999;

        // Style the confirmation box (basic styling)
        confirmBox.style.position = 'fixed';
        confirmBox.style.top = '50%';
        confirmBox.style.left = '50%';
        confirmBox.style.transform = 'translate(-50%, -50%)';
        confirmBox.style.background = '#fff';
        confirmBox.style.padding = '15px';
        confirmBox.style.border = '1px solid #ddd';
        confirmBox.style.zIndex = 1000;

        // Confirm button action
        confirmAction.addEventListener('click', function() {
            settings.onConfirm.call(this);
			document.body.removeChild(confirmBox);
			document.body.removeChild(backdrop);
        }.bind(this));

        // Cancel button action
        cancelAction.addEventListener('click', function() {
            settings.onCancel.call(this);
			document.body.removeChild(confirmBox);
			document.body.removeChild(backdrop);
        }.bind(this));
    }.bind(this));
};
