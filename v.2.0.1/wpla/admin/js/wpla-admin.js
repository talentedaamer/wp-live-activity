(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	console.log("wpla_comments");

	$(document).on('heartbeat-send', function(e, data) {
		data.wpla_check_comments = true;
	});

	$(document).on('heartbeat-tick', function(e, data) {
		console.log("comments tick", data);
		// var generated_table = wpla_generate_comments_table(data.wpla_live_comments)
		// $('#wpla-live-comments').html(generated_table);
	});

	// jQuery(document).ready(function($) {
	// 	// var wpla_comments = wpla_params.wpla_comments
	// 	var generated_table = wpla_generate_comments_table(wpla_comments_params.wpla_comments)
	// 	$('#wpla-live-comments').html(generated_table);
	
	// 	function wpla_generate_comments_table(data) {
	// 		var commentsList = '';
	// 		if (data) {
	// 			commentsList += '<table class="widefat striped">';
	// 			$.each(data, function(index, comment) {
	// 				commentsList += '<tr>';
	// 					// comment author profile
	// 					commentsList += '<td>';
	// 						commentsList += '<div class="wpla-comment-author-profile">';
	// 							commentsList += '<div class="wpla-comment-author-avatar">'+ comment.author_avatar +'</div>';
	// 							commentsList += '<div class="wpla-comment-author-info">';
	// 								commentsList += '<div class="wpla-comment-header">';
	// 									commentsList += '<strong class="wpla-comment-author-name">'+ comment.author +'</strong>';
	// 									commentsList += '<strong class="wpla-comment-date">'+ comment.date +'</strong>';
	// 								commentsList += '</div>';
	// 							commentsList += '</div>';
	// 						commentsList += '</div>';
	// 					commentsList += '</td>';
	// 					// comment content
	// 					commentsList += '<td>';
	// 						commentsList += '<div class="wpla-comment-content">';
	// 							commentsList += '<strong class="wpla-comment-post-title">'+ comment.post_title +'</strong>';
	// 							commentsList += '<div class="wpla-comment-content">'+ comment.content +'</div>';
	// 						commentsList += '</div>';
	// 					commentsList += '</td>';
	// 					// comment actions
	// 					commentsList += '<td>';
	// 						commentsList += '<div class="wpla-user-actions">';
	// 							commentsList += '<a href="${user.edit_link}"><span class="dashicons dashicons-visibility"><span class="screen-reader-text">View User</span></span></a>';
	// 						commentsList += '</div>';
	// 					commentsList += '</td>';
	// 				commentsList += '</tr>';
	// 			});
	// 			commentsList += '</table>';
	// 		}
	
	// 		return commentsList;
	// 	}
	// });

})( jQuery );
