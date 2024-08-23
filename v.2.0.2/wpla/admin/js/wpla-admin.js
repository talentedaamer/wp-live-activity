(function( $ ) {
	'use strict';

	/**
	 * Generated the initially provided comments
	 * comments are passed by wp_localize_script
	 * and appended to widget via jQuery
	 */
	var comments_table = wpla_generate_comments_table(wpla_params.comments)
	$('#wpla-comments').html(comments_table);

	/**
	 * send comments check to the data array
	 * it will be used in heartbeat_received function to check
	 */
	$(document).on('heartbeat-send', function(e, data) {
		data.wpla_check_comments = true;
	});

	/**
	 * heartbeat response function
	 */
	$(document).on('heartbeat-tick', function(e, data) {
		console.log('>> tick >>', data.wpla_comments[0])
		var comments_table = wpla_generate_comments_table(wpla_params.comments)
		$('#wpla-comments').html(comments_table);
	});

	function wpla_generate_comments_table(data) {
		var commentsList = '';
		if (data) {
			commentsList += '<table class="striped">';
			$.each(data, function(index, comment) {
				commentsList += '<tr>';
					commentsList += '<td>';
						commentsList += '<div class="wpla-comment-wrap">';
							// commentsList += comment_actions(comment);
							commentsList += comment_avatar(comment);
							commentsList += '<div class="wpla-comment-info">';
								commentsList += comment_header(comment);
								commentsList += comment_content(comment);
							commentsList += '</div>';
						commentsList += '</div>';
					commentsList += '</td>';
				commentsList += '</tr>';
			});
			commentsList += '</table>';
		}

		return commentsList;
	}

	function comment_avatar(data) {
		var comment_avatar = '';
		comment_avatar += '<div class="wpla-commentor-avatar">'+ data.author_avatar +'</div>';

		return comment_avatar;
	}

	function comment_header(data) {
		var comment_header = '';
		comment_header += '<div class="wpla-comment-header">';
			comment_header += '<h4 class="wpla-comment-title">'+ data.post_title +'</h4>';
			comment_header += '<strong class="wpla-comment-author-name">'+ data.author +'</strong>';
			comment_header += '<span class="wpla-seperator"> / </span>';
			comment_header += '<strong class="wpla-comment-date">'+ data.date_time +'</strong>';
		comment_header += '</div>';

		return comment_header;
	}

	function comment_content(data) {
		var comment_content = '';
		comment_content += '<div class="wpla-comment-content">';
			comment_content += '<p class="wpla-comment-content">'+ data.content +'</p>';
		comment_content += '</div>';

		return comment_content;
	}

	function comment_actions(data) {
		var comment_actions = '';
		comment_actions += '<div class="wpla-comment-actions">';
			comment_actions += '<button>Publish</button>';
			comment_actions += '<button>Delete</button>';
		comment_actions += '</div>';

		return comment_actions;
	}

})( jQuery );
