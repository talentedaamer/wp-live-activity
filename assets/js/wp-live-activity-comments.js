jQuery(document).ready(function($) {
    // var wpla_comments = wpla_params.wpla_comments
    console.log("wpla_comments", wpla_comments_params);
    var generated_table = wpla_generate_comments_table(wpla_comments_params.wpla_comments)
    $('#wpla-live-comments').html(generated_table);

    $(document).on('heartbeat-send', function(e, data) {
        data.wpla_check_live_comments = true;
    });

    $(document).on('heartbeat-tick', function(e, data) {
        console.log("comments tick", data);
        var generated_table = wpla_generate_comments_table(data.wpla_live_comments)
        $('#wpla-live-comments').html(generated_table);
    });

    function wpla_generate_comments_table(data) {
        var commentsList = '';
        if (data) {
            commentsList += '<table class="widefat striped">';
            $.each(data, function(index, comment) {
                commentsList += '<tr>';
                    // comment author profile
                    commentsList += '<td>';
                        commentsList += '<div class="wpla-comment-author-profile">';
                            commentsList += '<div class="wpla-comment-author-avatar">'+ comment.author_avatar +'</div>';
                            commentsList += '<div class="wpla-comment-author-info">';
                                commentsList += '<div class="wpla-comment-header">';
                                    commentsList += '<strong class="wpla-comment-author-name">'+ comment.author +'</strong>';
                                    commentsList += '<strong class="wpla-comment-date">'+ comment.date +'</strong>';
                                commentsList += '</div>';
                            commentsList += '</div>';
                        commentsList += '</div>';
                    commentsList += '</td>';
                    // comment content
                    commentsList += '<td>';
                        commentsList += '<div class="wpla-comment-content">';
                            commentsList += '<strong class="wpla-comment-post-title">'+ comment.post_title +'</strong>';
                            commentsList += '<div class="wpla-comment-content">'+ comment.content +'</div>';
                        commentsList += '</div>';
                    commentsList += '</td>';
                    // comment actions
                    commentsList += '<td>';
                        commentsList += '<div class="wpla-user-actions">';
                            commentsList += '<a href="${user.edit_link}"><span class="dashicons dashicons-visibility"><span class="screen-reader-text">View User</span></span></a>';
                        commentsList += '</div>';
                    commentsList += '</td>';
                commentsList += '</tr>';
            });
            commentsList += '</table>';
        }

        return commentsList;
    }
});