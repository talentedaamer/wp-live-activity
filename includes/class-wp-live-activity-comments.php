<?php
/**
 * WP Live Activity users
 */
class WPLiveActivityComments {
    private $cahce_key;
    private $cache_expiry;

    public function __construct($cahceKey, $cacheExpiry) {
        $this->cahce_key = $cahceKey;
        $this->cache_expiry = $cacheExpiry;

        add_filter( 'heartbeat_received', array( $this, 'wpla_heartbeat_received_comments_callback' ), 10, 2 );
        add_action( 'wp_dashboard_setup', array( $this, 'wpla_register_comments_dashboard_widget' ) );
	}

    public function wpla_heartbeat_received_comments_callback( $response, $data ) {
        if ( isset( $data['wpla_check_live_comments'] ) && $data['wpla_check_live_comments'] ) {
            $live_comments = $this->wpla_get_comments();
            $response["wpla_live_comments"] = $live_comments;
        } else {
            $response['wpla_live_comments'] = array();
        }

        return $response;
    }

    public function wpla_register_comments_dashboard_widget() {
        wp_add_dashboard_widget(
            'wpla_live_comments_widget',
            'Live Comments',
            array( $this, 'wpla_comments_dashboard_widget_content' ),
            null,
            null,
            'normal',
            'high'
        );
    }

    public function wpla_comments_dashboard_widget_content() {
        ?>
        <div id="wpla-live-comments" class="wpla-live-comments">
            <div class="wpla-loader">
                <span class="wpla-pulse wpla-green"></span>
                <span class="wpla-loader-text">Loading...</span>
            </div>
        </div>
        <?php
    }

    public function wpla_get_comments() {
        $live_comments = get_transient($this->cahce_key);
        if ($live_comments === false) {
            $number_comments = apply_filters( 'wpla_filter_number_comments', 5 );
            $args = array(
                'status' => array('approve', 'hold', 'trash'), // Comment status (approve, hold, spam, trash, post-trashed, all)
                'number' => $number_comments, // Number of comments to retrieve
                'orderby' => 'comment_date', // Order by a specific column
                'order' => 'DESC', // Order direction (ASC or DESC)
                'type' => 'comment', // Type of comment (comment, pingback, trackback)
            );
            $comments = get_comments( $args );
            $live_comments = array();
            if ( ! empty( $comments ) ) {
                foreach ( $comments as $comment ) {
                    $commenter_avatar_size = apply_filters( 'wpla_comment_user_avatar_size', 50 );
                    $comment_author_avatar = get_avatar( $comment->comment_author_email, $commenter_avatar_size );
                    $commented_post_title = get_the_title($comment->comment_post_ID);
                    $comment_content = wp_strip_all_tags($comment->comment_content);
                    $live_comments[] = array(
                        'ID' => $comment->comment_ID,
                        'comment_status' => $comment->comment_approved,
                        'author' => $this->wpla_trim_string($comment->comment_author, 18, '..'),
                        'author_email' => $this->wpla_trim_string($comment->comment_author_email, 25, '..'),
                        'author_avatar' => $comment_author_avatar,
                        'content' => $this->wpla_trim_string($comment_content, 50, '..'),
                        'date' => $comment->comment_date,
                        'date_gmt' => $comment->comment_date_gmt,
                        'parent' => $comment->comment_parent,
                        'post_ID' => $comment->comment_post_ID,
                        'post_title' => $this->wpla_trim_string($commented_post_title, 25, '..'),
                        'type' => $comment->comment_type,
                    );
                }
            }

            set_transient($this->cahce_key, $live_comments, $this->cache_expiry);
        }

        return $live_comments;
    }

    public function wpla_trim_string($content, $length = 10) {
        if (strlen($content) > $length) {
            return  substr($content, 0, $length) . '...';
        } else {
            return $content;
        }
    }
}
