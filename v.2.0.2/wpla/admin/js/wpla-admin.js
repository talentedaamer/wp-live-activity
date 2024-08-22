(function( $ ) {
	'use strict';

	/**
	 * Generated the initially provided comments
	 * comments are passed by wp_localize_script
	 * and appended to widget via jQuery
	 */
	var comments_table = wpla_generate_comments_table(wpla_params.comments)
	$('#wpla-comments').html(comments_table);

	$(document).on('heartbeat-send', function(e, data) {
		data.wpla_check_comments = true;
	});

	$(document).on('heartbeat-tick', function(e, data) {
		console.log("tick", data);
		// var generated_table = wpla_generate_comments_table(data.wpla_live_comments)
		// $('#wpla-live-comments').html(generated_table);
	});

	// jQuery(document).ready(function($) {
	// 	// var wpla_comments = wpla_params.wpla_comments

	
	function wpla_generate_comments_table(data) {
		var commentsList = '';
		if (data) {
			commentsList += '<table class="striped">';
			$.each(data, function(index, comment) {
				commentsList += '<tr>';
					// comment author profile
					commentsList += '<td>';
						commentsList += '<div class="wpla-comment-wrap">';
							commentsList += '<div class="wpla-commentor-avatar">'+ comment.author_avatar +'</div>';
							commentsList += '<div class="wpla-comment-info">';
								commentsList += '<div class="wpla-comment-header">';
									commentsList += '<strong class="wpla-comment-author-name">'+ comment.author +'</strong>';
									commentsList += '<span class="wpla-seperator"> / </strong>';
									commentsList += '<strong class="wpla-comment-date">'+ comment.date +'</strong>';
								commentsList += '</div>';
								commentsList += '<div class="wpla-comment-content">';
									commentsList += '<h4 class="wpla-comment-title">'+ comment.post_title +'</h4>';
									commentsList += '<p class="wpla-comment-content">'+ comment.content +'</p>';
								commentsList += '</div>';
							commentsList += '</div>';
						commentsList += '</div>';
					commentsList += '</td>';
					// comment content
					// commentsList += '<td>';
					// 	commentsList += '<div class="wpla-comment-content">';
					// 		commentsList += '<strong class="wpla-comment-post-title">'+ comment.post_title +'</strong>';
					// 		commentsList += '<div class="wpla-comment-content">'+ comment.content +'</div>';
					// 	commentsList += '</div>';
					// commentsList += '</td>';
					// comment actions
					// commentsList += '<td>';
					// 	commentsList += '<div class="wpla-user-actions">';
					// 		commentsList += '<a href="${user.edit_link}"><span class="dashicons dashicons-visibility"><span class="screen-reader-text">View User</span></span></a>';
					// 	commentsList += '</div>';
					// commentsList += '</td>';
				commentsList += '</tr>';
			});
			commentsList += '</table>';
		}

		return commentsList;
	}
	// });

})( jQuery );
